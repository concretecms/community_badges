<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Entity;

use Concrete\Core\Entity\File\File;
use Concrete\Core\Entity\File\Version;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
class Badge implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue('AUTO')]
    public int $id;

    public function __construct(
        #[ORM\Column(type: 'string')]
        public string $name,

        #[ORM\Column(type: 'string')]
        public string $handle,

        #[ORM\Column(type: 'string')]
        public string $description,

        #[ORM\ManyToOne(targetEntity: File::class)]
        #[ORM\JoinColumn(name: 'fID', referencedColumnName: 'fID', nullable: true, onDelete: 'SET NULL')]
        public ?File $thumbnail,

        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $createdAt = new \DateTimeImmutable('now') {
            set(\DateTimeImmutable $date) => $this->createdAt = $date->setTimezone(new \DateTimeZone('UTC'));
        },
    ) {}

    public function jsonSerialize(): array
    {
        $imageDataBase64 = '';
        $imageFileName = '';

        $image = $this->thumbnail;
        $imageApprovedVersion = $image?->getApprovedVersion();

        if ($imageApprovedVersion instanceof Version) {
            $imageFileName = $imageApprovedVersion?->getFileName();
            $contents = (string) $imageApprovedVersion->getFileContents();
            $imageDataBase64 =
                'data:' . (string) $imageApprovedVersion?->getMimeType() . ';base64,' . base64_encode($contents);
        }

        return [
            'name' => $this->name,
            'description' => $this->description,
            'image' => [
                'name' => $imageFileName,
                'data' => $imageDataBase64,
            ],
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHandle(): string
    {
        return $this->handle;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getThumbnail(): ?File
    {
        return $this->thumbnail;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
