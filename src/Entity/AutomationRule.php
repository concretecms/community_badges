<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AutomationRule
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue('AUTO')]
    public int $id;

    public function __construct(
        #[ORM\Column(type: 'string')]
        public string $name,

        #[ORM\Column(type: 'string')]
        public string $driverHandle = '',

        #[ORM\ManyToOne(targetEntity: Badge::class)]
        #[ORM\JoinColumn(name: 'badgeId', referencedColumnName: 'id')]
        public Badge $badge,

        #[ORM\Column(type: 'array', nullable: true)]
        public ?array $configuration,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDriverHandle(): string
    {
        return $this->driverHandle;
    }

    public function setDriverHandle(string $driverHandle): void
    {
        $this->driverHandle = $driverHandle;
    }

    public function getConfiguration(): ?array
    {
        return $this->configuration;
    }

    public function setConfiguration(?array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function getBadge(): Badge
    {
        return $this->badge;
    }

    public function setBadge(Badge $badge): void
    {
        $this->badge = $badge;
    }
}
