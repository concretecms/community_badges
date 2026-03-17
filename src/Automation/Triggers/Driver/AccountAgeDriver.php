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
use Concrete\Core\User\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use PortlandLabs\CommunityBadges\Automation\Triggers\Saver\AccountAgeSaver;
use PortlandLabs\CommunityBadges\Automation\Triggers\Saver\SaverInterface;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Exceptions\AchievementAlreadyExists;
use PortlandLabs\CommunityBadges\Exceptions\InvalidBadgeType;
use PortlandLabs\CommunityBadges\Exceptions\MailTransportError;
use PortlandLabs\CommunityBadges\Exceptions\NoUserSelected;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AccountAgeDriver extends AbstractDriver
{
    public function getConfigurationFormElement(): Element
    {
        return $this->getElement('dashboard/automation/triggers/account_age');
    }

    public function getSaver(): SaverInterface
    {
        /** @var SaverInterface */
        return $this->app->make(AccountAgeSaver::class);
    }

    public function getName(): string
    {
        return t('Account Age');
    }

    public function processManually(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        /** @var AwardService $awardService */
        $awardService = $this->app->make(AwardService::class);

        foreach ($this->getAutomatedRulesByDriverHandle('account_age') as $automationRule) {
            $ruleConfig = $automationRule->configuration;

            if (is_array($ruleConfig) && isset($ruleConfig['accountAge'])) {
                $accountAge = (int) $ruleConfig['accountAge'];

                try {
                    /** @noinspection SqlDialectInspection */
                    /** @noinspection SqlNoDataSourceInspection */
                    $rows = $db->fetchAllAssociative(sprintf(
                        'SELECT uID FROM Users WHERE uDateAdded < DATE_SUB(NOW(), INTERVAL %s YEAR)',
                        $accountAge,
                    ));

                    foreach ($rows as $row) {
                        $uID = (int) $row['uID'];

                        if (!$this->isItemProcessed($automationRule, (string) $uID)) {
                            $user = User::getByUserID($uID);
                            if (!$user instanceof User) {
                                continue;
                            }
                            $awardService->giveBadge($automationRule->badge, $user);

                            $io->success(sprintf(
                                'Awarded badge %s for users %s.',
                                $automationRule->badge->name,
                                $user->getUserName(),
                            ));

                            $this->markItemAsProcessed($automationRule, (string) $uID);
                        }
                    }
                } catch (Exception $e) {
                    $io->error($e->getMessage());
                } catch (AchievementAlreadyExists $e) {
                    $io->error('The achievement was already to the user.');
                } catch (InvalidBadgeType $e) {
                    $io->error('The badge type is invalid.');
                } catch (MailTransportError $e) {
                    $io->error('There was an error while sending the notifaction mail.');
                } catch (NoUserSelected $e) {
                    $io->error('There is no valid user selected.');
                }
            }
        }
    }
}
