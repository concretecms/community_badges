<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Entity;

use Concrete\Core\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class UserBadge
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue('AUTO')]
    public int $id;

    public function __construct(
        #[ORM\ManyToOne(User::class)]
        #[ORM\JoinColumn(name: 'uID', referencedColumnName: 'uID', onDelete: 'SET NULL')]
        public User $user,

        #[ORM\ManyToOne(targetEntity: AwardGrant::class)]
        #[ORM\JoinColumn(name: 'awantGrantId', referencedColumnName: 'id', nullable: true)]
        public ?AwardGrant $awardGrant,

        #[ORM\ManyToOne(targetEntity: Badge::class)]
        #[ORM\JoinColumn(name: 'badgeId', referencedColumnName: 'id')]
        public Badge $badge,

        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $createdAt = new \DateTimeImmutable('now') {
            set(\DateTimeImmutable $date) => $this->createdAt = $date->setTimezone(new \DateTimeZone('UTC'));
        },
    ) {}

    /**
     * @return bool
     */
    public function isGivenBySystem(): bool
    {
        return !$this->awardGrant instanceof AwardGrant;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getAwardGrant(): ?AwardGrant
    {
        return $this->awardGrant;
    }

    public function getBadge(): Badge
    {
        return $this->badge;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
