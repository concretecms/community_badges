<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Test\Src\Entity\Point;

use AllowDynamicProperties;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Container\Container;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\Constraint\IsIdentical;
use PortlandLabs\CommunityBadges\Entity\Point\Action;
use PortlandLabs\CommunityBadges\Entity\Point\ActionRepository;
use PortlandLabs\CommunityBadges\Entity\Point\ActionService;
use PortlandLabs\CommunityBadges\Entity\Point\TimelineEntry;
use PortlandLabs\CommunityBadges\Entity\Point\TimelineEntryRepository;
use PortlandLabs\CommunityBadges\Test\TestCase;
use PortlandLabs\CommunityBadges\User\Point\Action\ActionControllerInterface;
use PortlandLabs\CommunityBadges\User\Point\Action\DefaultActionController;
use Psr\Container\ContainerInterface;

#[CoversClass(ActionService::class)]
#[AllowDynamicProperties]
class ActionServiceTest extends TestCase
{
    protected function setUp(): void
    {
        $this->repo = self::createStub(ActionRepository::class);
        $this->entryRepo = self::createStub(TimelineEntryRepository::class);

        $this->container = self::createStub(ContainerInterface::class);
        $this->em = self::createStub(EntityManagerInterface::class);
        $this->em
            ->method('getRepository')
            ->willReturnMap([
                [Action::class, $this->repo],
                [TimelineEntry::class, $this->entryRepo],
            ]);
    }

    public function testRepoResolution(): void
    {
        $service = new ActionService($this->container, $this->em);
        self::assertSame($this->repo, $service->getRepository());
    }

    public function testEntryRepoResolution(): void
    {
        $service = new ActionService($this->container, $this->em);
        self::assertSame($this->entryRepo, $service->getEntryRepository());
    }

    #[DataProvider('actionEntryDataProvider')]
    public function testAdd(Action|TimelineEntry $entity): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('persist')->with(new IsIdentical($entity));
        $em->expects($this->once())->method('flush');
        new ActionService($this->container, $em)->add($entity);
    }

    public function testSave(): void
    {
        $action = self::createAction();
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('refresh')->with(new IsIdentical($action));
        $em->expects($this->once())->method('flush');
        new ActionService($this->container, $em)->save($action);
    }

    public function testAddEntry(): void
    {
        $action = self::createAction();
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('refresh')->with(new IsIdentical($action));
        $em->expects($this->once())->method('flush');
        new ActionService($this->container, $em)->save($action);
    }

    #[TestWith(['', DefaultActionController::class])]
    #[TestWith([null, DefaultActionController::class])]
    #[TestWith(['foo', 'foo'])]
    public function testGetController(?string $controller, string $bindTo): void
    {
        $action = self::createAction(customControllerClass: $controller);
        $controller = self::createStub(ActionControllerInterface::class);
        $container = new Container();
        $container->instance($bindTo, $controller);

        $service = new ActionService($container, $this->em);
        self::assertSame($controller, $service->getController($action));
    }

    public function testWeaklyCachesControllers(): void
    {
        $action = self::createAction(customControllerClass: 'foo');
        $this->container->method('get')->willReturnCallback(fn() => self::createStub(ActionControllerInterface::class));

        $service = new ActionService($this->container, $this->em);
        $first = $service->getController($action);
        $second = $service->getController($action);

        self::assertSame($second, $first);

        $hash = spl_object_hash($first);
        unset($first, $second);
        gc_collect_cycles();

        $third = $service->getController($action);
        self::assertNotSame($hash, spl_object_hash($third));
    }

    public function testLoadsDescription(): void
    {
        $description = uuid_create();
        $action = self::createAction(customControllerClass: 'foo');
        $entry = self::createEntry(action: $action);

        $controller = self::createStub(ActionControllerInterface::class);
        $controller->method('getActionDescription')->willReturn($description);
        $this->container->method('get')->willReturnMap([['foo', $controller]]);

        $service = new ActionService($this->container, $this->em);
        self::assertEquals($description, $service->getEntryDescription($entry));
    }
}
