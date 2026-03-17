<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\User\Point;

use Concrete\Core\Entity\User\User;
use Concrete\Core\Search\ItemList\EntityItemList;
use Concrete\Core\Search\Pagination\Pagination;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use PortlandLabs\CommunityBadges\Entity\Point\Action;
use PortlandLabs\CommunityBadges\Entity\Point\TimelineEntry;
use PortlandLabs\CommunityBadges\Package\ItemCategory\UserPointAction;

class EntryList extends EntityItemList
{
    protected $autoSortColumns = ['u.uName', 'upa.name', 'uph.points', 'uph.date'];

    public function __construct(
        protected EntityManager $em,
    ) {
        parent::__construct();
    }

    public function getEntityManager(): EntityManager
    {
        return $this->em;
    }

    /**
     * @param string $uName
     */
    public function filterByUserName(string $uName): void
    {
        $this->query->andWhere('u.name = :uName');
        $this->query->setParameter('uName', $uName);
    }

    /**
     * @param string $upaName
     */
    public function filterByUserPointActionName(string $upaName): void
    {
        $this->query->andWhere($this->query->expr()->like('upa.name', ':upaName'));
        $this->query->setParameter('upaName', '%' . $upaName . '%');
    }

    /**
     * @param int $uID
     */
    public function filterByUserID(int $uID): void
    {
        $this->query->andWhere('uph.user = :upuID');
        $this->query->setParameter('upuID', (int) $uID);
    }

    /**
     * @param array $mixed
     *
     * @return TimelineEntry
     */
    public function getResult($mixed)
    {
        assert($mixed instanceof TimelineEntry);
        return $mixed;
    }

    /**
     * The total results of the query.
     *
     * @return int
     */
    public function getTotalResults(): int
    {
        $query = $this->deliverQueryObject();
        assert($query instanceof QueryBuilder);
        $count = (int) $query
            ->resetDQLParts(['groupBy', 'orderBy'])
            ->select('count(distinct uph.id)')
            ->setMaxResults(1)
            ->getQuery()
            ->execute(hydrationMode: AbstractQuery::HYDRATE_SINGLE_SCALAR);
        return $count;
    }

    public function createQuery(): void
    {
        $this->query = $this
            ->getEntityManager()
            ->getRepository(TimelineEntry::class)
            ->createQueryBuilder('uph')
            ->leftJoin(Action::class, 'upa', 'WITH', 'uph.action = upa.id')
            ->leftJoin(User::class, 'u', 'WITH', 'uph.user = u.uID');
    }

    public function createPaginationObject(): Pagination
    {
        return new Pagination($this, new QueryAdapter($this->deliverQueryObject()));
    }
}
