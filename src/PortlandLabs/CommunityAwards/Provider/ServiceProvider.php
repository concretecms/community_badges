<?php

/**
 * @project:   Community Awards
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityAwards\Provider;

use Concrete\Core\Application\Application;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Routing\Router;
use PortlandLabs\CommunityAwards\API\V1\CommunityAwards;
use PortlandLabs\CommunityAwards\API\V1\Middleware\FractalNegotiatorMiddleware;

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
                $groupRouter->post('/community_awards/give_award', [CommunityAwards::class, 'giveAward']);
            });
    }
}