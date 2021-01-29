<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\CommunityBadges\Automation\Triggers\Driver;

use Concrete\Core\Filesystem\Element;
use PortlandLabs\CommunityBadges\Automation\Triggers\Saver\SaverInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface DriverInterface
{
    public function getConfigurationFormElement(): Element;

    public function getSaver(): SaverInterface;

    public function getName(): string;

    /**
     * This is required for all non-event-driven triggers.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function processManually(InputInterface $input, OutputInterface $output): void;

    /**
     * In this method you can hook into events to process automated rules.
     */
    public function register(): void;
}
