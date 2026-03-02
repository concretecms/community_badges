<?php

use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Application;

/** @var Form $form */
$form = Application::getFacadeApplication()->get(Form::class);
?>
<div class="form-group">
    <?php echo $form->label("action", t("Action")); ?>
    <?= $form->select('action', $actions ?? [], $action ?? null) ?>
</div>

<div class="form-group">
    <?php echo $form->label("minCount", t("Minimum action count")); ?>

    <div class="input-group">
        <?php echo $form->number("minCount", $minCount ?? 1, ["step" => 1, "min" => 1]); ?>

        <div class="input-group-append">
            <div class="input-group-text">
                <?php echo t("Times"); ?>
            </div>
        </div>
    </div>
</div>