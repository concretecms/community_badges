<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Entity\Point;

use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<TimelineEntry>
 */
class TimelineEntryRepository extends EntityRepository
{
    public function getTotal(int $userId): int
    {
        return (int) $this
            ->createQueryBuilder('te')
            ->select('SUM(te.points)')
            ->where('te.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
