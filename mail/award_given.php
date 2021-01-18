<?php

/**
 * @project:   Community Awards
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use PortlandLabs\CommunityAwards\Entity\AwardedAward;

/** @var AwardedAward $awardedAward */

$subject = t('Awarded Award – %s', $awardedAward->getAward()->getName());

if ($awardedAward->isGivenBySystem()) {
    $body = t("Congratulations! You awarded the award %s from the website.", $awardedAward->getAward()->getName());
} else {
    $body = t("Congratulations! You awarded the award %s from the user %s.", $awardedAward->getAward()->getName(), $awardedAward->getAwardGrant()->getUser()->getUserName());
}