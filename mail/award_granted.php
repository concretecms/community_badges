<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use PortlandLabs\CommunityBadges\Entity\AwardGrant;

/** @var AwardGrant $grantedAward */

$subject = t('Awarded Granted – %s', $grantedAward->getAward()->getName());

$body = t("Congratulations! You have granted the award %s from the website.", $grantedAward->getAward()->getName());