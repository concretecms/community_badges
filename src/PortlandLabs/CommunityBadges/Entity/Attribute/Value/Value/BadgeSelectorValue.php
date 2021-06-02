<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Entity\Attribute\Value\Value;

use Concrete\Core\Entity\Attribute\Value\Value\AbstractValue;
use Concrete\Core\Support\Facade\Application;
use Doctrine\ORM\Mapping as ORM;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Entity\Badge;
use PortlandLabs\CommunityBadges\Exceptions\BadgeNotFound;

/**
 * @ORM\Entity
 * @ORM\Table(name="BadgeSelectorValue")
 */
class BadgeSelectorValue extends AbstractValue
{
    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $badgeId = null;

    /**
     * @return int
     */
    public function getBadgeId(): int
    {
        return $this->badgeId;
    }

    /**
     * @return Badge|null
     */
    public function getBadge(): Badge
    {
        $app = Application::getFacadeApplication();
        /** @var AwardService $awardService */
        $awardService = $app->make(AwardService::class);
        try {
            return $awardService->getBadgeById($this->badgeId);
        } catch (BadgeNotFound $e) {
            return null;
        }
    }

    public function getValue()
    {
        return $this->getBadgeId();
    }

    public function getBadgeName() {
        $badge = $this->getBadge();

        if (is_object($badge)) {
            return $badge->getName();
        } else {
            return '';
        }
    }

    /**
     * @param int $badgeId
     * @return BadgeSelectorValue
     */
    public function setBadgeId(int $badgeId): BadgeSelectorValue
    {
        $this->badgeId = $badgeId;
        return $this;
    }

    public function __toString()
    {
        return $this->getBadgeName();
    }

}
