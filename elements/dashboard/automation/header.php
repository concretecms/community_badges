<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

use Concrete\Core\Support\Facade\Url;

defined('C5_EXECUTE') or die('Access denied');

/** @var array $driverList */
?>
<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="triggerDropdownMenuButton" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
        <?php echo t("Add Automation Rule"); ?>
    </button>

    <div class="dropdown-menu" aria-labelledby="triggerDropdownMenuButton">
        <?php foreach ($driverList as $driverHandle => $driverName) { ?>
            <a class="dropdown-item"
               href="<?php echo (string)Url::to("/dashboard/system/community_badges/automation/add", $driverHandle); ?>">
                <?php echo $driverName; ?>
            </a>
        <?php } ?>
    </div>
</div>
