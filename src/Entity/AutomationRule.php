<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AutomationRule
{
    #[ORM\Id]
    #[ORM\Column('integer')]
    #[ORM\GeneratedValue('AUTO')]
    public int $id;

    public function __construct(
        #[ORM\Column(type: 'string')]
        public string $name,

        #[ORM\Column(type: 'string')]
        public $driverHandle = '',

        #[ORM\Column(type: 'array', nullable: true)]
        public $configuration = null,

        #[ORM\ManyToOne(targetEntity: Badge::class)]
        #[ORM\JoinColumn(name: 'badgeId', referencedColumnName: 'id')]
        public Badge $badge,
    ) {}
}
