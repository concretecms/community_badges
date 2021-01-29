<?php

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
use PortlandLabs\CommunityBadges\Automation\Triggers\Saver\LeaveGroupSaver;
use PortlandLabs\CommunityBadges\Automation\Triggers\Saver\SaverInterface;
use PortlandLabs\CommunityBadges\AwardService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Exception;

class LeaveGroupDriver extends AbstractDriver
{
    public function getConfigurationFormElement(): Element
    {
        return new Element('dashboard/automation/triggers/leave_group', 'community_badges');
    }

    public function getSaver(): SaverInterface
    {
        return $this->app->make(LeaveGroupSaver::class);
    }

    public function getName(): string
    {
        return t("Leave Group");
    }

    public function register(): void
    {
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->app->make(EventDispatcherInterface::class);
        /** @var AwardService $awardService */
        $awardService = $this->app->make(AwardService::class);

        $eventDispatcher->addListener("on_user_exit_group", function ($event) use ($awardService) {
            /** @var UserGroup $event */
            $group = $event->getGroupObject();
            $user = $event->getUserObject();

            if ($group instanceof Group) {
                foreach ($this->getAutomatedRulesByDriverHandle("leave_group") as $automationRule) {
                    $ruleConfig = $automationRule->getConfiguration();
                    if (is_array($ruleConfig) && isset($ruleConfig["groupId"])) {
                        $groupId = (int)$ruleConfig["groupId"];

                        if ($groupId == $group->getGroupID()) {
                            try {
                                $awardService->giveBadge($automationRule->getBadge(), $user);
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