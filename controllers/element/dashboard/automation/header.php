<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityBadges\Controller\Element\Dashboard\Automation;

use Concrete\Core\Controller\ElementController;
use Concrete\Core\Support\Facade\Application;
use PortlandLabs\CommunityBadges\Automation\Triggers\Driver\DriverInterface;
use PortlandLabs\CommunityBadges\Automation\Triggers\Driver\Manager;

class Header extends ElementController
{
    /**
     * The handle of the package defining this element.
     *
     * @var string|null
     */
    protected $pkgHandle = 'community_badges';

    public function getElement()
    {
        return 'dashboard/automation/header';
    }

    public function view() {

        $driverList = [];

        $app = Application::getFacadeApplication();
        $driverManager = $app->make(Manager::class);

        foreach ($driverManager->getDrivers() as $driverHandle => $driver) {
            /** @var DriverInterface $driver */
            $driverList[$driverHandle] = $driver->getName();
        }

        $this->set('driverList', $driverList);
    }
}