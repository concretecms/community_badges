<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AutomationRuleProcessedItem
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    public int $id;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: AutomationRule::class)]
        #[ORM\JoinColumn(name: 'automationRuleId', referencedColumnName: 'id')]
        public AutomationRule $automationRule,

        #[ORM\Column(name: '`key`', type: 'string')]
        public string $key = '',
    ) {}
}
