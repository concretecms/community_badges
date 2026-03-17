<?php

declare(strict_types=1);

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Console\Command;

use Concrete\Core\Support\Facade\Application;
use PortlandLabs\CommunityBadges\Automation\Triggers\Driver\DriverInterface;
use PortlandLabs\CommunityBadges\Automation\Triggers\Driver\Manager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessAutomatedRules extends Command
{
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('community-badges:process-automated-rules')->setDescription(t(
            'Process all automated rules that are not event-based.',
        ));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $app = Application::getFacadeApplication();
        /** @var Manager $driverManager */
        $driverManager = $app->make(Manager::class);

        /** @var DriverInterface $driver */
        foreach ($driverManager->getDrivers() as $driver) {
            $driver->processManually($input, $output);
        }

        return self::SUCCESS;
    }
}
