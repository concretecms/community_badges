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

class AfterGiveAchievement extends GenericEvent
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
     * @return AfterGiveAchievement
     */
    public function setUserBadge(UserBadge $userBadge): AfterGiveAchievement
    {
        $this->userBadge = $userBadge;
        return $this;
    }

}
