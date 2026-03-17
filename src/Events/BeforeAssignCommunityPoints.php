<?php

declare(strict_types=1);

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Events;

use PortlandLabs\CommunityBadges\Entity\Point\TimelineEntry;
use Symfony\Contracts\EventDispatcher\Event;

class BeforeAssignCommunityPoints extends Event
{
    public function __construct(
        public readonly TimelineEntry $entry,
    ) {}

    public function getEntry(): TimelineEntry
    {
        return $this->entry;
    }
}
