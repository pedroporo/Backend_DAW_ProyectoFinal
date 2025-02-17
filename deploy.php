<?php

namespace Deployer;

require 'vendor/deployer/deployer/recipe/laravel.php';

set('application', 'Backend API');
set('keep_releases', 5);
add('shared_dirs', ['./storage', './bootstrap/cache']);
add('shared_files', ['.env']);
add('writable_dirs', ['./storage', './bootstrap/cache']);
host('backend.worldmemistic.duckdns.org')->set('remote_user', 'deployer')
    ->set('port','22002')
    ->set('identity_file', '~/.ssh/id_rsa')
    ->set('deploy_path', '/var/www/api');

task('build', function () {
    run('cd {{release_path}} && npm install && npm run build');
});
task('reload:php-fpm', function () {
    run('sudo /etc/init.d/php8.4-fpm restart');
});

after('deploy:failed', 'deploy:unlock');
after('deploy', 'reload:php-fpm');
before('deploy:symlink', 'artisan:migrate:fresh');
before('deploy:symlink', 'artisan:db:seed');
before('deploy:symlink', 'artisan:storage:link');
