<?php
namespace Deployer;

require 'vendor/autoload.php';
require 'recipe/common.php';
require 'recipe/laravel.php';

// Set env
set('ip_prod', '54.64.206.43');
set('ip_stg', '194.233.72.10');

set('prod', 'prod_action');
set('stg', 'plats_action');
set('dev', 'action');
set('main', 'prod');
set('cws', 'cws');
set('event', 'event');

set('repository', 'git@github.com:plats-network/plats-backend-action-hub.git');
set('prod_path', '/var/www/apps/{{prod}}');
set('stg_path', '/home/deploy/apps/{{stg}}');
set('dev_path', '/var/www/plats/{{dev}}');
set('main_path', '/var/www/apps/{{main}}');
set('cws_path', '/var/www/apps/{{cws}}');
set('event_path', '/var/www/apps/{{event}}');

set('keep_releases', 5);
set('allow_anonymous_stats', false);
set('git_tty', true);
set('use_relative_symlink', false);
set('ssh_multiplexing', false);

// Shared files/dirs between deploys 
add('shared_files', ['.env']);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', [
    'bootstrap/cache',
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
]);

// Hosts
host('prod')
    ->set('hostname', '{{ip_prod}}')
    ->set('stage', 'production')
    ->set('remote_user', 'deploy')
    ->set('identityFile', '~/.ssh/prod_plats')
    ->set('branch', 'release')
    ->set('deploy_path', '{{prod_path}}');

host('main')
    ->set('hostname', '{{ip_prod}}')
    ->set('stage', 'main')
    ->set('remote_user', 'deploy')
    ->set('identityFile', '~/.ssh/prod_plats')
    ->set('branch', 'prod')
    ->set('deploy_path', '{{main_path}}');

host('cws')
    ->set('hostname', '{{ip_prod}}')
    ->set('stage', 'cws')
    ->set('remote_user', 'deploy')
    ->set('identityFile', '~/.ssh/prod_plats')
    ->set('branch', 'prod')
    ->set('deploy_path', '{{cws_path}}');

host('event')
    ->set('hostname', '{{ip_prod}}')
    ->set('stage', 'event')
    ->set('remote_user', 'deploy')
    ->set('identityFile', '~/.ssh/prod_plats')
    ->set('branch', 'prod')
    ->set('deploy_path', '{{event_path}}');

host('stg')
    ->set('hostname', '{{ip_stg}}')
    ->set('stage', 'staging')
    ->set('remote_user', 'deploy')
    ->set('identityFile', '~/.ssh/plats')
    ->set('branch', 'staging')
    ->set('deploy_path', '{{stg_path}}');

host('dev')
    ->set('hostname', '{{ip_stg}}')
    ->set('stage', 'development')
    ->set('remote_user', 'deploy')
    ->set('identityFile', '~/.ssh/plats')
    ->set('branch', 'staging_v2')
    ->set('deploy_path', '{{dev_path}}');

// restart php-fpm
task('reload:php-fpm', function () {
    run('sudo /usr/sbin/service php8.1-fpm reload');
});

// Run npm
task('npm:run', function () {
    $envStage = get('stage');
    writeln("Run npm: " . $envStage);

    if ($envStage == 'production') {
        run('cd {{prod_path}}/current && npm install && npm run prod && php artisan storage:link');
    } elseif ($envStage == 'main') {
        run('cd {{main_path}}/current && npm install && npm run prod && php artisan storage:link');
    } elseif ($envStage == 'cws') {
        run('cd {{cws_path}}/current && npm install && npm run prod && php artisan storage:link');
    } elseif ($envStage == 'event') {
        run('cd {{event_path}}/current && npm install && npm run prod && php artisan storage:link');
    } elseif ($envStage == 'staging') {
        run('cd {{stg_path}}/current && npm install && npm run prod && php artisan storage:link');
    } else {
        run('cd {{dev_path}}/current && npm install && npm run prod && php artisan storage:link');
    }
});

task('deploy', [
    'deploy:unlock',
    'deploy:prepare',
    'deploy:vendors',
    'deploy:symlink',
    'npm:run',
    'reload:php-fpm',
    'deploy:cleanup',
]);

after('deploy:failed', 'deploy:unlock');
before('deploy:symlink', 'artisan:migrate');
