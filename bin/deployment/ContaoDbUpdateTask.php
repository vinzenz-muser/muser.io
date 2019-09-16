<?php

namespace Deployment;

use Mage\Task\AbstractTask;
use Symfony\Component\Process\Process;

class ContaoDbUpdateTask extends AbstractTask
{
    public function getName()
    {
        return 'custom/contao-db-update';
    }

    public function getDescription()
    {
        return '[Custom] Update contao database schema.';
    }

    public function execute()
    {
        $command = sprintf('%s bin/console dev:contao:db-update --dump-sql --force --no-interaction', $this->runtime->getEnvOption('php_bin'));

        /** @var Process $process */
        $process = $this->runtime->runCommand($command);

        return $process->isSuccessful();
    }
}
