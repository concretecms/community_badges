<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

/** @var AutomationRule[] $automationRules */

use Concrete\Core\Support\Facade\Url;
use PortlandLabs\CommunityBadges\Entity\AutomationRule;

?>

<?php if (count($automationRules) === 0) { ?>
    <p>
        <?php echo t("There are currently no automation rules available."); ?>
    </p>
<?php } else { ?>
    <table class="table table-striped">
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
        <?php foreach ($automationRules as $automationRule) { ?>
            <tr>
                <td>
                    <?php echo $automationRule->getName(); ?>
                </td>

                <td>
                    <div class="float-end">
                        <a href="<?php echo (string)Url::to("/dashboard/system/community_badges/automation/remove", $automationRule->getId()); ?>"
                           class="btn btn-danger">
                            <i class="fas fa-trash"></i> <?php echo t("Remove"); ?>
                        </a>

                        <a href="<?php echo (string)Url::to("/dashboard/system/community_badges/automation/edit", $automationRule->getId()); ?>"
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