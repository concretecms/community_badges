<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Support\Facade\Url;
use PortlandLabs\CommunityBadges\Entity\Achievement;
use PortlandLabs\CommunityBadges\Entity\Award;

/** @var Award[] $awards */
/** @var Achievement[] $achievements */
?>

<h3>
    <?php echo t("Awards"); ?>
</h3>

<?php if (count($awards) === 0) { ?>
    <p>
        <?php echo t("There are currently no awards available."); ?>
    </p>
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
                    <div class="float-end">
                        <a href="<?php echo (string)Url::to("/dashboard/users/badges/remove", $award->getId()); ?>"
                           class="btn btn-danger">
                            <i class="fas fa-trash"></i> <?php echo t("Remove"); ?>
                        </a>

                        <a href="<?php echo (string)Url::to("/dashboard/users/badges/edit", $award->getId()); ?>"
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

<hr>


<h3>
    <?php echo t("Achievements"); ?>
</h3>

<?php if (count($achievements) === 0) { ?>
    <p>
        <?php echo t("There are currently no achievements available."); ?>
    </p>
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
        <?php foreach ($achievements as $achievement) { ?>
            <tr>
                <td>
                    <?php echo $achievement->getName(); ?>
                </td>

                <td>
                    <div class="float-end">
                        <a href="<?php echo (string)Url::to("/dashboard/users/badges/remove", $achievement->getId()); ?>"
                           class="btn btn-danger">
                            <i class="fas fa-trash"></i> <?php echo t("Remove"); ?>
                        </a>

                        <a href="<?php echo (string)Url::to("/dashboard/users/badges/edit", $achievement->getId()); ?>"
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
