<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Entity;

use Concrete\Core\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AwardGrant
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue('AUTO')]
    public int $id;

    public function __construct(
        #[ORM\ManyToOne(Award::class)]
        #[ORM\JoinColumn(name: 'awardId', referencedColumnName: 'id')]
        public Award $award,

        #[ORM\ManyToOne(User::class)]
        #[ORM\JoinColumn(name: 'uID', referencedColumnName: 'uID', onDelete: 'SET NULL')]
        public User $user,

        #[ORM\Column(type: 'boolean')]
        public bool $dismissed = false,

        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $createdAt = new \DateTimeImmutable('now') {
            set(\DateTimeImmutable $date) => $this->createdAt = $date->setTimezone(new \DateTimeZone('UTC'));
        },

        #[ORM\Column(type: 'datetime_immutable', nullable: true)]
        public ?\DateTimeImmutable $redeemedAt = null {
            set(?\DateTimeImmutable $date) => $this->redeemedAt = $date?->setTimezone(new \DateTimeZone('UTC'));
        },
    ) {}
}
