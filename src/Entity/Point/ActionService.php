<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Entity\Point;

use Doctrine\ORM\EntityManagerInterface;
use PortlandLabs\CommunityBadges\User\Point\Action\ActionControllerInterface;
use PortlandLabs\CommunityBadges\User\Point\Action\ActionDescriptionInterface;
use PortlandLabs\CommunityBadges\User\Point\Action\DefaultActionController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class ActionService implements ActionServiceInterface
{
    /**
     * @var array<class-string<ActionControllerInterface>, \WeakReference<ActionControllerInterface>>
     */
    private array $controllers = [];

    public TimelineEntryRepository $timelineRepository {
        /** @mago-ignore analysis:invalid-return-statement */
        /** @mago-ignore analysis:property-type-coercion */
        get => $this->timelineRepository ??= $this->em->getRepository(TimelineEntry::class);
    }

    public ActionRepository $actionRepository {
        /** @mago-ignore analysis:invalid-return-statement */
        /** @mago-ignore analysis:property-type-coercion */
        get => $this->actionRepository ??= $this->em->getRepository(Action::class);
    }

    public function __construct(
        public readonly ContainerInterface $app,
        public readonly EntityManagerInterface $em,
    ) {}

    public function getRepository(): ActionRepository
    {
        return $this->actionRepository;
    }

    public function getEntryRepository(): TimelineEntryRepository
    {
        return $this->timelineRepository;
    }

    public function add(Action|TimelineEntry $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function save(Action $action): void
    {
        /** @mago-ignore analysis:missing-magic-method */
        $this->em->refresh($action);
        $this->em->flush();
    }

    public function getController(Action $action): ActionControllerInterface
    {
        $controllerClass = $action->customControllerClass ?? DefaultActionController::class;
        $reference = $this->controllers[$controllerClass] ?? null;
        $existingController = $reference?->get();
        if ($existingController) {
            return $existingController;
        }

        try {
            /** @var ActionControllerInterface $controller */
            $controller = $this->app->get($controllerClass);
        } catch (ContainerExceptionInterface) {
            $controller = new DefaultActionController();
        }

        $this->controllers[$controllerClass] = \WeakReference::create($controller);
        return $controller;
    }

    public function getEntryDescription(TimelineEntry $entry): string
    {
        return $this->getController($entry->action)->getActionDescription($entry);
    }
}
