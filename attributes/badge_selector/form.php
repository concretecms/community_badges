<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Application;
use PortlandLabs\CommunityBadges\AwardService;

/** @var string $value */

$app = Application::getFacadeApplication();
/** @var $form Form */
$form = $app->make(Form::class);
/** @var $awardService AwardService */
$awardService = $app->make(AwardService::class);

echo $form->select($this->field('value'), $awardService->getBadgeList(), $value);
