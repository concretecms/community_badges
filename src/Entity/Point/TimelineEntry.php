<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Entity\Point;

use Concrete\Core\Entity\User\User;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @final
 */
#[ORM\Entity(repositoryClass: TimelineEntryRepository::class)]
#[ORM\Table(name: 'UserPointHistory', indexes: [
    new ORM\Index(columns: ['upuID'], name: 'upuID'),
    new ORM\Index(columns: ['upaID'], name: 'upaID'),
])]
class TimelineEntry
{
    /** @readonly */
    #[ORM\Id]
    #[ORM\Column(name: 'upID', type: 'integer')]
    #[ORM\GeneratedValue('AUTO')]
    public int $id;

    public function __construct(
        #[ORM\ManyToOne(User::class)]
        #[ORM\JoinColumn(name: 'upuID', referencedColumnName: 'uID', onDelete: 'CASCADE')]
        public User $user,

        #[ORM\ManyToOne(Action::class)]
        #[ORM\JoinColumn(name: 'upaID', referencedColumnName: 'upaID', onDelete: 'CASCADE')]
        public Action $action,

        #[ORM\Column(name: 'upPoints', type: 'integer')]
        public int $points,

        #[ORM\Column(name: 'object', type: 'text')]
        public string $extra,

        #[ORM\Column(name: 'timestamp', type: 'datetime_immutable')]
        public \DateTimeImmutable $date {
            get => $this->date;
            set(\DateTimeImmutable $value) => $this->date = $value->setTimezone(new \DateTimeZone('UTC'));
        },
    ) {}
}
