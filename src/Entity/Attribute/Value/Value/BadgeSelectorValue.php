<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Entity\Attribute\Value\Value;

use Concrete\Core\Entity\Attribute\Value\Value\AbstractValue;
use Doctrine\ORM\Mapping as ORM;
use PortlandLabs\CommunityBadges\Entity\Badge;

#[ORM\Entity]
#[ORM\Table(name: 'BadgeSelectorValue')]
class BadgeSelectorValue extends AbstractValue implements \Stringable
{
    #[ORM\ManyToOne(targetEntity: Badge::class)]
    #[ORM\JoinColumn(name: 'badgeId', referencedColumnName: 'id', nullable: true)]
    protected ?Badge $badge;

    #[\Override]
    public function getValue(): ?int
    {
        return $this->badge?->id;
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->badge?->name ?? '';
    }
}
