<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\User\Point\Action;

class ActionDescription implements ActionDescriptionInterface
{
    public function __construct(
        public string $comments,
    ) {}

    public function getUserPointActionDescription(): string
    {
        return $this->comments;
    }
}
