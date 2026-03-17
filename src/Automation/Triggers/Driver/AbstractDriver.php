<?php

declare(strict_types=1);

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Automation\Triggers\Driver;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Filesystem\Element;
use Doctrine\ORM\EntityManagerInterface;
use PortlandLabs\CommunityBadges\Entity\AutomationRule;
use PortlandLabs\CommunityBadges\Entity\AutomationRuleProcessedItem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractDriver implements DriverInterface, ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @param string $driverHandle
     * @return AutomationRule[]
     */
    protected function getAutomatedRulesByDriverHandle(string $driverHandle): iterable
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->app->make(EntityManagerInterface::class);
        return $entityManager->getRepository(AutomationRule::class)->findBy(['driverHandle' => $driverHandle]);
    }

    protected function markItemAsProcessed(AutomationRule $automationRule, string $key)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->app->make(EntityManagerInterface::class);
        $automationRuleProcessedItem = new AutomationRuleProcessedItem($automationRule, $key);
        $entityManager->persist($automationRuleProcessedItem);
        $entityManager->flush();
    }

    protected function isItemProcessed(AutomationRule $automationRule, string $key): bool
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->app->make(EntityManagerInterface::class);

        return $entityManager
            ->getRepository(AutomationRuleProcessedItem::class)
            ->findOneBy([
                'automationRule' => $automationRule,
                'key' => $key,
            ]) instanceof AutomationRuleProcessedItem;
    }

    public function processManually(InputInterface $input, OutputInterface $output): void
    {
        return;
    }

    public function register(): void
    {
        return;
    }

    protected function getElement(string $name, string $pkgName = 'community_badges'): Element
    {
        /** @mago-expect analysis:too-many-arguments */
        return new Element($name, $pkgName);
    }
}
