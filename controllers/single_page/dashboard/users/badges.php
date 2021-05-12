<?php /** @noinspection PhpInconsistentReturnPointsInspection */
/** @noinspection DuplicatedCode */

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityBadges\Controller\SinglePage\Dashboard\Users;

use Concrete\Core\File\File;
use Concrete\Core\Form\Service\Validation;
use Concrete\Core\Http\Response;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Support\Facade\Url;
use Concrete\Package\CommunityBadges\Controller\Element\Badges\Header;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Entity\Achievement;
use PortlandLabs\CommunityBadges\Entity\Award;
use PortlandLabs\CommunityBadges\Entity\Badge;
use PortlandLabs\CommunityBadges\Enumerations\BadgeTypes;
use PortlandLabs\CommunityBadges\Exceptions\BadgeNotFound;

class Badges extends DashboardPageController
{
    /** @var AwardService */
    protected $awardService;
    /** @var ResponseFactory */
    protected $responseFactory;
    /** @var Validation */
    protected $validationService;

    public function on_start()
    {
        parent::on_start();

        $this->awardService = $this->app->make(AwardService::class);
        $this->validationService = $this->app->make(Validation::class);
        $this->responseFactory = $this->app->make(ResponseFactory::class);
    }

    private function validate(): bool
    {
        $this->validationService->setData($this->request->request->all());
        $this->validationService->addRequired("name", t("Please enter a name."));
        $this->validationService->addRequired("handle", t("Please enter a handle."));
        $this->validationService->addRequired("description", t("Please enter description."));

        if ($this->validationService->test()) {
            return true;
        } else {
            $this->error = $this->validationService->getError();
            return false;
        }
    }

    private function save(
        Badge $badge
    ): void
    {
        $badge->setDescription($this->request->request->get("description"));
        $badge->setName($this->request->request->get("name"));
        $badge->setHandle($this->request->request->get("handle"));
        $badge->setThumbnail(File::getByID($this->request->request->getInt("thumbnail")));

        $this->awardService->saveBadge($badge);
    }

    /** @noinspection PhpUnused */
    public function saved(
        $badgeId = null
    )
    {
        $this->view();

        try {
            $badge = $this->awardService->getBadgeById((int)$badgeId);

            $this->set("success", t("The badge %s was successfully saved.", $badge->getName()));
        } catch (BadgeNotFound $e) {
            return $this->responseFactory->notFound(t("Awarded Award not found."));
        }
    }

    /** @noinspection PhpUnused */
    public function removed()
    {
        $this->view();

        $this->set("success", t("The award has been successfully removed."));
    }

    public function add()
    {
        $badge = new Badge();
        $this->set("badge", $badge);
        $this->set("isNew", true);
        $this->set("badgeTypes", $this->awardService->getBadgeTypeList());
        $this->set("badgeType", BadgeTypes::AWARD);

        $this->render("/dashboard/users/badges/edit");

        if ($this->request->getMethod() === "POST") {
            if ($this->validate()) {
                if ($this->request->request->get("badgeType") === BadgeTypes::AWARD) {
                    $badge = new Award();
                } else {
                    $badge = new Achievement();
                }

                $this->save($badge);

                return $this->responseFactory->redirect((string)Url::to("/dashboard/users/badges/saved", $badge->getId()), Response::HTTP_TEMPORARY_REDIRECT);
            }
        }
    }

    public function remove(
        $badgeId = null
    )
    {
        try {
            $badge = $this->awardService->getBadgeById((int)$badgeId);
            $this->awardService->removeBadge($badge);
            return $this->responseFactory->redirect((string)Url::to("/dashboard/users/badges/removed"), Response::HTTP_TEMPORARY_REDIRECT);
        } catch (BadgeNotFound $e) {
            return $this->responseFactory->notFound(t("Award not found."));
        }
    }

    public function edit(
        $badgeId = null
    )
    {
        try {
            $badge = $this->awardService->getBadgeById((int)$badgeId);
            $this->set("badge", $badge);
            $this->set("isNew", false);
            $this->render("/dashboard/users/badges/edit");
        } catch (BadgeNotFound $e) {
            return $this->responseFactory->notFound(t("Award not found."));
        }

        if ($this->request->getMethod() === "POST") {
            if ($this->validate()) {
                $this->save($badge);

                return $this->responseFactory->redirect((string)Url::to("/dashboard/users/badges/saved", $badge->getId()), Response::HTTP_TEMPORARY_REDIRECT);
            }
        }
    }

    public function view()
    {
        $this->set("awards", $this->awardService->getAllAwards());
        $this->set("achievements", $this->awardService->getAllAchievements());
        $this->set("headerMenu", new Header());
    }
}
