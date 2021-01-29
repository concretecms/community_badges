<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Support\Facade\Url;
use Concrete\Core\Validation\CSRF\Token;
use PortlandLabs\CommunityBadges\Automation\Triggers\Driver\DriverInterface;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Application;
use PortlandLabs\CommunityBadges\Entity\AutomationRule;
use PortlandLabs\CommunityBadges\Entity\Badge;

/** @var AutomationRule $automationRule */
/** @var array $badgeList */
/** @var string $driverHandle */
/** @var DriverInterface $driver */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);
/** @var Token $token */
$token = $app->make(Token::class);
?>

<form action="#" method="post">
    <?php echo $token->output("save_automation_rule"); ?>

    <div class="form-group">
        <?php echo $form->label("driverName", t("Trigger")); ?>
        <?php echo $form->text("driverName", $driver->getName(), ["readonly" => "readonly"]); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("name", t("Name")); ?>
        <?php echo $form->text("name", $automationRule->getName()); ?>
    </div>

    <?php
    $element = $driver->getConfigurationFormElement();
    $element->getElementController()->setAutomationRule($automationRule);
    $element->render();
    ?>

    <div class="form-group">
        <?php echo $form->label("badgeId", t("Badge")); ?>
        <?php echo $form->select("badgeId", $badgeList, $automationRule->getBadge() instanceof Badge ? $automationRule->getBadge()->getId() : null); ?>
    </div>

    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions ">
            <a href="<?php echo (string)Url::to('/dashboard/system/community_badges/automation') ?>"
               class="btn btn-secondary float-left">
                <?php echo t("Cancel") ?>
            </a>

            <button type="submit" class="btn btn-primary float-right">
                <?php echo t('Edit Automation Rule') ?>
            </button>
        </div>
    </div>
</form>