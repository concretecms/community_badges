<?php

/**
 * @project:   Community Awards
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityAwards\Controller\SinglePage\Dashboard\Users\Awards;

use Concrete\Core\Http\Response;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\User\User;
use Concrete\Core\User\UserInfoRepository;
use PortlandLabs\CommunityAwards\AwardService;
use PortlandLabs\CommunityAwards\Exceptions\AwardedAwardNotFound;
use PortlandLabs\CommunityAwards\Exceptions\AwardNotFound;
use PortlandLabs\CommunityAwards\Exceptions\MailTransportError;
use PortlandLabs\CommunityAwards\Exceptions\NoUserSelected;

class Give extends DashboardPageController
{
    /** @var AwardService */
    protected $awardService;
    /** @var UserInfoRepository */
    protected $userInfoRepository;
    /** @var ResponseFactory */
    protected $responseFactory;

    public function on_start()
    {
        parent::on_start();

        $this->awardService = $this->app->make(AwardService::class);
        $this->userInfoRepository = $this->app->make(UserInfoRepository::class);
        $this->responseFactory = $this->app->make(ResponseFactory::class);
    }

    /** @noinspection PhpUnused */
    public function given(
        $awardedAwardId = null
    )
    {
        try {
            $awardedAward = $this->awardService->getAwardedAwardById((int)$awardedAwardId);

            $this->set('awardList', $this->awardService->getAwardList());
            $this->set("success", t("The award was successfully assigned to the user %s.", $awardedAward->getUser()->getUserName()));
        } catch (AwardedAwardNotFound $e) {
            return $this->responseFactory->notFound(t("Awarded Award not found."));
        }
    }


    public function view()
    {
        if ($this->request->getMethod() === "POST") {
            $awardId = $this->request->request->getInt("award", 0);
            $userId = $this->request->request->getInt("user", 0);

            if ($this->token->validate("give_award")) {
                $user = User::getByUserID($userId);

                try {
                    $award = $this->awardService->getAwardById((int)$awardId);

                    try {
                        $awardedAward = $this->awardService->giveAward($award, $user);

                        return $this->responseFactory->redirect((string)Url::to("/dashboard/users/awards/give/given", $awardedAward->getId()), Response::HTTP_TEMPORARY_REDIRECT);
                    } catch (MailTransportError $e) {
                        $this->error->add(t("There was an error while sending the mail notification."));
                    } catch (NoUserSelected $e) {
                        $this->error->add(t("You need to select a user."));
                    }
                } catch (AwardNotFound $e) {
                    $this->error->add(t("You need to select a valid award."));
                }
            } else {
                $this->error->add($this->token->getErrorMessage());
            }
        }

        $this->set('awardList', $this->awardService->getAwardList());
    }
}
