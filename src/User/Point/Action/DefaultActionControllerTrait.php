<?php

namespace PortlandLabs\CommunityBadges\User\Point\Action;

use PortlandLabs\CommunityBadges\Entity\Point\TimelineEntry;

/**
 * @template TDescription of ActionDescriptionInterface
 */
trait DefaultActionControllerTrait
{
    /** @return class-string<TDescription> */
    abstract protected function allowedActionDescriptionClass(): string;

    public function getActionPoints(TimelineEntry $entry): int
    {
        return $entry->action->defaultPoints;
    }

    public function getActionDescription(TimelineEntry $entry): string
    {
        return $this->getActionDescriptionObject($entry)?->getUserPointActionDescription() ?? '';
    }

    /**
     * @return ?TDescription
     */
    public function getActionDescriptionObject(TimelineEntry $entry): ?ActionDescriptionInterface
    {
        /** @var TDescription */
        return unserialize($entry->extra, ['allowed_classes' => [
            $this->allowedActionDescriptionClass(),
            \DateTimeImmutable::class,
            \DateTime::class,
        ]]);
    }

    /**
     * @param TDescription $description
     */
    public function serializeActionDescriptionObject(ActionDescriptionInterface $description): string
    {
        return serialize($description);
    }
}
