<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Automation\Triggers\Driver;

use Concrete\Core\Filesystem\Element;
use Concrete\Core\User\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use PortlandLabs\CommunityBadges\Automation\Triggers\Saver\CommunityPointsSaver;
use PortlandLabs\CommunityBadges\Automation\Triggers\Saver\SaverInterface;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Exceptions\AchievementAlreadyExists;
use PortlandLabs\CommunityBadges\Exceptions\InvalidBadgeType;
use PortlandLabs\CommunityBadges\Exceptions\MailTransportError;
use PortlandLabs\CommunityBadges\Exceptions\NoUserSelected;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CommunityPointsDriver extends AbstractDriver
{
    public function getConfigurationFormElement(): Element
    {
        return new Element('dashboard/automation/triggers/community_points', 'community_badges');
    }

    public function getSaver(): SaverInterface
    {
        return $this->app->make(CommunityPointsSaver::class);
    }

    public function getName(): string
    {
        return t("Community Points");
    }

    public function processManually(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        /** @var AwardService $awardService */
        $awardService = $this->app->make(AwardService::class);

        foreach ($this->getAutomatedRulesByDriverHandle("community_points") as $automationRule) {
            $ruleConfig = $automationRule->getConfiguration();

            if (is_array($ruleConfig) && isset($ruleConfig["communityPoints"])) {
                $communityPoints = $ruleConfig["communityPoints"];

                try {
                    /** @noinspection SqlDialectInspection */
                    /** @noinspection SqlNoDataSourceInspection */
                    $rows = $db->fetchAllAssociative(sprintf(
                        "SELECT s.*
FROM (
  SELECT u.uID, SUM(p.upPoints) AS points_sum
  FROM Users u JOIN UserPointHistory p ON (u.uID = p.upuID)
  GROUP BY u.uID) AS s
WHERE s.points_sum >= %s
ORDER BY s.uID
LIMIT 1;",
                        (int)$communityPoints
                    ));

                    /** @noinspection DuplicatedCode */
                    foreach ($rows as $row) {
                        $uID = $row["uID"];

                        if (!$this->isItemProcessed($automationRule, $uID)) {
                            $user = User::getByUserID($uID);
                            $awardService->giveBadge($automationRule->getBadge(), $user);

                            $io->success(sprintf("Awarded badge %s for users %s.", $automationRule->getBadge()->getName(), $user->getUserName()));

                            $this->markItemAsProcessed($automationRule, (string)$uID);
                        }
                    }

                } catch (Exception $e) {
                    $io->error($e->getMessage());
                } catch (AchievementAlreadyExists $e) {
                    $io->error("The achievement was already to the user.");
                } catch (InvalidBadgeType $e) {
                    $io->error("The badge type is invalid.");
                } catch (MailTransportError $e) {
                    $io->error("There was an error while sending the notifaction mail.");
                } catch (NoUserSelected $e) {
                    $io->error("There is no valid user selected.");
                }
            }
        }
    }
}