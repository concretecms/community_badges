<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Automation\Triggers\Saver;

use Concrete\Core\Error\ErrorList\ErrorList;
use PortlandLabs\CommunityBadges\Entity\AutomationRule;
use Symfony\Component\HttpFoundation\Request;

class ActionCountSaver extends AbstractSaver
{
    public function validateRequest(Request $request): ErrorList
    {
        $this->formValidator->setData($request->request->all());
        $this->formValidator->addRequired('action', t('Please enter the action.'));
        $this->formValidator->addRequired('minCount', t('Please enter the minimum count.'));
        $this->formValidator->test();
        /** @var ErrorList */
        return $this->formValidator->getError();
    }

    public function saveConfiguration(Request $request, AutomationRule $automationRule): void
    {
        $automationRule->configuration = [
            'action' => $request->request->getInt('action'),
            'minCount' => $request->request->getInt('minCount'),
        ];

        $this->entityManager->persist($automationRule);
        $this->entityManager->flush();
    }
}
