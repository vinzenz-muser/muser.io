<?php

namespace Deployment;

use Mage\Task\AbstractTask;
use Symfony\Component\Process\Process;

class CreateSharedDirectoriesTask extends AbstractTask
{
    public function getName()
    {
        return 'custom/create-shared-directories';
    }

    public function getDescription()
    {
        return sprintf('[Custom] Create shared directories.');
    }

    public function execute()
    {
        $directories = [
            'files',
            'assets/images',
            'var/log'
        ];

        $success = true;
        foreach ($directories as $directory) {
            $command = sprintf('mkdir -p ../../shared/%s', $directory);

            /** @var Process $process */
            $process = $this->runtime->runCommand($command);

            $success &= $process->isSuccessful();
        }

        return $success;
    }
}
