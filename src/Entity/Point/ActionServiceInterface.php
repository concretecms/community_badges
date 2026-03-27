<?php

namespace PortlandLabs\CommunityBadges\Entity\Point;

use PortlandLabs\CommunityBadges\User\Point\Action\ActionControllerInterface;

interface ActionServiceInterface
{
    public function getRepository(): ActionRepository;

    public function getEntryRepository(): TimelineEntryRepository;

    public function add(Action|TimelineEntry $entity): void;

    public function save(Action $action): void;

    public function getController(Action $action): ActionControllerInterface;

    public function getEntryDescription(TimelineEntry $entry): string;
}
