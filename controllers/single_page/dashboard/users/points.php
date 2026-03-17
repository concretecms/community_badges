<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityBadges\Controller\SinglePage\Dashboard\Users;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Page\Page;
use Concrete\Core\Search\Pagination\PaginationFactory;
use Doctrine\ORM\EntityManager;
use PortlandLabs\CommunityBadges\Entity\Point\ActionService;
use PortlandLabs\CommunityBadges\Entity\Point\TimelineEntry;
use PortlandLabs\CommunityBadges\User\Point\Action\ActionDescriptionInterface;
use PortlandLabs\CommunityBadges\User\Point\EntryList as UserPointEntryList;

class Points extends DashboardPageController
{
    public $helpers = ['form', 'concrete/ui', 'concrete/urls', 'image', 'concrete/asset_library', 'form/user_selector'];

    public function __construct(
        Page $c,
        protected ActionService $actionService,
    ) {
        parent::__construct($c);
    }

    public function view()
    {
        assert($this->request !== null);
        $upEntryList = $this->getEntries();
        $factory = new PaginationFactory($this->request);
        $pagination = $factory->createPaginationObject($upEntryList);

        $this->set('pagination', $pagination);
        $this->set('upEntryList', $upEntryList);
        $this->set('entries', $pagination->getCurrentPageResults());
        $this->set('dh', $this->app->make('date'));
    }

    public function getEntries(): UserPointEntryList
    {
        assert($this->entityManager instanceof EntityManager);
        $entries = new UserPointEntryList($this->entityManager);
        $entries->setItemsPerPage(100);

        if ($uID = $this->request->get('uID')) {
            $entries->filterByUserID($uID);
        }

        if ($uName = $this->request->get('uName')) {
            $entries->filterByUserName($uName);
        }

        $upaName = (string) $this->request->get('upaName', '');
        if ($upaName !== '') {
            $entries->filterByUserPointActionName($upaName);
        }

        if (!$this->request->query->has('ccm_order_by')) {
            $entries->sortBy('uph.date', 'desc');
        }

        return $entries;
    }

    public function deleteEntry($upID)
    {
        if (!$this->token->validate('delete_community_points')) {
            $this->error->add('Invalid Token');

            return $this->view();
        }

        $action = $this->actionService->getRepository()->find((int) $upID);
        if ($action) {
            $this->actionService->em->remove($action);
            $this->actionService->em->flush();
        }

        return $this->buildRedirect($this->action('entry_deleted'));
    }

    public function entry_deleted()
    {
        $this->set('message', t('User Point Entry Deleted'));
        $this->view();
    }

    public function getEntryDescription(TimelineEntry $entry): string
    {
        $description = $this->actionService->getController($entry->action)->getActionDescriptionObject($entry);
        if ($description instanceof ActionDescriptionInterface) {
            return $description->getUserPointActionDescription();
        }

        return '';
    }
}
