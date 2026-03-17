<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\User\Point\Action;

interface ActionDescriptionInterface
{
    public function getUserPointActionDescription(): string;
}
