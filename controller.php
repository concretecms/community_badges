<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\CommunityBadges;

use Concrete\Core\Application\UserInterface\Dashboard\Navigation\NavigationCache;
use Concrete\Core\Database\EntityManager\Driver\Driver;use Concrete\Core\Database\EntityManager\Provider\ProviderAggregateInterface;
use Concrete\Core\Database\EntityManager\Provider\ProviderInterface;use Concrete\Core\Database\EntityManager\Provider\StandardPackageProvider;
use Concrete\Core\Package\Package;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;use PortlandLabs\CommunityBadges\Console\Command\ProcessAutomatedRules;
use PortlandLabs\CommunityBadges\Provider\ServiceProvider;

class Controller extends Package implements ProviderAggregateInterface
{
    protected $pkgHandle = 'community_badges';
    protected $appVersionRequired = '9.0';
    protected $pkgVersion = '0.0.7';
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

    #[\Override]
    public function getEntityManagerProvider(): ProviderInterface
    {
        return new class($this->app, $this, ['src/Entity' => "\PortlandLabs\CommunityBadges\Entity"]) extends
            StandardPackageProvider {
            #[\Override]
            public function getDrivers(): array
            {
                $drivers = [];
                $pkgPath = $this->pkg->getPackagePath();

                foreach ($this->locations as $path => $prefix) {
                    $prefix = trim((string) $prefix, '\\');
                    $drivers[] = new Driver($prefix, new AttributeDriver([$pkgPath . '/' . $path]));
                }

                return $drivers;
            }
        };
    }
}
