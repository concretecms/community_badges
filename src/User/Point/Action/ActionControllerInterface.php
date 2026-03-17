<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\User\Point\Action;

use PortlandLabs\CommunityBadges\Entity\Point\TimelineEntry;

/**
 * Gives devs access to custom logic for user point actions / timeline entries
 *
 * @template TDescription of ActionDescriptionInterface
 */
interface ActionControllerInterface
{
    public function getActionPoints(TimelineEntry $entry): int;

    /**
     * @return ?TDescription
     */
    public function getActionDescriptionObject(TimelineEntry $entry): ?ActionDescriptionInterface;
    public function getActionDescription(TimelineEntry $entry): string;

    /**
     * @param TDescription $description
     */
    public function serializeActionDescriptionObject(ActionDescriptionInterface $description): string;
}
