<?php

/**
 * @project:   Community Awards
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityAwards;

use Concrete\Core\Application\UserInterface\Dashboard\Navigation\NavigationCache;
use Concrete\Core\Database\EntityManager\Provider\ProviderAggregateInterface;
use Concrete\Core\Database\EntityManager\Provider\StandardPackageProvider;
use Concrete\Core\Package\Package;
use PortlandLabs\CommunityAwards\Provider\ServiceProvider;

class Controller extends Package implements ProviderAggregateInterface
{
    protected $pkgHandle = 'community_awards';
    protected $appVersionRequired = '9.0';
    protected $pkgVersion = '0.0.1';
    protected $pkgAutoloaderRegistries = [
        'src/PortlandLabs/CommunityAwards' => 'PortlandLabs\CommunityAwards',
    ];

    public function getPackageDescription()
    {
        return t("Integrate the community awards feature awards into your site.");
    }

    public function getPackageName()
    {
        return t("Community Awards");
    }

    public function getEntityManagerProvider()
    {
        return new StandardPackageProvider($this->app, $this, [
            'src/PortlandLabs/CommunityAwards/Entity' => 'PortlandLabs\CommunityAwards\Entity'
        ]);
    }

    public function on_start()
    {
        /** @var ServiceProvider $serviceProvider */
        $serviceProvider = $this->app->make(ServiceProvider::class);
        $serviceProvider->register();
    }

    public function install()
    {
        $pkg = parent::install();
        $this->installContentFile("data.xml");
        /** @var NavigationCache $navigationCache */
        $navigationCache = $this->app->make(NavigationCache::class);
        $navigationCache->clear();
        return $pkg;
    }

}
