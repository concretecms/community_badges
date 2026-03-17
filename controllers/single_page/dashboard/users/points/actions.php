<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityBadges\Controller\SinglePage\Dashboard\Users\Points;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Page\Page;
use Concrete\Core\Search\Pagination\PaginationFactory;
use PortlandLabs\CommunityBadges\Entity\Point\Action;
use PortlandLabs\CommunityBadges\Entity\Point\ActionService;
use PortlandLabs\CommunityBadges\User\Point\Action\ActionList as UserPointActionList;

class Actions extends DashboardPageController
{
    public $helpers = ['form', 'concrete/ui', 'concrete/urls', 'image', 'concrete/asset_library'];

    public function __construct(
        Page $c,
        public readonly ActionService $actionService,
    ) {
        parent::__construct($c);
    }

    public function add()
    {
        $this->set('add_edit', t('Add'));
        $this->set('showForm', true);
        $this->view();
    }

    public function view($upaID = null)
    {
        $action = (int) $upaID > 0 ? $this->actionService->getRepository()->find((int) $upaID) : null;
        if ($action) {
            $this->set('add_edit', t('Edit'));
            $this->set('action', $action);
            $this->set('showForm', true);
        }

        $actionList = $this->getActionList();

        $factory = $this->app->make(PaginationFactory::class, [$this->request]);
        $pagination = $factory->createPaginationObject($actionList);

        $this->set('pagination', $pagination);
        $this->set('actionList', $actionList);
        $this->set('actions', $pagination->getCurrentPageResults());
    }

    public function getActionList(): UserPointActionList
    {
        $al = new UserPointActionList($this->getEntityManager());

        if (!$this->request->query->has('ccm_order_by')) {
            $al->sortBy('upa.name', 'ASC');
        }

        return $al;
    }

    public function save()
    {
        if (!$this->token->validate('add_action')) {
            $this->error->add('Invalid Token');
            $this->add();
            return;
        }

        $post = $this->request->request;

        $upaID = (int) $post->get('upaID');
        $action = $upaID > 0 ? $this->actionService->getRepository()->find($upaID) : null;
        if ($action) {
            $action->handle = (string) $post->get('upaHandle');
            $action->name = (string) $post->get('upaName');
            $action->defaultPoints = (int) $post->get('upaDefaultPoints');
            $action->isActive = (int) ($post->get('upaIsActive') ?? 0);
            $this->actionService->em->flush();
        } else {
            $action = new Action(
                $post->get('upaHandle'),
                $post->get('upaName'),
                $post->get('upaDefaultPoints'),
                false,
                $post->get('upaIsActive'),
                0,
                null,
            );
            $this->actionService->add($action);
        }

        return $this->buildRedirect($this->action('action_saved'));
    }

    public function delete($upaID)
    {
        if (!$this->token->validate('delete_action')) {
            $this->error->add('Invalid Token');

            return $this->view();
        }

        $action = $this->actionService->getRepository()->find($upaID);
        if ($action) {
            $this->actionService->em->remove($action);
            $this->actionService->em->flush();
        }

        return $this->buildRedirect($this->action('action_deleted'));
    }

    public function action_deleted()
    {
        $this->set('message', t('Community Point Action Deleted'));
        $this->view();
    }

    public function action_saved()
    {
        $this->set('message', t('Community Point Action Saved'));
        $this->view();
    }
}
