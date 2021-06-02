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
class AutomationRule
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $driverHandle = '';

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $configuration = [];

    /**
     * @var Badge
     *
     * @ORM\ManyToOne(targetEntity="\PortlandLabs\CommunityBadges\Entity\Badge")
     * @ORM\JoinColumn(name="badgeId", referencedColumnName="id")
     */
    protected $badge;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return AutomationRule
     */
    public function setId(int $id): AutomationRule
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return AutomationRule
     */
    public function setName(string $name): AutomationRule
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDriverHandle(): string
    {
        return $this->driverHandle;
    }

    /**
     * @param string $driverHandle
     * @return AutomationRule
     */
    public function setDriverHandle(string $driverHandle): AutomationRule
    {
        $this->driverHandle = $driverHandle;
        return $this;
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * @param array $configuration
     * @return AutomationRule
     */
    public function setConfiguration(array $configuration): AutomationRule
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * @return Badge
     */
    public function getBadge(): ?Badge
    {
        return $this->badge;
    }

    /**
     * @param Badge $badge
     * @return AutomationRule
     */
    public function setBadge(?Badge $badge): AutomationRule
    {
        $this->badge = $badge;
        return $this;
    }

}