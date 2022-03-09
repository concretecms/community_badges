<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Provider;

use Concrete\Core\Application\Application;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Http\Middleware\OAuthAuthenticationMiddleware;
use Concrete\Core\Routing\Router;
use PortlandLabs\CommunityBadges\API\V1\CommunityBadges;
use PortlandLabs\CommunityBadges\API\V1\Middleware\FractalNegotiatorMiddleware;
use PortlandLabs\CommunityBadges\Automation\Triggers\Driver\Manager;
use PortlandLabs\CommunityBadges\User\Search\Field\Field\AchievementField;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ServiceProvider extends Provider
{
    protected $eventDispatcher;
    protected $responseFactory;
    protected $router;

    public function __construct(
        Application $app,
        EventDispatcherInterface $eventDispatcher,
        Router $router
    )
    {
        parent::__construct($app);

        $this->eventDispatcher = $eventDispatcher;
        $this->router = $router;
    }

    public function register()
    {
        $this->registerAPI();
        $this->registerAutomationManager();
        $this->registerSearchFields();

        $this->app->bind(\Concrete\Core\Package\ItemCategory\Manager::class, \PortlandLabs\CommunityBadges\Package\ItemCategory\Manager::class);
    }

    protected function registerSearchFields()
    {
//         $manager = $this->app->make('manager/search_field/user');
//         $manager->getGroupByName('Core Properties')->addField(new AchievementField());
    }

    protected function registerAutomationManager()
    {
        $this->app->singleton(Manager::class);
        /** @var Manager $driverManager */
        $driverManager = $this->app->make(Manager::class);

        $this->eventDispatcher->addListener("on_start", function () use ($driverManager) {
            $driverManager->register();
        });
    }

    protected function registerAPI()
    {
        $this->router->buildGroup()
            ->setPrefix('/api/v1')
            ->addMiddleware(FractalNegotiatorMiddleware::class)
            ->routes(function ($groupRouter) {
                /**
                 * @var $groupRouter Router
                 */

                /** @noinspection PhpParamsInspection */
                $groupRouter->post('/community_badges/give_award', [CommunityBadges::class, 'giveAward']);
                $groupRouter->post('/community_badges/dismiss_grant_award', [CommunityBadges::class, 'dismissGrantAward']);

            });
        $this->router->buildGroup()
            ->setPrefix('/api/v1')
            ->addMiddleware(OAuthAuthenticationMiddleware::class)
            ->addMiddleware(FractalNegotiatorMiddleware::class)
            ->routes(function ($groupRouter) {
                /**
                 * @var $groupRouter Router
                 */

                /** @noinspection PhpParamsInspection */
                $groupRouter->get('/community_badges', [CommunityBadges::class, 'getList']);
            });
    }
}
