<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Entity\Point;

use Doctrine\ORM\Mapping as ORM;
use Illuminate\Support\Str;
use PortlandLabs\BackboneQueue\Activity\ActivityType;use PortlandLabs\CommunityBadges\User\Point\Action\ActionControllerInterface;
use PortlandLabs\MwrCommunity\Profile\Experience\ActionController;

/**
 * @final
 */
#[ORM\Entity(repositoryClass: ActionRepository::class)]
#[ORM\Table(name: 'UserPointActions', indexes: [
    new ORM\Index(name: 'pkgID', columns: ['pkgID']),
])]
class Action
{
    /** @readonly */
    #[ORM\Id]
    #[ORM\Column(name: 'upaID', type: 'integer')]
    #[ORM\GeneratedValue('AUTO')]
    public int $id;

    public function __construct(
        #[ORM\Column(name: 'upaHandle', type: 'string', length: 255, unique: true)]
        public string $handle,
        #[ORM\Column(name: 'upaName', type: 'string', length: 255)]
        public string $name,
        #[ORM\Column(name: 'upaDefaultPoints', type: 'integer')]
        public int $defaultPoints,
        #[ORM\Column(name: 'upaHasCustomClass', type: 'boolean')]
        public bool $hasCustomClass,
        #[ORM\Column(name: 'upaIsActive', type: 'boolean')]
        public bool $isActive,
        #[ORM\Column(name: 'pkgID', type: 'integer')]
        public int $packageId,

        /** @var class-string<ActionControllerInterface>|null $customControllerClass */
        #[ORM\Column(name: 'upaCustomControllerClass', type: 'text', nullable: true)]
        public ?string $customControllerClass,
    ) {}
}
