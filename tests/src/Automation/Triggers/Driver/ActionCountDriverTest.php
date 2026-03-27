<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Test\Src\Automation\Triggers\Driver;

use PHPUnit\Framework\Attributes\CoversClass;
use PortlandLabs\CommunityBadges\Automation\Triggers\Driver\ActionCountDriver;
use PortlandLabs\CommunityBadges\Automation\Triggers\Saver\ActionCountSaver;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Entity\Point\ActionServiceInterface;
use PortlandLabs\CommunityBadges\Test\TestCase;
use ReflectionProperty;

#[\AllowDynamicProperties]
#[CoversClass(ActionCountDriver::class)]
class ActionCountDriverTest extends TestCase
{
    protected function setUp(): void
    {
        $this->awardService = self::createStub(AwardService::class);
        $this->actionService = self::createStub(ActionServiceInterface::class);
        $this->db = self::createStub(\Doctrine\DBAL\Connection::class);
    }

    public function testGetElement(): void
    {
        $driver = new ActionCountDriver($this->awardService, $this->actionService, $this->db);
        $element = $driver->getConfigurationFormElement();
        $property = new ReflectionProperty($element, 'element');
        self::assertSame('dashboard/automation/triggers/action_count', $property->getValue($element));
    }

    public function testGetSaver(): void
    {
        $driver = new ActionCountDriver($this->awardService, $this->actionService, $this->db);
        $driver->setApplication($this->appStub);

        $saver = self::createStub(ActionCountSaver::class);
        $this->appStub->method('get')->willReturnMap([[ActionCountSaver::class, $saver]]);
        self::assertSame($saver, $driver->getSaver());
    }

    public function testName(): void
    {
        $driver = new ActionCountDriver($this->awardService, $this->actionService, $this->db);
        self::assertSame('Action Count', $driver->getName());
    }

    public function testUserNeedsBadge(): void
    {

    }
}
