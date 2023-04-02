<?php
namespace Deployer;

require 'recipe/laravel.php';

// Url para clonar o repositório VIA SSH (muito importante ser SSH, não copiar a de HTTPS)
set('repository', 'git@gitlab.com:kennedy/gestorhospitalar.git');

set('application', 'laravel-deployer');
set('git_tty', false);

// Por causa da pasta backend
set('shared_dirs', [
    'backend/storage',
]);
set('shared_files', [
    'backend/.env',
]);
set('writable_dirs', [
    'backend/bootstrap/cache',
    'backend/storage',
    'backend/storage/app',
    'backend/storage/app/public',
    'backend/storage/framework',
    'backend/storage/framework/cache',
    'backend/storage/framework/sessions',
    'backend/storage/framework/views',
    'backend/storage/logs',
]);


// Se algum arquivo ou diretório fora de storage tem que ser compartilhado entre os deploys
// o caminho deles (relativo a raiz do repositório) ele deve ser colocado aqui
add('shared_files', []);
add('shared_dirs', []);

// Se alguma pasta fora de storage tiver que receber permissão de escrita para a aplicação
// o caminho dela (relativo a raiz do repositório) deve ser colocada aqui
add('writable_dirs', []);


// Hosts
host('server01.asasaude.app.br')
    ->stage('production')
    ->user('asatec')
    ->identityFile('~/.ssh/id_rsa')
    ->multiplexing(false)
    ->set('http_user', 'asatec')
    ->set('http_group', 'www-data')
    ->set('deploy_path', '~/sites/asasaude.app.br/sistema/{{ application }}')
    ->set('bin/php', '/usr/local/bin/php-7.2')
    ->set('writable_mode', 'chown');

// Tasks

desc('Installing vendors');
task('deploy:vendors', function () {
    if (!commandExist('unzip')) {
        writeln('<comment>To speed up composer installation setup "unzip" command with PHP zip extension https://goo.gl/sxzFcD</comment>');
    }
    run('cd {{release_path}}/backend && {{bin/composer}} {{composer_options}}');
});

set('laravel_version', function () {
    $result = run('cd {{release_path}}/backend && {{bin/php}} artisan --version');

    preg_match_all('/(\d+\.?)+/', $result, $matches);

    $version = (string)($matches[0][0] ?? 5.5);

    writeln('<info>Laravel Framework ' . $version . '</info>');
    return $version;
});

desc('Execute artisan storage:link');
task('artisan:storage:link', function () {
    $needsVersion = '5.3';
    $currentVersion = get('laravel_version');

    if (version_compare($currentVersion, $needsVersion, '>=')) {
        run('{{bin/php}} {{release_path}}/backend/artisan storage:link');
    }
});

desc('Execute artisan view:cache');
task('artisan:view:cache', function () {
    $needsVersion = '5.6';
    $currentVersion = get('laravel_version');

    if (version_compare($currentVersion, $needsVersion, '>=')) {
        run('{{bin/php}} {{release_path}}/backend/artisan view:cache');
    }
});

desc('Execute artisan config:cache');
task('artisan:config:cache', function () {
    run('{{bin/php}} {{release_path}}/backend/artisan config:cache');
});

desc('Execute artisan optimize');
task('artisan:optimize', function () {
    $deprecatedVersion = '5.5';
    $readdedInVersion = '5.7';
    $currentVersion = get('laravel_version');

    if (
        version_compare($currentVersion, $deprecatedVersion, '<') ||
        version_compare($currentVersion, $readdedInVersion, '>=')
    ) {
        run('{{bin/php}} {{release_path}}/backend/artisan optimize');
    }
});

desc('Execute artisan cache:clear');
task('artisan:cache:clear', function () {
    run('{{bin/php}} {{release_path}}/backend/artisan cache:clear');
});

desc('Make symlink for public disk');
task('deploy:public_disk', function () {
    // Remove from source.
    run('if [ -d $(echo {{release_path}}/backend/public/storage) ]; then rm -rf {{release_path}}/backend/public/storage; fi');

    // Create shared dir if it does not exist.
    run('mkdir -p {{deploy_path}}/shared/backend/storage/app/public');

    // Symlink shared dir to release dir
    run('{{bin/symlink}} {{deploy_path}}/shared/backend/storage/app/public {{release_path}}/backend/public/storage');
});

desc('Disable maintenance mode');
task('artisan:up', function () {
    $output = run('if [ -f {{deploy_path}}/current/backend/artisan ]; then {{bin/php}} {{deploy_path}}/current/backend/artisan up; fi');
    writeln('<info>' . $output . '</info>');
});

desc('Enable maintenance mode');
task('artisan:down', function () {
    $output = run('if [ -f {{deploy_path}}/current/backend/artisan ]; then {{bin/php}} {{deploy_path}}/current/backend/artisan down; fi');
    writeln('<info>' . $output . '</info>');
});

desc('Execute artisan migrate');
task('artisan:migrate', function () {
    run('{{bin/php}} {{release_path}}/backend/artisan migrate --force');
})->once();

desc('Execute artisan migrate:fresh');
task('artisan:migrate:fresh', function () {
    run('{{bin/php}} {{release_path}}/backend/artisan migrate:fresh --force');
});


task('app:seed', function () {
    // Se tiver um seeder que tenha que ser rodado
    run('{{bin/php}} {{release_path}}/backend/artisan db:seed --force --class ProductionDatabaseSeeder');
});

task('vesta:ssl_directory', function () {
  $user = get('http_user');
  $domain = get('hostname');
  $vestaDirPath = "/home/{$user}/web/{$domain}";

  if (!test("[ -d {$vestaDirPath}/public_html/.well-known ]")) {
      run("mkdir -p {$vestaDirPath}/public_html/.well-known");
  }

  run("rm -rf {{release_path}}/backend/public/.well-known");
  run("{{bin/symlink}} {$vestaDirPath}/public_html/.well-known {{release_path}}/backend/public/.well-known");
});


// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
before('deploy:symlink', 'artisan:migrate');
after('artisan:migrate', 'app:seed');
before('deploy:symlink', 'deploy:public_disk');

// SSL
// after('deploy:symlink', 'vesta:ssl_directory');
