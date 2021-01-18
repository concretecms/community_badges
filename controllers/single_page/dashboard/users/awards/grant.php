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
use PortlandLabs\CommunityAwards\Exceptions\AwardNotFound;
use PortlandLabs\CommunityAwards\Exceptions\GrantAwardNotFound;
use PortlandLabs\CommunityAwards\Exceptions\MailTransportError;
use PortlandLabs\CommunityAwards\Exceptions\NoUserSelected;

class Grant extends DashboardPageController
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
    public function granted(
        $grantedAwardId = null
    )
    {
        try {
            $grantedAward = $this->awardService->getGrantAwardById((int)$grantedAwardId);

            $this->set('awardList', $this->awardService->getAwardList());
            $this->set("success", t("The grant award was successfully assigned to the user %s.", $grantedAward->getUser()->getUserName()));
        } catch (GrantAwardNotFound $e) {
            return $this->responseFactory->notFound(t("Grant Award not found."));
        }
    }

    public function view()
    {
        if ($this->request->getMethod() === "POST") {
            $awardId = $this->request->request->getInt("award", 0);
            $userId = $this->request->request->getInt("user", 0);

            if ($this->token->validate("grant_award")) {
                $user = User::getByUserID($userId);

                try {
                    $award = $this->awardService->getAwardById((int)$awardId);

                    try {
                        $awardedAward = $this->awardService->grantAward($award, $user);

                        return $this->responseFactory->redirect((string)Url::to("/dashboard/users/awards/grant/granted", $awardedAward->getId()), Response::HTTP_TEMPORARY_REDIRECT);
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
