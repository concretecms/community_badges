<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Events;

use PortlandLabs\CommunityBadges\Entity\Point\TimelineEntry;
use Symfony\Contracts\EventDispatcher\Event;

final class AfterAssignCommunityPoints extends Event
{
    public function __construct(
        public readonly TimelineEntry $entry
    ) {}

    public function getEntry(): TimelineEntry
    {
        return $this->entry;
    }
}
