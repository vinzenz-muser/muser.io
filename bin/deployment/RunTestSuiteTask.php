<?php

namespace Deployment;

use Symfony\Component\Process\Process;
use Mage\Task\AbstractTask;

class RunTestSuiteTask extends AbstractTask
{
    public function getName()
    {
        return 'custom/run-tests';
    }

    public function getDescription()
    {
        return '[Custom] Run Test Suite';
    }

    public function execute()
    {
        /** @var Process $process */
        $process = $this->runtime->runCommand('./vendor/bin/simple-phpunit');

        return $process->isSuccessful();
    }
}