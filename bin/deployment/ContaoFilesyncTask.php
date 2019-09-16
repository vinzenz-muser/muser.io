<?php

namespace Deployment;

use Mage\Task\AbstractTask;
use Symfony\Component\Process\Process;

class ContaoFilesyncTask extends AbstractTask
{
    public function getName()
    {
        return 'custom/contao-filesync';
    }

    public function getDescription()
    {
        return '[Custom] Sync the Contao filesystem.';
    }

    public function execute()
    {
        $command = sprintf('%s bin/console contao:filesync', $this->runtime->getEnvOption('php_bin'));

        /** @var Process $process */
        $process = $this->runtime->runCommand($command);

        return $process->isSuccessful();
    }
}
