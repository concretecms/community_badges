<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityBadges\Controller\Element\Dashboard\Automation\Triggers;

use PortlandLabs\CommunityBadges\Automation\Triggers\AutomationRuleElementController;

class LeaveGroup extends AutomationRuleElementController
{
    /**
     * The handle of the package defining this element.
     *
     * @var string|null
     */
    protected $pkgHandle = 'community_badges';

    public function getElement()
    {
        return 'dashboard/automation/triggers/leave_group';
    }

    public function view()
    {
        $configuration = $this->getAutomationRule()->getConfiguration();
        if (is_array($configuration) && isset($configuration["groupId"])) {
            $this->set("groupId", $configuration["groupId"]);
        }
    }
}