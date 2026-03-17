<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Entity\Point;

use Doctrine\ORM\EntityManagerInterface;
use PortlandLabs\CommunityBadges\User\Point\Action\ActionControllerInterface;
use PortlandLabs\CommunityBadges\User\Point\Action\ActionDescriptionInterface;
use PortlandLabs\CommunityBadges\User\Point\Action\DefaultActionController;
use Psr\Container\ContainerInterface;

final class ActionService
{
    /**
     * @var array<class-string<ActionControllerInterface>, \WeakReference<ActionControllerInterface>>
     */
    private array $controllers = [];

    public function __construct(
        public readonly ContainerInterface $app,
        public readonly EntityManagerInterface $em,
    ) {}

    public function getRepository(): ActionRepository
    {
        $repository = $this->em->getRepository(Action::class);
        assert($repository instanceof ActionRepository);
        return $repository;
    }

    public function getEntryRepository(): TimelineEntryRepository
    {
        $repository = $this->em->getRepository(TimelineEntry::class);
        assert($repository instanceof TimelineEntryRepository);
        return $repository;
    }

    public function add(Action $action): void
    {
        $this->em->persist($action);
        $this->em->flush();
    }

    public function save(Action $action): void
    {
        $this->em->refresh($action);
        $this->em->flush();
    }

    public function addEntry(TimelineEntry $entry): void
    {
        $this->em->persist($entry);
        $this->em->flush();
        $this->em->detach($entry);
    }

    public function getController(Action $action): ActionControllerInterface
    {
        $controllerClass = $action->customControllerClass ?? DefaultActionController::class;
        $existingController = $this->controllers[$controllerClass]?->get();
        if ($existingController) {
            return $existingController;
        }

        /** @var ActionControllerInterface $controller */
        $controller = $this->app->get($controllerClass);
        $this->controllers[$controllerClass] = \WeakReference::create($controller);
        return $controller;
    }

    public function getEntryDescription(TimelineEntry $entry): string
    {
        return $this->getController($entry->action)->getActionDescription($entry);
    }
}
