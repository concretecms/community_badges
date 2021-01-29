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

class AccountAgeSaver extends AbstractSaver
{
    public function validateRequest(Request $request): ErrorList
    {
        $this->formValidator->setData($request->request->all());
        $this->formValidator->addRequired("accountAge", t("Please enter the account age."));
        $this->formValidator->test();
        return $this->formValidator->getError();
    }

    public function saveConfiguration(Request $request, AutomationRule $automationRule): void
    {
        $automationRule->setConfiguration([
            "accountAge" => $request->request->getInt("accountAge")
        ]);

        $this->entityManager->persist($automationRule);
        $this->entityManager->flush();
    }
}
