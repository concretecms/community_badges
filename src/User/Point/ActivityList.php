<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\User\Point;

use Concrete\Core\Legacy\DatabaseItemList;

class ActivityList extends DatabaseItemList
{
    protected $itemsPerPage = 50;

    public function __construct()
    {
        $this->setQuery(
            'select sum(upPoints) as total, upuID from UserPointHistory inner join UserPointActions on UserPointHistory.upaID = UserPointActions.upaID',
        );
        $this->groupBy('upuID');
        $this->sortBy('total', 'desc');
    }

    /**
     * @param string $period
     * @return void
     */
    public function filterByTimePeriod($period)
    {
        $ts = \DateTimeImmutable::createFromTimestamp((int) strtotime($period))->format('Y-m-d H:i:s');
        $this->filter('timestamp', $ts, '>=');
    }

    /**
     * @param string $periodFrom
     * @param string $periodTo
     * @return void
     */
    public function filterByTimePeriodRange($periodFrom, $periodTo)
    {
        $ts1 = \DateTimeImmutable::createFromTimestamp((int) strtotime($periodFrom))->format('Y-m-d H:i:s');
        $ts2 = \DateTimeImmutable::createFromTimestamp((int) strtotime($periodTo))->format('Y-m-d H:i:s');
        $this->filter('timestamp', $ts1, '>=');
        $this->filter('timestamp', $ts2, '<=');
    }

    public function filterByAction($upaID)
    {
        $this->filter('upaID', $upaID);
    }
}
