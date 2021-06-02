<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Automation\Triggers\Saver;

use Concrete\Core\Error\ErrorList\ErrorList;
use PortlandLabs\CommunityBadges\Entity\AutomationRule;
use Symfony\Component\HttpFoundation\Request;

interface SaverInterface
{
    public function validateRequest(
        Request $request
    ): ErrorList;

    public function saveConfiguration(
        Request $request,
        AutomationRule $automationRule
    ): void;
}