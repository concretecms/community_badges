<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\User\Search\Field\Field;

use Concrete\Core\Search\Field\AbstractField;
use Concrete\Core\Search\ItemList\ItemList;
use Concrete\Core\User\UserList;
use Doctrine\DBAL\Query\QueryBuilder;
use PortlandLabs\CommunityBadges\AwardService;

class AchievementField extends AbstractField
{
    protected $requestVariables = [
        'badgeID',
    ];

    public function getKey()
    {
        return 'achievement';
    }

    public function getDisplayName()
    {
        return t('Achievement Won');
    }

    public function filterList(ItemList $list)
    {
        if (isset($this->data['badgeID'])) {
            /** @var QueryBuilder $query */
            $query = $list->getQueryObject();
            $query->innerJoin('u', 'UserBadge', 'ub', 'u.uID = ub.uID');
            $query->andWhere('ub.badgeId = :badgeId');
            $query->setParameter('badgeId', (int) $this->data['badgeID']);
        }
    }

    public function renderSearchField()
    {
        /** @var AwardService $awardService */
        $awardService = app(AwardService::class);
        $achievements = $awardService->getAllAchievements([], ['name' => 'asc']);

        $html = '<div class="form-group"><select class="form-select form-control" name="badgeID">';
        foreach ($achievements as $achievement) {
            $html .= '<option value="' . $achievement->id . '" ';
            if (isset($this->data['badgeID']) && $this->data['badgeID'] === $achievement->id) {
                $html .= 'selected="selected" ';
            }
            $html .= '>' . $achievement->name . '</option>';
        }
        $html .= '</select></div>';

        return $html;
    }
}
