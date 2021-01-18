<?php

/**
 * @project:   Community Awards
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Support\Facade\Url;
use PortlandLabs\CommunityAwards\Entity\Award;

/** @var Award[] $awards */

?>

<?php if (count($awards) === 0) { ?>
    <div class="alert alert-info">
        <?php echo t("There are currently no awards available."); ?>
    </div>
<?php } else { ?>
    <table class="table table-stripped">
        <thead>
        <tr>
            <th>
                <?php echo t("Name"); ?>
            </th>

            <th>
                &nbsp;
            </th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($awards as $award) { ?>
            <tr>
                <td>
                    <?php echo $award->getName(); ?>
                </td>

                <td>
                    <div class="float-right">
                        <a href="<?php echo (string)Url::to("/dashboard/users/awards/remove", $award->getId()); ?>"
                           class="btn btn-danger">
                            <i class="fas fa-trash"></i> <?php echo t("Remove"); ?>
                        </a>

                        <a href="<?php echo (string)Url::to("/dashboard/users/awards/edit", $award->getId()); ?>"
                           class="btn btn-secondary">
                            <i class="fas fa-pencil-alt"></i> <?php echo t("Edit"); ?>
                        </a>
                    </div>

                    <div class="clearfix"></div>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>
