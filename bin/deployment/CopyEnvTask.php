<?php

namespace Deployment;

use Mage\Task\AbstractTask;
use Symfony\Component\Process\Process;

class CopyEnvTask extends AbstractTask
{
    public function getName()
    {
        return 'custom/copy-env';
    }

    public function getDescription()
    {
        return sprintf('[Custom] Create .env for %s environment.', $this->options['env']);
    }

    public function getDefaults()
    {
        return [
            'env' => 'staging'
        ];
    }

    public function execute()
    {
        $command = sprintf('cp config/hosts/.env.%s.dist .env.local', $this->options['env']);

        /** @var Process $process */
        $process = $this->runtime->runCommand($command);

        return $process->isSuccessful();
    }
}
