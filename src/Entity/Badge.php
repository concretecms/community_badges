<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Entity;

use Concrete\Core\Entity\File\File;
use Concrete\Core\Entity\File\Version;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
class Badge implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column('integer')]
    #[ORM\GeneratedValue('AUTO')]
    public int $id;

    public function __construct(
        #[ORM\Column('string')]
        public string $name,

        #[ORM\Column('string')]
        public string $handle,

        #[ORM\Column('string')]
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
            $imageDataBase64 =
                'data:'
                . $imageApprovedVersion?->getMimeType()
                . ';base64,'
                . base64_encode($imageApprovedVersion->getFileContents());
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
}
