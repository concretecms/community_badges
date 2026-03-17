<?php

declare(strict_types=1);

namespace Concrete\Package\CommunityBadges\Controller\Element\Dashboard\Automation\Triggers;

use PortlandLabs\CommunityBadges\Automation\Triggers\AutomationRuleElementController;
use PortlandLabs\CommunityBadges\Automation\Triggers\Driver\Manager;
use PortlandLabs\CommunityBadges\Entity\Point\ActionService;
use PortlandLabs\CommunityBadges\User\Point\Action\Action;

class ActionCount extends AutomationRuleElementController
{
    protected $pkgHandle = 'community_badges';
    /** @var Manager */
    protected $manager;

    public function __construct(
        Manager $manager,
        private ActionService $actionService,
    ) {
        parent::__construct();
        $this->manager = $manager;
    }

    public function getElement(): string
    {
        return 'dashboard/automation/triggers/action_count';
    }

    public function view(): void
    {
        $configuration = $this->getAutomationRule()?->getConfiguration();

        $actions = [];

        foreach ($this->actionService->getRepository()->findAll() as $action) {
            $actions[$action->id] = $action->name;
        }

        asort($actions);
        $this->set('actions', $actions);
        $this->set('action', $configuration['action'] ?? null);
        $this->set('minCount', $configuration['minCount'] ?? 1);
    }
}
