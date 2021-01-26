<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Entity;

use Concrete\Core\Entity\File\File;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 */
class Badge
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
    protected $name = "";

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $description = "";

    /**
     * @var File
     *
     * @ORM\ManyToOne(targetEntity="\Concrete\Core\Entity\File\File")
     * @ORM\JoinColumn(name="fID", referencedColumnName="fID", onDelete="SET NULL", nullable=true)
     */
    protected $thumbnail;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Badge
     */
    public function setId(int $id): Badge
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
     * @return Badge
     */
    public function setName(string $name): Badge
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Badge
     */
    public function setDescription(string $description): Badge
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getThumbnail(): ?File
    {
        return $this->thumbnail;
    }

    /**
     * @param File|null $thumbnail
     * @return Badge
     */
    public function setThumbnail(?File $thumbnail): Badge
    {
        $this->thumbnail = $thumbnail;
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
     * @return Badge
     */
    public function setCreatedAt(DateTime $createdAt): Badge
    {
        $this->createdAt = $createdAt;
        return $this;
    }

}