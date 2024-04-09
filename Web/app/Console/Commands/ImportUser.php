<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\User;
use App\Models\Event\EventUserTicket;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ImportUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import all user from excel';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        printf("Start Import\n");
        $users = (new FastExcel)->import(public_path().'/list_users.xlsx', function ($line) {
            $email = Str::lower($line['email']);

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $user = User::where('email', $email)->first();

                if ($user) {
                    $check = EventUserTicket::whereUserId($user->id)
                        ->where('task_id', '9a0ad18b-de5b-4881-a323-f141420713ab')
                        ->exists();

                    if (!$check) {
                        EventUserTicket::create([
                            'name' => $user->name,
                            'phone' => $user->phone ?? '0934238'.random_int(100, 999),
                            'email' => $user->email,
                            'task_id' => '9a0ad18b-de5b-4881-a323-f141420713ab',
                            'user_id' => $user->id,
                            'is_checkin' => true,
                            'hash_code' => Str::random(35),
                            'is_vip' => true
                        ]);
                    }
                } else {
                    $user = User::create([
                        'name' => 'Guest-'.Carbon::now()->timestamp,
                        'email' => $email,
                        'password' => '123456a@',
                        'phone' => '0934238'.random_int(100, 999),
                        'role' => GUEST_ROLE,
                        'confirm_at' => now(),
                        'status' => USER_CONFIRM
                    ]);

                    if ($user) {
                        EventUserTicket::create([
                            'name' => $user->name,
                            'phone' => $user->phone,
                            'email' => $email,
                            'task_id' => '9a0ad18b-de5b-4881-a323-f141420713ab',
                            'user_id' => $user->id,
                            'is_checkin' => true,
                            'hash_code' => Str::random(35),
                            'is_vip' => true
                        ]);
                    }
                }
            }
        });
        printf('End Import');

        return Command::SUCCESS;
    }
}
