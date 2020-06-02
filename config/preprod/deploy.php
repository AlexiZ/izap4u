<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;
use EasyCorp\Bundle\EasyDeployBundle\Configuration\Option;

return new class extends DefaultDeployer
{
    public function configure()
    {
        return $this->getConfigBuilder()
            // SSH connection string to connect to the remote server (format: user@host-or-IP:port-number)
            ->server('u87073604@home655277423.1and1-data.host')
            // the absolute path of the remote server directory where the project is deployed
            ->deployDir('/kunden/homepages/39/d655277423/htdocs/izap4u')
            // the URL of the Git repository where the project code is hosted
            ->repositoryUrl('git@github.com:AlexiZ/izap4u.git')
            // the repository branch to deploy
            ->repositoryBranch('master')
            ->keepReleases(3)
            ->useSshAgentForwarding(true)
            ->remoteComposerBinaryPath('php7.3-cli /kunden/homepages/39/d655277423/htdocs/izap4u/composer.phar')
            ->remotePhpBinaryPath('php7.3-cli')
            ->symfonyEnvironment('prod')
            ->sharedFilesAndDirs(['.env', 'var/log', 'public/images/uploads'])
        ;
    }

    public function beforePreparing()
    {
        $this->log('Remote composer dump autoload');
        $this->runRemote(sprintf('%s dump-autoload', $this->getConfig(Option::remoteComposerBinaryPath)));
    }

    public function beforeFinishingDeploy()
    {
        $this->runRemote('{{ console_bin }} doctrine:migrations:migrate');
        $this->log('Migrations have been executed.');
    }
};
