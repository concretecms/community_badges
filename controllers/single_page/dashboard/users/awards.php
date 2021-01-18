<?php

/**
 * @project:   Community Awards
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityAwards\Controller\SinglePage\Dashboard\Users;

use Concrete\Core\File\File;
use Concrete\Core\Form\Service\Validation;
use Concrete\Core\Http\Response;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Support\Facade\Url;
use Concrete\Package\CommunityAwards\Controller\Element\Awards\Header;
use PortlandLabs\CommunityAwards\AwardService;
use PortlandLabs\CommunityAwards\Entity\Award;
use PortlandLabs\CommunityAwards\Exceptions\AwardNotFound;

class Awards extends DashboardPageController
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
        $this->validationService->addRequired("description", t("Please enter description."));

        if ($this->validationService->test()) {
            return true;
        } else {
            $this->error = $this->validationService->getError();
            return false;
        }
    }

    private function save(
        Award $award
    ): void
    {
        $award->setDescription($this->request->request->get("description"));
        $award->setGroupId($this->request->request->getInt("groupId"));
        $award->setName($this->request->request->get("name"));
        $award->setThumbnail(File::getByID($this->request->request->getInt("thumbnail")));

        $this->awardService->saveAward($award);
    }

    /** @noinspection PhpUnused */
    public function saved(
        $awardId = null
    )
    {
        $this->view();

        try {
            $award = $this->awardService->getAwardById((int)$awardId);

            $this->set("success", t("The award %s was successfully saved.", $award->getName()));
        } catch (AwardNotFound $e) {
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
        $award = new Award();
        $this->set("award", $award);
        $this->render("/dashboard/users/awards/edit");

        if ($this->request->getMethod() === "POST") {
            if ($this->validate()) {
                $this->save($award);
                return $this->responseFactory->redirect((string)Url::to("/dashboard/users/awards/saved", $award->getId()), Response::HTTP_TEMPORARY_REDIRECT);
            }
        }
    }

    public function remove(
        $awardId = null
    )
    {
        try {
            $award = $this->awardService->getAwardById((int)$awardId);
            $this->awardService->removeAward($award);
            return $this->responseFactory->redirect((string)Url::to("/dashboard/users/awards/removed"), Response::HTTP_TEMPORARY_REDIRECT);
        } catch (AwardNotFound $e) {
            return $this->responseFactory->notFound(t("Award not found."));
        }
    }

    public function edit(
        $awardId = null
    )
    {
        try {
            $award = $this->awardService->getAwardById((int)$awardId);
            $this->set("award", $award);
            $this->render("/dashboard/users/awards/edit");
        } catch (AwardNotFound $e) {
            return $this->responseFactory->notFound(t("Award not found."));
        }

        if ($this->request->getMethod() === "POST") {
            if ($this->validate()) {
                $this->save($award);
                return $this->responseFactory->redirect((string)Url::to("/dashboard/users/awards/saved", $award->getId()), Response::HTTP_TEMPORARY_REDIRECT);
            }
        }
    }

    public function view()
    {
        $this->set("awards", $this->awardService->getAllAwards());
        $this->set("headerMenu", new Header());
    }
}
