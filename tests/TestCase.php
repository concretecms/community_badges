<?php

namespace PortlandLabs\CommunityBadges\Test;

use Concrete\Core\Application\Application;
use Concrete\Core\Entity\User\User;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PortlandLabs\CommunityBadges\Entity\Point\Action;
use PortlandLabs\CommunityBadges\Entity\Point\TimelineEntry;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected Application&Stub $appStub;
    protected Application&MockObject $appMock;

    protected bool $useStubForApp = true;

    protected static function createAction(
        string $handle = '',
        string $name = '',
        int $defaultPoints = 0,
        bool $hasCustomClass = false,
        bool $isActive = true,
        int $packageId = 0,
        ?string $customControllerClass = null,
    ): Action {
        return new Action(
            handle: $handle,
            name: $name,
            defaultPoints: $defaultPoints,
            hasCustomClass: $hasCustomClass,
            isActive: $isActive,
            packageId: $packageId,
            customControllerClass: $customControllerClass,
        );
    }

    protected static function createEntry(
        ?User $user = null,
        ?Action $action = null,
        int $points = 0,
        string $extra = '',
        \DateTimeInterface $date = new \DateTimeImmutable(),
    ): TimelineEntry {
        $action ??= self::createAction();
        $user ??= self::createStub(User::class);
        return new TimelineEntry(user: $user, action: $action, points: $points, extra: $extra, date: $date);
    }

    public static function actionEntryDataProvider(): iterable
    {
        yield [self::createAction()];
        yield [self::createEntry()];
    }

    #[Before]
    public function setupApp(): void
    {
        $app = $this->useStubForApp ? self::createStub(Application::class) : $this->createMock(Application::class);
        \Concrete\Core\Support\Facade\Application::setFacadeApplication($app);
        $this->{$this->useStubForApp ? 'appStub' : 'appMock'} = $app;
    }
}
