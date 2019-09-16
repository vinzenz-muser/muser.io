<?php

namespace Deployment;

use Mage\Task\AbstractTask;
use Symfony\Component\Process\Process;

class ContaoSymlinksTask extends AbstractTask
{
    public function getName()
    {
        return 'custom/contao-symlinks';
    }

    public function getDescription()
    {
        return '[Custom] Symlink the public contao resources into the public directory.';
    }

    public function execute()
    {
        $command = sprintf('%s bin/console contao:symlinks public', $this->runtime->getEnvOption('php_bin'));

        /** @var Process $process */
        $process = $this->runtime->runCommand($command);

        return $process->isSuccessful();
    }
}
