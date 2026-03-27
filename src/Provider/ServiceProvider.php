<?php

declare(strict_types=1);

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Provider;

use Concrete\Core\Events\EventDispatcher;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Http\Middleware\OAuthAuthenticationMiddleware;
use Concrete\Core\Routing\RouteGroupBuilder;
use Concrete\Core\Routing\Router;
use Concrete\Core\Search\Field\Group;
use Concrete\Core\Search\Field\GroupInterface;
use Concrete\Core\User\Search\Field\Manager as UserManager;
use PortlandLabs\CommunityBadges\API\V1\CommunityBadges;
use PortlandLabs\CommunityBadges\API\V1\Middleware\FractalNegotiatorMiddleware;
use PortlandLabs\CommunityBadges\Automation\Triggers\Driver\Manager;
use PortlandLabs\CommunityBadges\Entity\Point\ActionService;
use PortlandLabs\CommunityBadges\Entity\Point\ActionServiceInterface;
use PortlandLabs\CommunityBadges\User\Search\Field\Field\AchievementField;

class ServiceProvider extends Provider
{
    public function register()
    {
        $this->registerAPI();
        $this->registerAutomationManager();
        $this->registerSearchFields();
        $this->registerEntities();

        $this->app->bind(
            \Concrete\Core\Package\ItemCategory\Manager::class,
            \PortlandLabs\CommunityBadges\Package\ItemCategory\Manager::class,
        );
    }

    protected function registerSearchFields()
    {
        $this->app->extend('manager/search_field/user', static function (UserManager $manager) {
            $group = $manager->getGroupByName('Core Properties');
            if ($group instanceof Group) {
                $group->addField(new AchievementField());
            }
            return $manager;
        });
    }

    protected function registerAutomationManager()
    {
        $this->app->singleton(Manager::class);
        $app = $this->app;

        $this->app->extend(EventDispatcher::class, static function (EventDispatcher $dispatcher) use ($app) {
            $dispatcher->getEventDispatcher()->addListener('on_start', static function () use ($app) {
                $app->get(Manager::class)?->register();
            });
            return $dispatcher;
        });
    }

    protected function registerAPI()
    {
        $this->app->extend(Router::class, static function (Router $router) {
            /** @var RouteGroupBuilder $group */
            $group = $router->buildGroup();
            $group
                ->setPrefix('/api/v1')
                ->addMiddleware(FractalNegotiatorMiddleware::class)
                ->routes(static function (Router $groupRouter) {
                    /** @noinspection PhpParamsInspection */
                    /** @mago-expect analysis:invalid-argument */
                    $groupRouter->post('/community_badges/give_award', [CommunityBadges::class, 'giveAward']);
                    /** @noinspection PhpParamsInspection */
                    /** @mago-expect analysis:invalid-argument */
                    $groupRouter->post('/community_badges/dismiss_grant_award', [
                        CommunityBadges::class,
                        'dismissGrantAward',
                    ]);
                });

            /** @var RouteGroupBuilder $group */
            $group = $router->buildGroup();
            $group
                ->setPrefix('/api/v1')
                ->addMiddleware(OAuthAuthenticationMiddleware::class)
                ->addMiddleware(FractalNegotiatorMiddleware::class)
                ->routes(static function (Router $groupRouter) {
                    /** @noinspection PhpParamsInspection */
                    /** @mago-expect analysis:invalid-argument */
                    $groupRouter->get('/community_badges', [CommunityBadges::class, 'getList']);
                });
            return $router;
        });
    }

    protected function registerEntities(): void
    {
        $this->app->bind(ActionServiceInterface::class, ActionService::class);
    }
}
