<?php

/**
 * @project:   Community Awards
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityAwards\Entity;

use Concrete\Core\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity
 */
class AwardedAward
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
     * @ORM\ManyToOne(targetEntity="\PortlandLabs\CommunityAwards\Entity\AwardGrant")
     * @ORM\JoinColumn(name="awantGrantId", referencedColumnName="id", nullable=true)
     */
    protected $awardGrant;

    /**
     * @var Award
     *
     * @ORM\ManyToOne(targetEntity="\PortlandLabs\CommunityAwards\Entity\Award")
     * @ORM\JoinColumn(name="awardId", referencedColumnName="id")
     */
    protected $award;

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
     * @return AwardedAward
     */
    public function setId(int $id): AwardedAward
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
     * @return AwardedAward
     */
    public function setUser(User $user): AwardedAward
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
     * @return AwardedAward
     */
    public function setAwardGrant(?AwardGrant $awardGrant): AwardedAward
    {
        $this->awardGrant = $awardGrant;
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
     * @return AwardedAward
     */
    public function setAward(Award $award): AwardedAward
    {
        $this->award = $award;
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
     * @return AwardedAward
     */
    public function setCreatedAt(DateTime $createdAt): AwardedAward
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