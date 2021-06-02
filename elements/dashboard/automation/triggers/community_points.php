<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Application;

/** @var int $communityPoints */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);

?>

<div class="form-group">
    <?php echo $form->label("communityPoints", t("Community Points")); ?>
    <?php echo $form->number("communityPoints", $communityPoints, ["step" => 1, "min" => 0]); ?>
</div>