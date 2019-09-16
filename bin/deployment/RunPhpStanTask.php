<?php

namespace Deployment;

use Symfony\Component\Process\Process;
use Mage\Task\AbstractTask;

class RunPhpStanTask extends AbstractTask
{
    public function getName()
    {
        return 'custom/run-phpstan';
    }

    public function getDescription()
    {
        return '[Custom] Run PHPStan';
    }

    public function getDefaults()
    {
        return [
            'level' => 7
        ];
    }

    public function execute()
    {
        /** @var Process $process */
        $process = $this->runtime->runCommand(sprintf('./vendor/bin/phpstan analyze src/ --level=%d', $this->options['level']));

        return $process->isSuccessful();
    }
}