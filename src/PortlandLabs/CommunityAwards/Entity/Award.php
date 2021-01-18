<?php

/**
 * @project:   Community Awards
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityAwards\Entity;

use Concrete\Core\Entity\File\File;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity
 */
class Award
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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $groupId;

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
     * @return Award
     */
    public function setId(int $id): Award
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
     * @return Award
     */
    public function setName(string $name): Award
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
     * @return Award
     */
    public function setDescription(string $description): Award
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
     * @return Award
     */
    public function setThumbnail(?File $thumbnail): Award
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getGroupId(): ?int
    {
        return $this->groupId;
    }

    /**
     * @param int|null $groupId
     * @return Award
     */
    public function setGroupId(?int $groupId): Award
    {
        $this->groupId = $groupId;
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
     * @return Award
     */
    public function setCreatedAt(DateTime $createdAt): Award
    {
        $this->createdAt = $createdAt;
        return $this;
    }

}