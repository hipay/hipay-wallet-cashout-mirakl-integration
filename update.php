<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

echo '<div class="alert alert-dismissible alert-info">Backup data </div>';

system('rm -R '.__DIR__.'/update/ ', $status);
system('rm  '.__DIR__.'/backup.tar.gz ', $status);
system('tar czvf  '.__DIR__.'/backup.tar.gz '.__DIR__.'/ ', $status);

# MysqlDump

echo '<div class="alert alert-dismissible alert-info">updating app, this may take a while </div>';

putenv('COMPOSER_HOME='.__DIR__.'/vendor/bin/composer');
 system('composer config --global github-oauth.github.com 2692ed08b94c436069f03eebda7e36e59ad10cfc', $status);
system('yes | composer create-project hipay/hipay-wallet-cashout-mirakl-integration '.__DIR__.'/update dev-feature/EC-122  2>&1', $status);

system('chmod 755 -R '.__DIR__, $status);

$oldParameters    = Yaml::parse(file_get_contents(__DIR__.'/config/parameters.yml'));
$updateParameters = Yaml::parse(file_get_contents(__DIR__.'/update/config/parameters.yml'));

$newParameters = array_replace_recursive($updateParameters, $oldParameters);

echo '<div class="alert alert-dismissible alert-info">updating parameters.yml  </div>';

print_r($newParameters);

$newParametersYaml = Yaml::dump($newParameters);

file_put_contents(__DIR__.'/update/config/parameters.yml', $newParametersYaml);

echo '<div class="alert alert-dismissible alert-info">Copying new files</div>';

system('rsync -av  '.__DIR__.'/update/ '.__DIR__.'/', $status);
//system('rm -R update/ ', $status);

echo '<div class="alert alert-dismissible alert-info">updating database  </div>';

system('cd '.__DIR__.' && php bin/console orm:schema-tool:update --force');

return '<div class="alert alert-dismissible alert-success">Success</div>';
