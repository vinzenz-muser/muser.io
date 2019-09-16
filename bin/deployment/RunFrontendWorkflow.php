<?php

namespace Deployment;

use Symfony\Component\Process\Process;
use Mage\Task\AbstractTask;

class RunFrontendWorkflow extends AbstractTask
{
    public function getName()
    {
        return 'custom/frontend-workflow';
    }

    public function getDescription()
    {
        return '[Custom] Run frontend workflow';
    }

    public function execute()
    {
        $command = sprintf('yarn run build:prod');

        /** @var Process $process */
        $process = $this->runtime->runCommand($command);

        return $process->isSuccessful();
    }
}
