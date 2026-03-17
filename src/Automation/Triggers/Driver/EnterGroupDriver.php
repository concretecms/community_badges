<?php

declare(strict_types=1);

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Automation\Triggers\Driver;

use Concrete\Core\Filesystem\Element;
use Concrete\Core\User\Event\UserGroup;
use Concrete\Core\User\Group\Group;
use Concrete\Core\User\User;
use Exception;
use PortlandLabs\CommunityBadges\Automation\Triggers\Saver\EnterGroupSaver;
use PortlandLabs\CommunityBadges\Automation\Triggers\Saver\SaverInterface;
use PortlandLabs\CommunityBadges\AwardService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EnterGroupDriver extends AbstractDriver
{
    public function getConfigurationFormElement(): Element
    {
        return $this->getElement('dashboard/automation/triggers/enter_group');
    }

    public function getSaver(): SaverInterface
    {
        /** @var EnterGroupSaver */
        return $this->app->make(EnterGroupSaver::class);
    }

    public function getName(): string
    {
        return t('Enter Group');
    }

    public function register(): void
    {
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->app->make(EventDispatcherInterface::class);
        /** @var AwardService $awardService */
        $awardService = $this->app->make(AwardService::class);

        $eventDispatcher->addListener('on_user_enter_group', function (UserGroup $event) use ($awardService) {
            /** @var mixed $group */
            $group = $event->getGroupObject();
            /** @var User $user */
            $user = $event->getUserObject();

            if ($group instanceof Group) {
                foreach ($this->getAutomatedRulesByDriverHandle('enter_group') as $automationRule) {
                    $ruleConfig = $automationRule->configuration;
                    if (is_array($ruleConfig) && isset($ruleConfig['groupId'])) {
                        $groupId = (int) $ruleConfig['groupId'];

                        if ($groupId === $group->getGroupID()) {
                            try {
                                $awardService->giveBadge($automationRule->badge, $user);
                            } catch (Exception $exception) {
                                // Ignore all kind of exceptions.
                            }
                        }
                    }
                }
            }
        });
    }
}
