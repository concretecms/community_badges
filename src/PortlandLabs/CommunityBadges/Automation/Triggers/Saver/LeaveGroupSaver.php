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

class LeaveGroupSaver extends AbstractSaver
{
    public function validateRequest(Request $request): ErrorList
    {
        $this->formValidator->setData($request->request->all());
        $this->formValidator->addRequired("groupId", t("Please select a valid group."));
        $this->formValidator->test();
        return $this->formValidator->getError();
    }

    public function saveConfiguration(Request $request, AutomationRule $automationRule): void
    {
        $automationRule->setConfiguration([
            "groupId" => $request->request->getInt("groupId")
        ]);

        $this->entityManager->persist($automationRule);
        $this->entityManager->flush();
    }
}
