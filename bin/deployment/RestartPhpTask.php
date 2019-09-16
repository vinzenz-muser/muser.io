<?php

namespace Deployment;

use Mage\Task\AbstractTask;
use Symfony\Component\Process\Process;

class RestartPhpTask extends AbstractTask
{
    public function getName()
    {
        return 'custom/restart-php';
    }

    public function getDescription()
    {
        return '[Custom] Restarts all PHP-CGI processes to clear OPCache.';
    }

    public function execute()
    {
        /** @var Process $process */
        $this->runtime->runCommand('pkill lsphp');

        // This is a hacky way to ignore the current state
        // of Cyon servers, which will return a non-0 return code
        return true;
    }
}
