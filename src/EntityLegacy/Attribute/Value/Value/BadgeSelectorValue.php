<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\EntityLegacy\Attribute\Value\Value;

use Concrete\Core\Entity\Attribute\Value\Value\AbstractValue;
use Doctrine\ORM\Mapping as ORM;
use PortlandLabs\CommunityBadges\Entity\Badge;

/**
 * @ORM\Entity
 * @ORM\Table(name="BadgeSelectorValue")
 */
class BadgeSelectorValue extends AbstractValue implements \Stringable
{
    /**
     * @ORM\ManyToOne(targetEntity="\PortlandLabs\CommunityBadges\Entity\Badge")
     * @ORM\JoinColumn(name="badgeId", referencedColumnName="id", nullable=true)
     */
    protected ?Badge $badge;

    #[\Override]
    public function getValue(): ?Badge
    {
        return $this->badge;
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->badge?->name ?? '';
    }
}
