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

    /**
     * This method splits user badges by a given prefix into two arrays.
     *
     * @param array $allBadges
     * @param string $searchPrefix
     * @param array $matchedBadges
     * @param array $otherBadges
     */
    private function splitBadgesByPrefix(
        array $allBadges,
        string $searchPrefix,
        array &$matchedBadges = [],
        array &$otherBadges = []
    ): void
    {
        foreach ($allBadges as $curBadge) {
            $badgeHandle = $curBadge["userBadge"]->getBadge()->getHandle();

            if (substr($badgeHandle, 0, strlen($searchPrefix)) === $searchPrefix) {
                $matchedBadges[] = $curBadge;
            } else {
                $otherBadges[] = $curBadge;
            }
        }
    }

    public function view()
    {
        $grantedAwards = [];
        $badges = [];
        $certifications = [];

        $currentPage = Page::getCurrentPage();
        $profile = $currentPage->getPageController()->get("profile");
        $currentUser = new User();
        $isOwnProfile = false;

        if ($profile instanceof UserInfo) {
            $user = User::getByUserID($profile->getUserID());

            if ($currentUser->isRegistered() && $profile->getUserID() == $currentUser->getUserID()) {
                $isOwnProfile = true;
                $grantedAwards = $this->awardService->getAllGrantedAwardsGroupedByUser($user);
            }

            // Retrieve the user badges and split them into certifications + all others
            $this->splitBadgesByPrefix(
                $this->awardService->getAllBadgesGroupedByUser($user),
                "test_",
                $certifications,
                $badges
            );
        }

        $this->set('isOwnProfile', $isOwnProfile);
        $this->set('grantedAwards', $grantedAwards);
        $this->set('badges', $badges);
        $this->set('certifications', $certifications);
    }
}
