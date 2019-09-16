<?php

namespace Deployment;

use Symfony\Component\Process\Process;
use Mage\Task\AbstractTask;

class InstallComposerTask extends AbstractTask
{
    public function getName()
    {
        return 'custom/install-composer';
    }

    public function getDescription()
    {
        return '[Custom] Install composer and run it';
    }

    public function execute()
    {
        $command1 = sprintf('curl -sS https://getcomposer.org/installer | %s', $this->runtime->getEnvOption('php_bin'));

        /** @var Process $process1 */
        $process1 = $this->runtime->runCommand($command1);

        $command2 = sprintf('%s composer.phar install', $this->runtime->getEnvOption('php_bin'));

        /** @var Process $process1 */
        $process2 = $this->runtime->runCommand($command2);


        return $process1->isSuccessful() && $process2->isSuccessful();
    }
}
