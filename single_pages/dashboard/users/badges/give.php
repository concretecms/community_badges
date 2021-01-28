<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Form\Service\Form;
use Concrete\Core\Form\Service\Widget\UserSelector;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\Validation\CSRF\Token;

/** @var array $badgeList */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);
/** @var UserSelector $userSelector */
$userSelector = $app->make(UserSelector::class);
/** @var Token $token */
$token = $app->make(Token::class);

?>

<form action="<?php echo(string)Url::to("/dashboard/users/badges/give"); ?>" method="post">
    <?php echo $token->output("give_award"); ?>

    <div class="form-group">
        <?php echo $form->label("badge", t("Badge")); ?>
        <?php echo $form->select("badge", $badgeList); ?>

        <p class="help-block">
            <?php echo t("Please select an award or achievement from the list above."); ?>
        </p>
    </div>

    <div class="form-group">
        <?php echo $form->label("user", t("User")); ?>
        <?php echo $userSelector->selectUser("user"); ?>
    </div>

    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <div class="float-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> <?php echo t("Save"); ?>
                </button>
            </div>
        </div>
    </div>
</form>
