<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Events;

use PortlandLabs\CommunityBadges\Entity\UserBadge;
use Symfony\Component\EventDispatcher\GenericEvent;

class AfterGiveAward extends GenericEvent
{
    /** @var UserBadge */
    protected $userBadge;

    /**
     * @return UserBadge
     */
    public function getUserBadge(): UserBadge
    {
        return $this->userBadge;
    }

    /**
     * @param UserBadge $userBadge
     * @return AfterGiveAward
     */
    public function setUserBadge(UserBadge $userBadge): AfterGiveAward
    {
        $this->userBadge = $userBadge;
        return $this;
    }

}
