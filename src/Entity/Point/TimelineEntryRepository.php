<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Entity\Point;

use Concrete\Core\Entity\User\User;
use Doctrine\Common\Collections\AbstractLazyCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * @extends EntityRepository<TimelineEntry>
 * @final
 */
class TimelineEntryRepository extends EntityRepository
{
    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getTotal(int|User $userId): int
    {
        if ($userId instanceof User) {
            $userId = $userId->getUserID();
        }

        return (int) $this
            ->createQueryBuilder('te')
            ->select('SUM(te.points)')
            ->where('te.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return AbstractLazyCollection<int, TimelineEntry>&Selectable<int, TimelineEntry>
     */
    public function forUser(User $user): AbstractLazyCollection
    {
        return $this->matching(new Criteria()->andWhere(Criteria::expr()->eq('user', $user)));
    }
}
