<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\User\Point\Action;

/**
 * @implements ActionControllerInterface<ActionDescription>
 */
final class DefaultActionController implements ActionControllerInterface
{
    /**
     * @use DefaultActionControllerTrait<ActionDescription>
     */
    use DefaultActionControllerTrait;

    protected function allowedActionDescriptionClass(): string
    {
        return ActionDescription::class;
    }
}
