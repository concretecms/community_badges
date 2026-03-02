<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Events;

use PortlandLabs\CommunityBadges\User\Point\Entry;
use Symfony\Component\EventDispatcher\GenericEvent;

class AfterAssignCommunityPoints extends GenericEvent
{
    /** @var Entry */
    protected $entry;

    /**
     * @return Entry
     */
    public function getEntry(): Entry
    {
        return $this->entry;
    }

    /**
     * @param Entry $entry
     * @return AfterAssignCommunityPoints
     */
    public function setEntry(Entry $entry): AfterAssignCommunityPoints
    {
        $this->entry = $entry;
        return $this;
    }
}
