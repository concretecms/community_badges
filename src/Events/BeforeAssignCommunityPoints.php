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

class BeforeAssignCommunityPoints extends GenericEvent
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
     * @return BeforeAssignCommunityPoints
     */
    public function setEntry(Entry $entry): BeforeAssignCommunityPoints
    {
        $this->entry = $entry;
        return $this;
    }
}
