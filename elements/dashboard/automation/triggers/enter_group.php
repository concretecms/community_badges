<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Form\Service\Form;
use Concrete\Core\Form\Service\Widget\GroupSelector;
use Concrete\Core\Support\Facade\Application;

/** @var int $groupId */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);
/** @var GroupSelector $groupSelector */
$groupSelector = $app->make(GroupSelector::class);

?>

<div class="form-group">
    <?php echo $form->label("groupId", t("Group")); ?>
    <?php $groupSelector->selectGroup("groupId", $groupId); ?>
</div>