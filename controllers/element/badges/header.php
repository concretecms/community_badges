<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityBadges\Controller\Element\Badges;

use Concrete\Core\Controller\ElementController;

class Header extends ElementController
{
    protected $pkgHandle = "community_badges";

    public function getElement()
    {
        return 'badges/header';
    }
}
