<?php

/**
 * @project:   Community Awards
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityAwards\Controller\Element\Awards;

use Concrete\Core\Controller\ElementController;

class Header extends ElementController
{
    protected $pkgHandle = "community_awards";

    public function getElement()
    {
        return 'awards/header';
    }
}
