<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityBadges\Attribute\BadgeSelector;

use Concrete\Core\Support\Facade\Application;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Entity\Attribute\Key\Settings\BadgeSelectorSettings;
use PortlandLabs\CommunityBadges\Entity\Attribute\Value\Value\BadgeSelectorValue;

use Concrete\Core\Attribute\DefaultController;
use Concrete\Core\Attribute\FontAwesomeIconFormatter;
use PortlandLabs\CommunityBadges\Entity\Badge;
use PortlandLabs\CommunityBadges\Exceptions\BadgeNotFound;

class Controller extends DefaultController
{
    protected $searchIndexFieldDefinition = [
        'type' => 'integer',
        'options' => ['default' => 0, 'notnull' => false],
    ];

    public function getIconFormatter()
    {
        return new FontAwesomeIconFormatter('link');
    }

    public function getAttributeValueClass()
    {
        return BadgeSelectorValue::class;
    }

    public function form()
    {
        $value = null;

        if (is_object($this->attributeValue)) {
            $value = $this->getAttributeValue()->getValue();
        }

        if (!$value) {
            if ($this->request->query->has($this->attributeKey->getAttributeKeyHandle())) {
                $value = $this->createAttributeValue((int)$this->request->query->get($this->attributeKey->getAttributeKeyHandle()));
            }
        }

        $this->set('value', $value);
    }

    public function getDisplayValue()
    {
        return $this->getPlainTextValue();
    }

    public function getPlainTextValue()
    {
        $badgeId = $this->getAttributeValue()->getValue();

        $app = Application::getFacadeApplication();
        /** @var AwardService $awardService */
        $awardService = $app->make(AwardService::class);
        try {
            $badge = $awardService->getBadgeById($badgeId);

            return $badge->getName();
        } catch (BadgeNotFound $e) {
            return null;
        }
    }

    /**
     * @param int|Badge $value
     * @return BadgeSelectorValue
     */
    public function createAttributeValue($value)
    {
        $attributeValue = new BadgeSelectorValue();

        $badgeId = null;

        if ($value instanceof Badge) {
            $badgeId = $value->getId();
        } else if (is_numeric($value)) {
            $badgeId = $value;
        }

        $attributeValue->setBadgeId($badgeId);

        return $attributeValue;
    }

    public function createAttributeValueFromRequest()
    {
        $data = $this->post();

        if (isset($data['value'])) {
            return $this->createAttributeValue((int)$data['value']);
        }
    }
}
