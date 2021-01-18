<?php

/**
 * @project:   Community Awards
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityAwards\Events;

use PortlandLabs\CommunityAwards\Entity\AwardGrant;
use Symfony\Component\EventDispatcher\GenericEvent;

class AfterGrantAward extends GenericEvent
{

    /** @var AwardGrant */
    protected $grantedAward;

    /**
     * @return AwardGrant
     */
    public function getGrantedAward(): AwardGrant
    {
        return $this->grantedAward;
    }

    /**
     * @param AwardGrant $grantedAward
     * @return AfterGrantAward
     */
    public function setGrantedAward(AwardGrant $grantedAward): AfterGrantAward
    {
        $this->grantedAward = $grantedAward;
        return $this;
    }

}
