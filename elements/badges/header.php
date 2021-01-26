<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Support\Facade\Url;

?>

<div class="ccm-dashboard-header-buttons">
    <a href="<?php echo (string)Url::to("/dashboard/users/badges/add"); ?>" class="btn btn-primary">
        <i class="fa fa-plus"></i> <?php echo t("Add Badge"); ?>
    </a>
</div>