<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\User;
use App\Models\Event\EventUserTicket;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SetVip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:vip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        printf("Start\n\n");
        $users = (new FastExcel)->import(public_path().'/list_users.xlsx', function ($line) {
            $email = Str::lower($line['email']);

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $user = User::whereRaw('lower(email) = ? ', [$email])->first();

                if ($user) {
                    $vip = EventUserTicket::whereUserId($user->id)
                        ->where('task_id', '9a131bf1-d41a-4412-a075-599e97bf6dcb')
                        ->first();

                    if ($vip && empty($vip->is_vip)) {
                        var_dump($vip->is_vip);
                        $vip->update(['is_vip' => true]);
                    }
                }
            }
        });
        printf("End\n\n");
        return Command::SUCCESS;
    }
}
