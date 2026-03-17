<?php

declare(strict_types=1);

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Automation\Triggers\Driver;

use Concrete\Core\Application\Application;
use Concrete\Core\Support\Manager as CoreManager;
use Illuminate\Contracts\Container\Container;

class Manager extends CoreManager
{
    public function __construct(Application $app)
    {
        /** @noinspection PhpParamsInspection */
        parent::__construct($app);

        $this->driver('enter_group');
        $this->driver('leave_group');
        $this->driver('account_age');
        $this->driver('community_points');
        $this->driver('action_count');
    }

    /** @noinspection PhpUnused */
    public function createEnterGroupDriver()
    {
        return $this->app->get(EnterGroupDriver::class);
    }

    /** @noinspection PhpUnused */
    public function createLeaveGroupDriver()
    {
        return $this->app->get(LeaveGroupDriver::class);
    }

    /** @noinspection PhpUnused */
    public function createAccountAgeDriver()
    {
        return $this->app->get(AccountAgeDriver::class);
    }

    /** @noinspection PhpUnused */
    public function createCommunityPointsDriver()
    {
        return $this->app->get(CommunityPointsDriver::class);
    }

    /** @noinspection PhpUnused */
    public function createActionCountDriver(): ActionCountDriver
    {
        return $this->app->get(ActionCountDriver::class);
    }

    public function register()
    {
        /** @var DriverInterface $driver */
        foreach ($this->getDrivers() as $driver) {
            $driver->register();
        }
    }
}
