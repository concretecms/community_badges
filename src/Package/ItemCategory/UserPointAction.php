<?php

declare(strict_types=1);

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
     * @param Action $mixed
     * @return string
     */
    public function getItemName($mixed)
    {
        return $mixed->getUserPointActionName();
    }
}
