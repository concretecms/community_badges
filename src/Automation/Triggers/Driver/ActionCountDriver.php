<?php

declare(strict_types=1);

namespace PortlandLabs\CommunityBadges\Automation\Triggers\Driver;

use Concrete\Core\Filesystem\Element;
use Concrete\Core\User\User;
use Doctrine\DBAL\Connection;
use PortlandLabs\CommunityBadges\Automation\Triggers\Saver\ActionCountSaver;
use PortlandLabs\CommunityBadges\Automation\Triggers\Saver\SaverInterface;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Exceptions\AchievementAlreadyExists;
use PortlandLabs\CommunityBadges\User\Point\Action\Action;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ActionCountDriver extends AbstractDriver
{
    public function __construct(
        private AwardService $awardService,
        private Connection $db,
    ) {}

    public function getConfigurationFormElement(): Element
    {
        return new Element('dashboard/automation/triggers/action_count', 'community_badges');
    }

    public function getSaver(): SaverInterface
    {
        /** @var ActionCountSaver */
        return $this->app->make(ActionCountSaver::class);
    }

    public function getName(): string
    {
        return 'Action Count';
    }

    public function processManually(InputInterface $input, OutputInterface $output): void
    {
        foreach ($this->getAutomatedRulesByDriverHandle('action_count') as $automationRule) {
            $ruleConfig = $automationRule->getConfiguration();

            $action = (int) ($ruleConfig['action'] ?? 0);
            $minCount = max((int) ($ruleConfig['minCount'] ?? 1), 1);
            $actionObject = Action::getByID($action);
            if (!$actionObject) {
                continue;
            }

            $allUsers = $this->db->iterateColumn('SELECT u.uID FROM Users u');
            foreach ($allUsers as $user) {
                if (!$this->userNeedsBadge((int) $user, $action, $minCount)) {
                    continue;
                }

                try {
                    $badge = $automationRule->getBadge();
                    if ($output->isVerbose()) {
                        $output->writeln(sprintf('Awarding badge %s to user %s', $badge->getHandle(), $user));
                    }

                    $this->awardService->giveBadge($automationRule->getBadge(), User::getByUserID((int) $user));
                } catch (AchievementAlreadyExists) {
                    // Ignore
                }
            }
        }
    }

    private function userNeedsBadge(int $userId, int $action, int $minCount): bool
    {
        // We do it this way because it allows the query to return quickly when there are a ton of rows
        $statement = $this->db->executeQuery('select 1 from UserPointHistory where upuID = ? and upaID = ? limit '
        . ($minCount + 1), [$userId, $action]);
        $count = 0;
        foreach ($statement->iterateColumn() as $_) {
            $count++;
        }
        return $count >= $minCount;
    }
}
