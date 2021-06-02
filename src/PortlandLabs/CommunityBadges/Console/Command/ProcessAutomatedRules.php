<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Console\Command;

use PortlandLabs\CommunityBadges\Automation\Triggers\Driver\DriverInterface;
use PortlandLabs\CommunityBadges\Automation\Triggers\Driver\Manager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Concrete\Core\Support\Facade\Application;

class ProcessAutomatedRules extends Command
{
    protected function configure()
    {
        $this
            ->setName('community-badges:process-automated-rules')
            ->setDescription(t('Process all automated rules that are not event-based.'));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = Application::getFacadeApplication();
        /** @var Manager $driverManager */
        $driverManager = $app->make(Manager::class);

        foreach ($driverManager->getDrivers() as $driver) {
            /** @var DriverInterface $driver */
            $driver->processManually($input, $output);
        }
    }
}
