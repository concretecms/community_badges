<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use PortlandLabs\CommunityBadges\Entity\UserBadge;

/** @var UserBadge $userBadge */

$subject = t('Awarded Award – %s', $userBadge->getBadge()->getName());

if ($userBadge->isGivenBySystem()) {
    $body = t("Congratulations! You awarded the award %s from the website.", $userBadge->getBadge()->getName());
} else {
    $body = t("Congratulations! You awarded the award %s from the user %s.", $userBadge->getBadge()->getName(), $userBadge->getAwardGrant()->getUser()->getUserName());
}