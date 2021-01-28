<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Application\Service\FileManager;
use Concrete\Core\Editor\EditorInterface;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Validation\CSRF\Token;
use PortlandLabs\CommunityBadges\Entity\Badge;

/** @var bool $isNew */
/** @var array $badgeTypes */
/** @var string $badgeType */
/** @var Badge $badge */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);
/** @var FileManager $fileManager */
$fileManager = $app->make(FileManager::class);
/** @var EditorInterface $editor */
$editor = $app->make(EditorInterface::class);
/** @var Token $token */
$token = $app->make(Token::class);

?>

<form action="#" method="post">
    <?php echo $token->output("create_award"); ?>

    <?php if ($isNew) { ?>
        <div class="form-group">
            <?php echo $form->label("badgeType", t("Type")); ?>
            <?php echo $form->select("badgeType", $badgeTypes, $badgeType); ?>
        </div>
    <?php } ?>

    <div class="form-group">
        <?php echo $form->label("name", t("Name")); ?>
        <?php echo $form->text("name", $badge->getName()); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("description", t("Description")); ?>
        <?php echo $editor->outputStandardEditor("description", $badge->getDescription()); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("thumbnail", t("Thumbnail")); ?>
        <?php echo $fileManager->image("thumbnail", "thumbnail", t("Please Select"), $badge->getThumbnail()); ?>
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
