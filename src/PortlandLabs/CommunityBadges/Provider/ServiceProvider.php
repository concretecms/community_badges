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

class ServiceProvider extends Provider
{
    protected $eventDispatcher;
    protected $responseFactory;
    protected $router;

    public function __construct(
        Application $app,
        Router $router
    )
    {
        parent::__construct($app);

        $this->router = $router;
    }

    public function register()
    {
        $this->registerAPI();
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