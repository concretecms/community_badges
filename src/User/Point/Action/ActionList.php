<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\User\Point\Action;

use Concrete\Core\Search\ItemList\EntityItemList;
use Concrete\Core\Search\Pagination\Pagination;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use PortlandLabs\CommunityBadges\Entity\Point\Action as EntityAction;
use PortlandLabs\CommunityBadges\Entity\Point\ActionRepository;

class ActionList extends EntityItemList
{
    protected $autoSortColumns = ['upa.upaName', 'upa.upaHandle', 'upa.upaDefaultPoints', 'upa.gBadgeID'];

    public function __construct(
        protected EntityManager $em,
    ) {
        parent::__construct();
    }

    public function createQuery(): void
    {
        $repository = $this->getEntityManager()->getRepository(EntityAction::class);
        assert($repository instanceof ActionRepository);
        $this->query = $repository->createQueryBuilder('upa');
    }

    public function filterByIsActive($active)
    {
        $this->query->andWhere('upa.isActive = :upaIsActive');
        $this->query->setParameter('upaIsActive', $active);
    }

    /**
     * The total results of the query.
     *
     * @return int
     */
    public function getTotalResults()
    {
        /** @var QueryBuilder $query */
        $query = $this->deliverQueryObject();

        return (int) $query
            ->resetQueryParts(['groupBy', 'orderBy'])
            ->select('count(distinct upa.upaID)')
            ->setMaxResults(1)
            ->execute()
            ->fetchOne();
    }

    /**
     * Gets the pagination object for the query.
     *
     * @return Pagination
     */
    public function createPaginationObject()
    {
        $query = $this->deliverQueryObject();
        $adapter = new QueryAdapter($query);

        return new Pagination($this, $adapter);
    }

    public function getResult($mixed): EntityAction
    {
        assert($mixed instanceof EntityAction);
        return $mixed;
    }

    public function getEntityManager(): EntityManager
    {
        return $this->em;
    }
}
