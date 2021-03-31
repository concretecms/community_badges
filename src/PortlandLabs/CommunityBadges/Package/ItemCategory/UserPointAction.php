<?php

namespace PortlandLabs\CommunityBadges\Package\ItemCategory;

use Concrete\Core\Entity\Package;
use Concrete\Core\Package\ItemCategory\AbstractCategory;
use PortlandLabs\CommunityBadges\User\Point\Action\Action;

class UserPointAction extends AbstractCategory
{
    public function getItemCategoryDisplayName()
    {
        return t('User Point Actions');
    }

    /**
     * @param Action $action
     * @return mixed
     */
    public function getItemName($action)
    {
        return $action->getUserPointActionName();
    }

    public function getPackageItems(Package $package)
    {
        return Action::getListByPackage($package);
    }
}
