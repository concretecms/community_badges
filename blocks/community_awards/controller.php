<?php

/**
 * @project:   Community Awards
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityAwards\Block\CommunityAwards;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Page\Page;
use Concrete\Core\User\User;
use Concrete\Core\User\UserInfo;
use PortlandLabs\CommunityAwards\AwardService;

class Controller extends BlockController
{
    protected $btTable = "btCommunityAwards";
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
        return t('Community Awards');
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
        $awardedAwards = [];

        $currentPage = Page::getCurrentPage();
        $profile = $currentPage->getPageController()->get("profile");

        if ($profile instanceof UserInfo) {
            $user = User::getByUserID($profile->getUserID());
            $grantedAwards = $this->awardService->getAllGrantedAwardsByUser($user);
            $awardedAwards = $this->awardService->getAllAwardedAwardsByUser($user);
        }

        $this->set('grantedAwards', $grantedAwards);
        $this->set('awardedAwards', $awardedAwards);
    }
}
