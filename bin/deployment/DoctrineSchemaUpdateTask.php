<?php

namespace Deployment;

use Mage\Task\AbstractTask;
use Symfony\Component\Process\Process;

class DoctrineSchemaUpdateTask extends AbstractTask
{
    public function getName()
    {
        return 'custom/doctrine-schema-update';
    }

    public function getDescription()
    {
        return '[Custom] Update database schema.';
    }

    public function execute()
    {
        $command = sprintf('%s bin/console doctrine:schema:update --dump-sql --force', $this->runtime->getEnvOption('php_bin'));

        /** @var Process $process */
        $process = $this->runtime->runCommand($command);

        return $process->isSuccessful();
    }
}
