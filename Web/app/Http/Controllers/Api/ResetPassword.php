<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ResetPasswordRequest as ResetPasswordRequestValidate;
use App\Http\Requests\SetNewPasswordRequest;
use App\Http\Resources\ResetPasswordResource;
use App\Jobs\SendResetPasswordEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Notifications\ResetPasswordRequest;
use App\Services\ResetPasswordService;
use App\Services\UserService;

class ResetPassword extends ApiController
{
    /**
     * @var ResetPasswordService
     */
    protected $resetPasswordService;

    /**
     * @var ResetPasswordService
     */
    protected $userService;

    /**
     * @param ResetPasswordService $resetPasswordService
     */
    public function __construct(ResetPasswordService $resetPasswordService, UserService $userService)
    {
        $this->resetPasswordService = $resetPasswordService;
        $this->userService = $userService;
    }

    /**
     * Create token password reset.
     *
     * @param  ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function sendMail(Request $request)
    {
        $this->validateEmailUnique($request);
        $resetPassword = $this->resetPasswordService->findByEmail($request->email);
        $isExpired = $this->isExpired($this->resetPasswordService->findByEmail($request->email));
        if (!$isExpired && $resetPassword) {
            return $this->respondError('Please wait 10 minutes from the time of sending the mail.', 400, TIME_INVALID);
        }
        $user =  $this->userService->findByEmail($request->email);
        $passwordReset = $this->resetPasswordService->updateOrCreate($user);
        $passwordReset->user_name = $user->name;
        $passwordReset->url = env('APP_URL') . '/api/reset-password/' . $passwordReset->token;
        $data = new ResetPasswordResource($passwordReset);
        if ($passwordReset) {
            dispatch(new SendResetPasswordEmail($data));
        }
        return $this->respondSuccess('We have emailed your OTP verification code!');
    }

    /**
     * Reset password action
     *
     * @param  SetNewPasswordRequest $request
     * @param  $token
     * @return JsonResponse
     */
    public function reset(SetNewPasswordRequest $request)
    {
        $passwordReset = $this->getInfoResetPassword($request->post('email'), $request->post('code'));
        if (!$passwordReset) {
            return $this->respondError('This password reset code is invalid.', 400, RESET_CODE_INVALID);
        }
        $this->userService->changePassword(null, $request->post('password'), $request->post('email'));
        $passwordReset->forceDelete();

        return $this->respondSuccess('Change password successfully!');
    }

    /**
     * Verify action with code
     *
     * @param  ResetPasswordRequestValidate $request
     * @param  $code
     * @return JsonResponse
     */
    public function verifyCode(ResetPasswordRequestValidate $request)
    {
        $passwordReset = $this->getInfoResetPassword($request->post('email'), $request->post('code'));
        if (!$passwordReset) {
            return $this->respondError('This password reset code is invalid.', 400, RESET_CODE_INVALID);
        }
        $dataResponse = [
            'email' => $passwordReset->email,
            'code' => $passwordReset->code,
        ];

        return $this->respondWithData($dataResponse, 'Please set a new password.');
    }

    /**
     * Check expiration date
     *
     * @param  $model
     * @return Boolean
     */
    public function isExpired($model)
    {
        return $model ? Carbon::parse($model->updated_at)->addMinutes(10)->isPast() : false;
    }

    /**
     * Get infomation of reset password model
     *
     * @param  $passwordReset
     * @return Boolean
     */
    public function getInfoResetPassword($email, $code)
    {
        return $this->resetPasswordService->getInfoResetPassword($email, $code);
    }

    /**
     * Validate email unique
     *
     * @param  $request
     */
    public function validateEmailUnique($request)
    {
        return $request->validate(['email' => ['required', 'max:' . INPUT_MAX_LENGTH, 'email', 'exists:users,email']]);
    }
}
