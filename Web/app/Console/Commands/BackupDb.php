<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\DbDumper\Databases\PostgreSql;
use Carbon\Carbon;

class BackupDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:db_postgress';

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
        date_default_timezone_set('EST');
        try {
            $this->info('The backup has been started');
            $backup_name = 'backup-' . Carbon::now()->format('Y-m-d')  . '.sql';
            $backup_path = 'app/backups/' . $backup_name;
            PostgreSql::create()
                ->setDbName(env('DB_DATABASE'))
                ->setUserName(env('DB_USERNAME'))
                ->setPort(env('DB_PORT'))
                ->setPassword(env('DB_PASSWORD'))
                ->dumpToFile($backup_path);
            $this->info('The backup has been proceed successfully.');
        } catch (ProcessFailedException $exception) {
            logger()->error('Backup exception', compact('exception'));
            $this->error('The backup process has been failed.');
        }
    }
}
