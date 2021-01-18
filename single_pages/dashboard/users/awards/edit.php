<?php

/**
 * @project:   Community Awards
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Application\Service\FileManager;
use Concrete\Core\Editor\EditorInterface;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Form\Service\Widget\GroupSelector;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Validation\CSRF\Token;
use PortlandLabs\CommunityAwards\Entity\Award;

/** @var Award $award */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);
/** @var GroupSelector $groupSelector */
$groupSelector = $app->make(GroupSelector::class);
/** @var FileManager $fileManager */
$fileManager = $app->make(FileManager::class);
/** @var EditorInterface $editor */
$editor = $app->make(EditorInterface::class);
/** @var Token $token */
$token = $app->make(Token::class);

?>

<form action="#" method="post">
    <?php echo $token->output("create_award"); ?>

    <div class="form-group">
        <?php echo $form->label("name", t("Name")); ?>
        <?php echo $form->text("name",  $award->getName()); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("description", t("Description")); ?>
        <?php echo $editor->outputStandardEditor("description",  $award->getDescription()); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("thumbnail", t("Thumbnail")); ?>
        <?php echo $fileManager->image("thumbnail", "thumbnail", t("Please Select"), $award->getThumbnail()); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("groupId", t("Group")); ?>
        <?php $groupSelector->selectGroup("groupId", $award->getGroupId()); ?>
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
