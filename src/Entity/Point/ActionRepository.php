<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Entity\Point;

use Concrete\Core\Entity\Package as PackageEntity;
use Concrete\Core\Package\Package;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Action>
 */
class ActionRepository extends EntityRepository
{
    public function findOneByHandle(string $handle): ?Action
    {
        return $this->findOneBy(['handle' => $handle]);
    }

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, string>|null $orderBy
     * @return Action[]
     */
    public function findByPackage(
        PackageEntity|Package|int $package,
        array $criteria = [],
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null,
    ): iterable {
        $pkgId = is_int($package) ? $package : null;

        if ($package instanceof Package) {
            $package = $this
                ->getEntityManager()
                ->getRepository(PackageEntity::class)
                ->findOneBy([
                    'handle' => $package->getPackageHandle(),
                ]);
        }

        if ($package instanceof PackageEntity) {
            $pkgId = $package->getPackageID();
        }

        if ($pkgId === null) {
            throw new \InvalidArgumentException(
                'Package must be an instance of PackageEntity, Package, or an integer package ID.',
            );
        }

        $criteria['packageId'] = $pkgId;
        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }
}
