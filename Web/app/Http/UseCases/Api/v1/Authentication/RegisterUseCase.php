<?php

declare(strict_types=1);

namespace App\Http\UseCases\Api\v1\Authentication;

use App\Http\Shared\MakeApiResponse;
use App\Mail\VerifyMail;
use App\Models\AffiliateUser;
use App\Models\MultiTenant;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Illuminate\Support\Arr;

final class RegisterUseCase
{
    use MakeApiResponse;

    public function handle(array $data): JsonResponse
    {

        $referral_code = Arr::get($data, 'referral.desk.price');
        $referral_user = '';
        if ($referral_code) {
            $referral_user = User::where('affiliate_code', $referral_code)->first();
        }
        try {
            DB::beginTransaction();

            $tenant = MultiTenant::create(['tenant_username' => Arr::get($data, 'first_name')]);
            $userDefaultLanguage = Setting::where('key', 'user_default_language')->first()->value ?? 'vi';
            //Check $userDefaultLanguage != 'vi' then set $userDefaultLanguage = 'en'
            if ($userDefaultLanguage != 'vi') {
                $userDefaultLanguage = 'vi';
            }

            /* @var User $user*/
            $user = User::create([
                'first_name' => Arr::get($data, 'first_name'),
                'last_name' => Arr::get($data, 'last_name'),
                'email' => Arr::get($data, 'email'),
                'language' => $userDefaultLanguage,
                'password' => Hash::make(Arr::get($data, 'password')),
                'tenant_id' => $tenant->id,
                'affiliate_code' => generateUniqueAffiliateCode(),
            ])->assignRole(Role::ROLE_ADMIN);

            $plan = Plan::whereIsDefault(true)->first();

            Subscription::create([
                'plan_id' => $plan->id,
                'plan_amount' => $plan->price,
                'plan_frequency' => Plan::MONTHLY,
                'starts_at' => Carbon::now(),
                'ends_at' => Carbon::now()->addDays($plan->trial_days),
                'trial_ends_at' => Carbon::now()->addDays($plan->trial_days),
                'status' => Subscription::ACTIVE,
                'tenant_id' => $tenant->id,
                'no_of_vcards' => $plan->no_of_vcards,
            ]);

            if ($referral_user) {
                $affiliateUser = new AffiliateUser();
                $affiliateUser->affiliated_by = $referral_user->id;
                $affiliateUser->user_id = $user->id;
                $affiliateUser->amount = getSuperAdminSettingValue('affiliation_amount');
                $affiliateUser->save();
            }

            DB::commit();
            $token = Password::getRepository()->create($user);
            $appBaseUrl = config('app.url');
            //Check app url is not end with /
            if (substr($appBaseUrl, -1) == '/') {
                $appBaseUrl = substr($appBaseUrl, 0, -1);
            }
            $data['url'] = $appBaseUrl .'/verify-email/'.$user->id.'/'.$token;
            $data['user'] = $user;
            Mail::to($user->email)->send(new VerifyMail($data));

            Log::info('New User Registered as '.$user->full_name.' with email '.$user->email);

            //$user->notify(new NewRegistration());

            Flash::success(__('messages.placeholder.registered_success'));

            return $this->successResponse([
                'message' => 'User registered successfully.',
                'token' => $user->createToken(Str::random(15))->plainTextToken,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            //throw new UnprocessableEntityHttpException($e->getMessage());
            //Send Error Response
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}
