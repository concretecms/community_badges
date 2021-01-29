<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Automation\Triggers\Driver;

use Concrete\Core\Application\Application;
use Concrete\Core\Support\Manager as CoreManager;

class Manager extends CoreManager
{
    /** @var Application */
    protected $app;

    /** @noinspection PhpUnused */
    public function createEnterGroupDriver()
    {
        return $this->app->make(EnterGroupDriver::class);
    }

    /** @noinspection PhpUnused */
    public function createLeaveGroupDriver()
    {
        return $this->app->make(LeaveGroupDriver::class);
    }

    /** @noinspection PhpUnused */
    public function createAccountAgeDriver()
    {
        return $this->app->make(AccountAgeDriver::class);
    }

    /** @noinspection PhpUnused */
    public function createCommunityPointsDriver()
    {
        return $this->app->make(CommunityPointsDriver::class);
    }

    public function __construct(Application $app)
    {
        /** @noinspection PhpParamsInspection */
        parent::__construct($app);

        $this->driver('enter_group');
        $this->driver('leave_group');
        $this->driver('account_age');
        $this->driver('community_points');
    }

    public function register()
    {
        foreach ($this->getDrivers() as $driver) {
            /** @var DriverInterface $driver */
            $driver->register();
        }
    }
}
