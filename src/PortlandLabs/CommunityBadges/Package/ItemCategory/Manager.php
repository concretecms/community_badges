<?php

namespace PortlandLabs\CommunityBadges\Package\ItemCategory;

use Concrete\Core\Package\ItemCategory\Manager as CoreManager;

class Manager extends CoreManager
{
    public function createUserPointActionDriver()
    {
        return new UserPointAction();
    }
}