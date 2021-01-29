<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Automation\Triggers;

use Concrete\Core\Controller\ElementController;
use PortlandLabs\CommunityBadges\Entity\AutomationRule;

abstract class AutomationRuleElementController extends ElementController
{
    /** @var AutomationRule */
    protected $automationRule;

    /**
     * @return AutomationRule
     */
    public function getAutomationRule(): AutomationRule
    {
        return $this->automationRule;
    }

    /**
     * @param AutomationRule $automationRule
     * @return AutomationRuleElementController
     */
    public function setAutomationRule(AutomationRule $automationRule): AutomationRuleElementController
    {
        $this->automationRule = $automationRule;
        return $this;
    }

}