<?php /** @noinspection PhpInconsistentReturnPointsInspection */
/** @noinspection DuplicatedCode */

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityBadges\Controller\SinglePage\Dashboard\System\CommunityBadges;

use Concrete\Core\Form\Service\Validation;
use Concrete\Core\Http\Response;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Support\Facade\Url;
use Concrete\Package\CommunityBadges\Controller\Element\Dashboard\Automation\Header;
use Doctrine\ORM\EntityManagerInterface;
use PortlandLabs\CommunityBadges\Automation\Triggers\Driver\DriverInterface;
use PortlandLabs\CommunityBadges\Automation\Triggers\Driver\Manager;
use Concrete\Core\Page\Controller\DashboardPageController;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Entity\AutomationRule;
use PortlandLabs\CommunityBadges\Entity\Badge;
use PortlandLabs\CommunityBadges\Entity\Trigger;
use PortlandLabs\CommunityBadges\Exceptions\BadgeNotFound;

class Automation extends DashboardPageController
{
    /** @var Manager */
    protected $driverManager;
    /** @var AwardService */
    protected $awardService;
    /** @var ResponseFactory */
    protected $responseFactory;
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function on_start()
    {
        parent::on_start();
        $this->driverManager = $this->app->make(Manager::class);
        $this->awardService = $this->app->make(AwardService::class);
        $this->responseFactory = $this->app->make(ResponseFactory::class);
        $this->entityManager = $this->app->make(EntityManagerInterface::class);
    }

    private function validate()
    {
        /** @var Validation $formValidator */
        $formValidator = $this->app->make(Validation::class);

        $formValidator->setData($this->request->request->all());

        $formValidator->addRequiredToken("save_automation_rule");
        $formValidator->addRequired("name", t("Please enter a name."));

        if ($formValidator->test()) {
            return true;
        } else {
            $this->error = $formValidator->getError();
            return false;
        }
    }

    public function added()
    {
        $this->set('success', t("Trigger added successfully."));
        $this->view();
    }

    public function updated()
    {
        $this->set('success', t("Automation Rule added successfully."));
        $this->view();
    }

    public function removed()
    {
        $this->set('success', t("Automation Rule removed successfully."));
        $this->view();
    }

    public function remove(
        $automationRuleId = null
    )
    {
        $automationRule = $this->entityManager->getRepository(AutomationRule::class)->findOneBy(["id" => $automationRuleId]);

        if ($automationRule instanceof AutomationRule) {
            $this->entityManager->remove($automationRule);
            $this->entityManager->flush();
            return $this->responseFactory->redirect((string)Url::to("/dashboard/system/community_badges/automation/removed"), Response::HTTP_TEMPORARY_REDIRECT);
        } else {
            return $this->responseFactory->notFound(t("Automation Rule not found."));
        }
    }

    public function edit(
        $automationRuleId = null
    )
    {
        $automationRule = $this->entityManager->getRepository(AutomationRule::class)->findOneBy(["id" => $automationRuleId]);

        if ($automationRule instanceof AutomationRule) {
            $driverHandle = $automationRule->getDriverHandle();

            try {
                /** @var DriverInterface $driver */
                $driver = $this->driverManager->driver($driverHandle);
            } catch (\InvalidArgumentException $argumentException) {
                return $this->responseFactory->notFound(t("Trigger not found."));
            }

            if ($this->request->getMethod() === "POST") {
                if ($this->validate()) {
                    try {
                        $badge = $this->awardService->getBadgeById($this->request->request->getInt("badgeId"));

                        $automationRule->setName($this->request->request->get("name"));
                        $automationRule->setDriverHandle($driverHandle);
                        $automationRule->setBadge($badge);

                        $errorList = $driver->getSaver()->validateRequest($this->request);

                        if (!$errorList->has()) {
                            $this->entityManager->persist($automationRule);
                            $this->entityManager->flush();

                            $driver->getSaver()->saveConfiguration($this->request, $automationRule);

                            return $this->responseFactory->redirect((string)Url::to("/dashboard/system/community_badges/automation/updated"), Response::HTTP_TEMPORARY_REDIRECT);
                        } else {
                            foreach($errorList->getList() as $error) {
                                $this->error->add($error);
                            }
                        }
                    } catch (BadgeNotFound $e) {
                        $this->error->add(t("Invalid badge."));
                    }
                }
            }

            $this->set('automationRule', $automationRule);
            $this->set('badgeList', $this->awardService->getBadgeList());
            $this->set('driverHandle', $driverHandle);
            $this->set('driver', $driver);

            $this->render('/dashboard/system/community_badges/automation/edit');

        } else {
            return $this->responseFactory->notFound(t("Automation Rule not found."));
        }
    }

    public function add(
        $driverHandle = ''
    )
    {
        if ($driverHandle == '') {
            return $this->responseFactory->notFound(t("Trigger not found."));
        } else {
            try {
                /** @var DriverInterface $driver */
                $driver = $this->driverManager->driver($driverHandle);
            } catch (\InvalidArgumentException $argumentException) {
                return $this->responseFactory->notFound(t("Trigger not found."));
            }

            $automationRule = new AutomationRule();

            if ($this->request->getMethod() === "POST") {
                if ($this->validate()) {
                    try {
                        $badge = $this->awardService->getBadgeById($this->request->request->getInt("badgeId"));

                        $automationRule->setName($this->request->request->get("name"));
                        $automationRule->setDriverHandle($driverHandle);
                        $automationRule->setBadge($badge);

                        $errorList = $driver->getSaver()->validateRequest($this->request);

                        if (!$errorList->has()) {
                            $this->entityManager->persist($automationRule);
                            $this->entityManager->flush();

                            $driver->getSaver()->saveConfiguration($this->request, $automationRule);

                            return $this->responseFactory->redirect((string)Url::to("/dashboard/system/community_badges/automation/added"), Response::HTTP_TEMPORARY_REDIRECT);
                        } else {
                            foreach($errorList->getList() as $error) {
                                $this->error->add($error);
                            }
                        }
                    } catch (BadgeNotFound $e) {
                        $this->error->add(t("Invalid badge."));
                    }
                }
            }

            $this->set('automationRule', $automationRule);
            $this->set('badgeList', $this->awardService->getBadgeList());
            $this->set('driverHandle', $driverHandle);
            $this->set('driver', $driver);

            $this->render('/dashboard/system/community_badges/automation/edit');
        }
    }

    public function view()
    {
        $this->set('headerMenu', new Header());
        $this->set('automationRules', $this->entityManager->getRepository(AutomationRule::class)->findAll());
    }
}
