<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class AutomationRuleProcessedItem
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id = null;

    /**
     * @var AutomationRule
     *
     * @ORM\ManyToOne(targetEntity="\PortlandLabs\CommunityBadges\Entity\AutomationRule")
     * @ORM\JoinColumn(name="automationRuleId", referencedColumnName="id")
     */
    protected $automationRule;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="`key`")
     */
    protected $key = '';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return AutomationRuleProcessedItem
     */
    public function setId(int $id): AutomationRuleProcessedItem
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return AutomationRule
     */
    public function getAutomationRule(): AutomationRule
    {
        return $this->automationRule;
    }

    /**
     * @param AutomationRule $automationRule
     * @return AutomationRuleProcessedItem
     */
    public function setAutomationRule(AutomationRule $automationRule): AutomationRuleProcessedItem
    {
        $this->automationRule = $automationRule;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return AutomationRuleProcessedItem
     */
    public function setKey(string $key): AutomationRuleProcessedItem
    {
        $this->key = $key;
        return $this;
    }
}