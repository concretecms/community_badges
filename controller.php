<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityBadges;

use Concrete\Core\Application\UserInterface\Dashboard\Navigation\NavigationCache;
use Concrete\Core\Database\EntityManager\Provider\ProviderAggregateInterface;
use Concrete\Core\Database\EntityManager\Provider\StandardPackageProvider;
use Concrete\Core\Package\Package;
use PortlandLabs\CommunityBadges\Console\Command\ProcessAutomatedRules;
use PortlandLabs\CommunityBadges\Provider\ServiceProvider;

class Controller extends Package implements ProviderAggregateInterface
{
    protected $pkgHandle = 'community_badges';
    protected $appVersionRequired = '9.0';
    protected $pkgVersion = '0.0.3';
    protected $pkgAutoloaderRegistries = [
        'src/PortlandLabs/CommunityBadges' => 'PortlandLabs\CommunityBadges',
    ];

    public function getPackageDescription()
    {
        return t("Integrate the community awards feature awards into your site.");
    }

    public function getPackageName()
    {
        return t("Community Badges");
    }

    public function getEntityManagerProvider()
    {
        return new StandardPackageProvider($this->app, $this, [
            'src/PortlandLabs/CommunityBadges/Entity' => 'PortlandLabs\CommunityBadges\Entity'
        ]);
    }

    public function on_start()
    {
        /** @var ServiceProvider $serviceProvider */
        $serviceProvider = $this->app->make(ServiceProvider::class);
        $serviceProvider->register();

        if ($this->app->isRunThroughCommandLineInterface()) {
            $console = $this->app->make('console');
            $console->add(new ProcessAutomatedRules());
        }
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

    public function upgrade()
    {
        parent::upgrade();
        $this->installContentFile("data.xml");
        $navigationCache = $this->app->make(NavigationCache::class);
        $navigationCache->clear();
    }

}
