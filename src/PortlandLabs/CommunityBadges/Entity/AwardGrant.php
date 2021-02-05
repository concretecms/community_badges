<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Entity;

use Concrete\Core\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity
 */
class AwardGrant
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
     * @var Award
     *
     * @ORM\ManyToOne(targetEntity="\PortlandLabs\CommunityBadges\Entity\Award")
     * @ORM\JoinColumn(name="awardId", referencedColumnName="id")
     */
    protected $award;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\Concrete\Core\Entity\User\User")
     * @ORM\JoinColumn(name="uID", referencedColumnName="uID", onDelete="SET NULL")
     */
    protected $user;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $dismissed = false;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt = null;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $redeemedAt = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return AwardGrant
     */
    public function setId(int $id): AwardGrant
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Award
     */
    public function getAward(): Award
    {
        return $this->award;
    }

    /**
     * @param Award $award
     * @return AwardGrant
     */
    public function setAward(Award $award): AwardGrant
    {
        $this->award = $award;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return AwardGrant
     */
    public function setUser(User $user): AwardGrant
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     * @return AwardGrant
     */
    public function setCreatedAt(DateTime $createdAt): AwardGrant
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getRedeemedAt(): DateTime
    {
        return $this->redeemedAt;
    }

    /**
     * @param DateTime $redeemedAt
     * @return AwardGrant
     */
    public function setRedeemedAt(DateTime $redeemedAt): AwardGrant
    {
        $this->redeemedAt = $redeemedAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDismissed(): bool
    {
        return $this->dismissed;
    }

    /**
     * @param bool $dismissed
     * @return AwardGrant
     */
    public function setDismissed(bool $dismissed): AwardGrant
    {
        $this->dismissed = $dismissed;
        return $this;
    }

}