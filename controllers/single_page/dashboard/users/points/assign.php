<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityBadges\Controller\SinglePage\Dashboard\Users\Points;

use Concrete\Core\Form\Service\Widget\DateTime;
use Concrete\Core\Http\ResponseFactoryInterface;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Page\Page;
use Concrete\Core\User\UserInfoRepository;
use PortlandLabs\CommunityBadges\Entity\Point\Action;
use PortlandLabs\CommunityBadges\Entity\Point\ActionService;
use PortlandLabs\CommunityBadges\Entity\Point\TimelineEntry;
use PortlandLabs\CommunityBadges\User\Point\Action\Action as UserPointAction;
use PortlandLabs\CommunityBadges\User\Point\Action\ActionDescription as UserPointActionDescription;

class Assign extends DashboardPageController
{
    public $helpers = [
        'form',
        'concrete/ui',
        'concrete/urls',
        'image',
        'concrete/asset_library',
        'form/user_selector',
        'form/date_time',
    ];

    public function __construct(
        Page $c,
        private readonly ActionService $actionService,
    ) {
        parent::__construct($c);
    }

    public function view($upID = null)
    {
        if (is_numeric($upID)) {
            $user = $this->entityManager->find(TimelineEntry::class, $upID)?->user;
            $this->set('upUser', $user?->getUserID());
        }

        $this->set('userPointActions', $this->getUserPointActions());
    }

    public function save()
    {
        if (!$this->token->validate('add_community_points')) {
            $this->error->add('Invalid Token');

            return $this->view();
        }

        $post = $this->request->request;
        $user = $post->get('upUser');
        if (is_numeric($user)) {
            // rolling as user id
            $ui = $this->app->make(UserInfoRepository::class)->getByID($user);
        } else {
            $ui = $this->app->make(UserInfoRepository::class)->getByName($user);

            // look up userID
        }

        if (!is_object($ui)) {
            $this->error->add(t('User Required'));
        }

        if (!($upaID = $post->get('upaID'))) {
            $this->error->add(t('Action Required'));
        }

        if (!is_numeric($upPoints = $post->get('upPoints'))) {
            $this->error->add(t('Points Required'));
        }

        $action = $this->actionService->getRepository()->find($upaID);
        if (!$action) {
            $this->error->add(t('Invalid Action'));
        }
        $controller = $this->actionService->getController($action);

        if (!$this->error->has()) {
            $obj = new UserPointActionDescription($post->get('upComments'));
            $now = new \DateTimeImmutable('now');
            if ($this->post('manual_datetime') > 0) {
                /** @var DateTime $dt */
                $dt = $this->app->make('helper/form/date_time');
                $now = \DateTimeImmutable::createFromInterface($dt->translate('dtoverride', asDateTime: true));
            }

            $entry = new TimelineEntry(
                $ui->getEntityObject(),
                $action,
                0,
                $controller->serializeActionDescriptionObject($obj),
                $now,
            );
            $entry->points = $controller->getActionPoints($entry);
            $this->actionService->em->persist($entry);
            $this->actionService->em->flush();

            return $this->buildRedirect($this->action('entry_saved'));
        }

        $this->view();
    }

    public function getUserPointActions()
    {
        $res = [0 => t('-- None --')];

        /** @var Action[] $actions */
        $actions = $this->entityManager->getRepository(Action::class)->findBy([], ['defaultPoints' => 'asc']);
        foreach ($actions as $action) {
            $res[$action->id] = h("{$action->defaultPoints} - {$action->name}");
        }

        return $res;
    }

    public function getJsonActionSelectOptions()
    {
        $actions = $this->getUserPointActions();
        $res = [];
        foreach ($actions as $key => $value) {
            $res[] = ['optionValue' => $key, 'optionDisplay' => $value];
        }

        return $this->app->make(ResponseFactoryInterface::class)->json($res);
    }

    public function getJsonDefaultPointAction($upaID)
    {
        $action = $this->actionService->getRepository()->find((int) $upaID);
        return $this->app->make(ResponseFactoryInterface::class)->json($action?->defaultPoints);
    }

    public function entry_saved()
    {
        $this->set('message', t('User Point Entry Saved'));
        $this->view();
    }
}
