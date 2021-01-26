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
class UserBadge
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\Concrete\Core\Entity\User\User")
     * @ORM\JoinColumn(name="uID", referencedColumnName="uID", onDelete="SET NULL")
     */
    protected $user;

    /**
     * @var AwardGrant
     *
     * @ORM\ManyToOne(targetEntity="\PortlandLabs\CommunityBadges\Entity\AwardGrant")
     * @ORM\JoinColumn(name="awantGrantId", referencedColumnName="id", nullable=true)
     */
    protected $awardGrant;

    /**
     * @var Badge
     *
     * @ORM\ManyToOne(targetEntity="\PortlandLabs\CommunityBadges\Entity\Badge")
     * @ORM\JoinColumn(name="badgeId", referencedColumnName="id")
     */
    protected $badge;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return UserBadge
     */
    public function setId(int $id): UserBadge
    {
        $this->id = $id;
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
     * @return UserBadge
     */
    public function setUser(User $user): UserBadge
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return AwardGrant|null
     */
    public function getAwardGrant(): ?AwardGrant
    {
        return $this->awardGrant;
    }

    /**
     * @param AwardGrant|null $awardGrant
     * @return UserBadge
     */
    public function setAwardGrant(?AwardGrant $awardGrant): UserBadge
    {
        $this->awardGrant = $awardGrant;
        return $this;
    }

    /**
     * @return Badge
     */
    public function getBadge(): Badge
    {
        return $this->badge;
    }

    /**
     * @param Badge $badge
     * @return UserBadge
     */
    public function setBadge(Badge $badge): UserBadge
    {
        $this->badge = $badge;
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
     * @return UserBadge
     */
    public function setCreatedAt(DateTime $createdAt): UserBadge
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function isGivenBySystem(): bool
    {
        return !$this->getAwardGrant() instanceof AwardGrant;
    }

}