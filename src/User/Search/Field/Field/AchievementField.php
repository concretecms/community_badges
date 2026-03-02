<?php

namespace PortlandLabs\CommunityBadges\User\Search\Field\Field;

use Concrete\Core\Search\Field\AbstractField;
use Concrete\Core\Search\ItemList\ItemList;
use Concrete\Core\User\Group\GroupList;
use Concrete\Core\User\UserList;
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

    /**
     * @param UserList $list
     * @param $request
     */
    public function filterList(ItemList $list)
    {
        if (isset($this->data['badgeID'])) {
            $query = $list->getQueryObject();
            $query->innerJoin('u', 'UserBadge', 'ub', 'u.uID = ub.uID');
            $query->andWhere('ub.badgeId = :badgeId');
            $query->setParameter('badgeId', (int) $this->data['badgeID']);
        }
    }

    public function renderSearchField()
    {
        $awardService = app(AwardService::class);
        $achievements = $awardService->getAllAchievements([], ['name' => 'asc']);

        $html = '<div class="form-group"><select class="form-select form-control" name="badgeID">';
        foreach ($achievements as $achievement) {
            $html .= '<option value="' . $achievement->getID() . '" ';
            if (isset($this->data['badgeID']) && $this->data['badgeID'] == $achievement->getID()) {
                $html .= 'selected="selected" ';
            }
            $html .= '>' . $achievement->getName() . '</option>';
        }
        $html .= '</select></div>';

        return $html;
    }
}
