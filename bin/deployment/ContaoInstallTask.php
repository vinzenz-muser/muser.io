<?php

namespace Deployment;

use Mage\Task\AbstractTask;
use Symfony\Component\Process\Process;

class ContaoInstallTask extends AbstractTask
{
    public function getName()
    {
        return 'custom/contao-install';
    }

    public function getDescription()
    {
        return '[Custom] Install Contao directories.';
    }

    public function execute()
    {
        $command = sprintf('%s bin/console contao:install public', $this->runtime->getEnvOption('php_bin'));

        /** @var Process $process */
        $process = $this->runtime->runCommand($command);

        return $process->isSuccessful();
    }
}
