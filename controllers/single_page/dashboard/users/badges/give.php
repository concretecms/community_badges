<?php /** @noinspection PhpUnused */
/** @noinspection PhpInconsistentReturnPointsInspection */

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityBadges\Controller\SinglePage\Dashboard\Users\Badges;

use Concrete\Core\Http\Response;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\User\User;
use Concrete\Core\User\UserInfoRepository;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Exceptions\AchievementAlreadyExists;
use PortlandLabs\CommunityBadges\Exceptions\AwardedBadgeNotFound;
use PortlandLabs\CommunityBadges\Exceptions\BadgeNotFound;
use PortlandLabs\CommunityBadges\Exceptions\InvalidBadgeType;
use PortlandLabs\CommunityBadges\Exceptions\MailTransportError;
use PortlandLabs\CommunityBadges\Exceptions\NoUserSelected;

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
        $userBadgeId = null
    )
    {
        try {
            $userBadge = $this->awardService->getUserBadgeById((int)$userBadgeId);
            $this->set('badgeList', $this->awardService->getBadgeList());
            $this->set("success", t("The badge was successfully assigned to the user %s.", $userBadge->getUser()->getUserName()));
        } catch (AwardedBadgeNotFound $e) {
            return $this->responseFactory->notFound(t("Awarded Award not found."));
        }
    }


    public function view()
    {
        if ($this->request->getMethod() === "POST") {
            $badgeId = $this->request->request->getInt("badge", 0);
            $userId = $this->request->request->getInt("user", 0);

            if ($this->token->validate("give_award")) {
                $user = User::getByUserID($userId);

                try {
                    $badge = $this->awardService->getBadgeById((int)$badgeId);

                    try {
                        $userBadge = $this->awardService->giveBadge($badge, $user);

                        return $this->responseFactory->redirect((string)Url::to("/dashboard/users/badges/give/given", $userBadge->getId()), Response::HTTP_TEMPORARY_REDIRECT);
                    } catch (MailTransportError $e) {
                        $this->error->add(t("There was an error while sending the mail notification."));
                    } catch (NoUserSelected $e) {
                        $this->error->add(t("You need to select a user."));
                    } catch (InvalidBadgeType $e) {
                        $this->error->add(t("Invalid badge type."));
                    } catch (AchievementAlreadyExists $e) {
                        $this->error->add(t("The user has already received the selected achievement."));
                    }
                } catch (BadgeNotFound $e) {
                    $this->error->add(t("You need to select a valid award."));
                }
            } else {
                $this->error->add($this->token->getErrorMessage());
            }
        }

        $this->set('badgeList', $this->awardService->getBadgeList());
    }
}
