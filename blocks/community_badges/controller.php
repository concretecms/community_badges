<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityBadges\Block\CommunityBadges;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Page\Page;
use Concrete\Core\User\User;
use Concrete\Core\User\UserInfo;
use PortlandLabs\CommunityBadges\AwardService;

class Controller extends BlockController
{
    protected $btTable = "btCommunityBadges";
    /** @var AwardService */
    protected $awardService;
    /** @var ErrorList */
    protected $error;

    public function getBlockTypeDescription()
    {
        return t('Integrate the community awards feature awards into your site.');
    }

    public function getBlockTypeName()
    {
        return t('Community Badges');
    }

    public function on_start()
    {
        parent::on_start();

        $this->awardService = $this->app->make(AwardService::class);
        $this->error = $this->app->make(ErrorList::class);
    }

    public function on_before_render()
    {
        parent::on_before_render();

        if ($this->error->has()) {
            $this->set('error', $this->error);
        }
    }

    public function view()
    {
        $grantedAwards = [];
        $achievements = [];
        $awards = [];

        $currentPage = Page::getCurrentPage();
        $profile = $currentPage->getPageController()->get("profile");
        $currentUser = new User();

        if ($profile instanceof UserInfo) {
            $user = User::getByUserID($profile->getUserID());

            if ($currentUser->isRegistered() && $profile->getUserID() == $currentUser->getUserID()) {
                $grantedAwards = $this->awardService->getAllGrantedAwardsGroupedByUser($user);
            }

            $achievements = $this->awardService->getAllAchievementsByUser($user);
            $awards = $this->awardService->getAllAwardsGroupedByUser($user);
        }

        $this->set('grantedAwards', $grantedAwards);
        $this->set('achievements', $achievements);
        $this->set('awards', $awards);
    }
}
