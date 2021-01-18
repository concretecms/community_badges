<?php

/**
 * @project:   Community Awards
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityAwards\Events;

use PortlandLabs\CommunityAwards\Entity\AwardedAward;
use Symfony\Component\EventDispatcher\GenericEvent;

class BeforeGiveAward extends GenericEvent
{
    /** @var AwardedAward */
    protected $awardedAward;

    /**
     * @return AwardedAward
     */
    public function getAwardedAward(): AwardedAward
    {
        return $this->awardedAward;
    }

    /**
     * @param AwardedAward $awardedAward
     * @return BeforeGiveAward
     */
    public function setAwardedAward(AwardedAward $awardedAward): BeforeGiveAward
    {
        $this->awardedAward = $awardedAward;
        return $this;
    }



}
