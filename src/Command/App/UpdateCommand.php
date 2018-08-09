<?php

namespace HiPay\Wallet\Mirakl\Integration\Command\App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class UpdateCommand extends Command
{
    protected $parameters;

    /**
     *
     * @param type $parameters
     */
    public function __construct($parameters)
    {

        parent::__construct();

        $this->parameters = $parameters;

    }

    /**
     *
     */
    protected function configure()
    {
        $this->setName('app:update')
            ->setDescription('Update application');
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        echo '<div class="alert alert-dismissible alert-info">Backup data </div>';

        // Erase old backup

        system('rm -R ' . __DIR__ . '/../../../update/ ', $status);
        system('rm  ' . __DIR__ . '/../../../backup.tar.gz ', $status);

        // Backup Application files

        system('tar czvf  ' . __DIR__ . '/../../../backup.tar.gz ' . __DIR__ . '/../../../ ', $status);

        // Backup database data and schema

        $backupName = 'backup-' . time() . '.sql';

        system(
            'mysqldump -u ' . $this->parameters['db.username'] . ' -p' . $this->parameters['db.password'] . ' -h ' .
            $this->parameters['db.host'] . ' ' .
            $this->parameters['db.name'] . ' > ' . __DIR__ . '/../../../' . $backupName . ' ',
            $status
        );

        echo '<div class="alert alert-dismissible alert-info">updating app, this may take a while </div>';

        // Create hipay-wallet-cashout-mirakl-integration in update folder

        putenv('COMPOSER_HOME=' . __DIR__ . '/../../../vendor/bin/composer');

        system('composer config -g github-oauth.github.com ' . $this->parameters['github.token']);

        system(
            'yes | composer create-project hipay/hipay-wallet-cashout-mirakl-integration '
            . __DIR__ . '/../../../update 2>&1',
            $status
        );

        system('chmod 755 -R ' . __DIR__ . '/../../../', $status);

        // Update parameters.yml with new field

        $oldParameters = Yaml::parse(file_get_contents(__DIR__ . '/../../../config/parameters.yml'));
        $updateParameters = Yaml::parse(file_get_contents(__DIR__ . '/../../../update/config/parameters.yml'));

        $newParameters = array_replace_recursive($updateParameters, $oldParameters);

        echo '<div class="alert alert-dismissible alert-info">updating parameters.yml  </div>';

        print_r($newParameters);

        $newParametersYaml = Yaml::dump($newParameters);

        file_put_contents(__DIR__ . '/../../../update/config/parameters.yml', $newParametersYaml);

        echo '<div class="alert alert-dismissible alert-info">Copying new files</div>';

        system(
            'rsync -av --delete-after --exclude="backup.tar.gz" --exclude="' .
            $backupName . '" ' . __DIR__ . '/../../../update/ ' . __DIR__ . '/../../../',
            $status
        );
        system('rm -R update/ ', $status);

        echo '<div class="alert alert-dismissible alert-info">updating database  </div>';

        system('cd ' . __DIR__ . '/../../../ && php bin/console orm:schema-tool:update --force');
    }
}