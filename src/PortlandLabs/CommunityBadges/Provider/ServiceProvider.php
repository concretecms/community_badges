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
use Concrete\Core\Routing\Router;
use PortlandLabs\CommunityBadges\API\V1\CommunityBadges;
use PortlandLabs\CommunityBadges\API\V1\Middleware\FractalNegotiatorMiddleware;
use PortlandLabs\CommunityBadges\Automation\Triggers\Driver\Manager;
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
            });
    }
}