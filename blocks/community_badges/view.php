<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Entity\File\File;
use Concrete\Core\Entity\File\Version;
use Concrete\Core\Entity\Package as PackageEntity;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Form\Service\Widget\UserSelector;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\User\User;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Utility\Service\Identifier;
use Concrete\Package\CommunityBadges\Controller;
use HtmlObject\Element;
use HtmlObject\Image;
use PortlandLabs\CommunityBadges\Entity\Badge;
use PortlandLabs\CommunityBadges\Entity\UserBadge;
use PortlandLabs\CommunityBadges\Entity\AwardGrant;

/** @var bool $isOwnProfile */
/** @var AwardGrant[] $grantedAwards */
/** @var UserBadge[] $certifications */
/** @var UserBadge[] $badges */

$app = Application::getFacadeApplication();
/** @var Identifier $idHelper */
$idHelper = $app->make(Identifier::class);
/** @var Form $form */
$form = $app->make(Form::class);
/** @var UserSelector $userSelector */
$userSelector = $app->make(UserSelector::class);
/** @var PackageService $packageService */
$packageService = $app->make(PackageService::class);
/** @var PackageEntity $packageEntity */
$packageEntity = $packageService->getByHandle("community_badges");
/** @var Controller $package */
$package = $packageEntity->getController();
$user = new User();
?>

<div class="public-profile" style="margin-top: 0">
    <?php if ($isOwnProfile && count($grantedAwards) > 0) { ?>
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <span>
                        <?php echo t("Awards"); ?>
                    </span>
                </div>
            </div>

            <div class="card-text">
                <div class="row">
                    <div class="col">
                        <div class="profile-badges">
                            <?php foreach ($grantedAwards as $grantedAward) { ?>
                                <?php
                                $id = "give-award-" . $idHelper->getString();
                                ?>
                                <div class="profile-badge">
                                    <a href="javascript:void(0);"
                                       title="<?php echo h(t("Click here to give this award to another user.")); ?>"
                                       class="give-award"
                                       data-toggle="modal" data-target="#<?php echo $id; ?>"
                                    >
                                        <div class="profile-badge">
                                            <?php
                                            $badgeUrl = $package->getRelativePath() . "/images/default_badge.png";

                                            $grantedAwardEntry = $grantedAward["grantedAward"];

                                            if ($grantedAwardEntry instanceof AwardGrant) {
                                                if ($grantedAwardEntry->getAward() instanceof Badge) {
                                                    $badgeThumbnail = $grantedAwardEntry->getAward()->getThumbnail();
                                                    if ($badgeThumbnail instanceof File) {
                                                        $badgeThumbnailVersion = $badgeThumbnail->getApprovedVersion();
                                                        if ($badgeThumbnailVersion instanceof Version) {
                                                            $badgeUrl = $badgeThumbnailVersion->getURL();
                                                        }
                                                    }
                                                }
                                            }

                                            $imageElement = new Image($badgeUrl, $grantedAwardEntry->getAward()->getName());
                                            $imageElement->setAttribute("title", $grantedAwardEntry->getAward()->getName());

                                            if ($grantedAward["count"] > 1) {
                                                $imageWrapper = new Element("div");
                                                $imageWrapper->addClass("badge-container");
                                                /** @noinspection PhpParamsInspection */
                                                $imageWrapper->appendChild($imageElement);
                                                $imageWrapper->appendChild(new Element("div", $grantedAward["count"], ["class" => "badge-counter", "style" => "margin: 0;"]));
                                                echo $imageWrapper;
                                            } else {
                                                echo $imageElement;
                                            }

                                            ?>
                                        </div>
                                    </a>
                                </div>

                                <div class="modal community-award-modal" tabindex="-1" role="dialog"
                                     id="<?php echo $id; ?>">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <?php echo t("Give Award"); ?>
                                                </h5>
                                            </div>

                                            <div class="modal-body">
                                                <?php echo $form->hidden("grantedAwardId", $grantedAwardEntry->getId()); ?>

                                                <div class="form-group">
                                                    <?php echo $form->label("user", t("User")); ?>
                                                    <?php echo $userSelector->quickSelect("user"); ?>

                                                    <div class="help-block">
                                                        <?php echo t("Enter the username of the user that you want to give that award."); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    <?php echo t("Cancel"); ?>
                                                </button>

                                                <button type="button" class="btn btn-primary">
                                                    <?php echo t("Give Award"); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if (count($badges) > 0) { ?>
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <span>
                        <?php echo t("Achievements"); ?>
                    </span>

                    <?php if ($isOwnProfile) { ?>
                        <a href="<?php echo (string)Url::to('/account/karma') ?>"
                           class="btn btn-sm btn-secondary float-right">
                            <?php echo t("Earn Achievements"); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>

            <div class="card-text">
                <div class="row">
                    <div class="col">
                        <?php if (count($badges) > 0) { ?>
                            <div class="profile-badges">
                                <?php foreach ($badges as $badge) { ?>
                                    <div class="profile-badge">
                                        <?php
                                        $badgeUrl = $package->getRelativePath() . "/images/default_badge.png";

                                        $userBadge = $badge["userBadge"];

                                        if ($userBadge instanceof UserBadge) {
                                            if ($userBadge->getBadge() instanceof Badge) {
                                                $badgeThumbnail = $userBadge->getBadge()->getThumbnail();
                                                if ($badgeThumbnail instanceof File) {
                                                    $badgeThumbnailVersion = $badgeThumbnail->getApprovedVersion();
                                                    if ($badgeThumbnailVersion instanceof Version) {
                                                        $badgeUrl = $badgeThumbnailVersion->getURL();
                                                    }
                                                }
                                            }
                                        }

                                        $imageElement = new Image($badgeUrl, $userBadge->getBadge()->getName());
                                        $imageElement->setAttribute("title", $userBadge->getBadge()->getName());

                                        if ($badge["count"] > 1) {
                                            $imageWrapper = new Element("div");
                                            $imageWrapper->addClass("badge-container");
                                            /** @noinspection PhpParamsInspection */
                                            $imageWrapper->appendChild($imageElement);
                                            $imageWrapper->appendChild(new Element("div", $badge["count"], ["class" => "badge-counter", "style" => "margin: 0;"]));
                                            echo $imageWrapper;
                                        } else {
                                            echo $imageElement;
                                        }

                                        ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="none-entered">
                                <?php echo t("None Entered"); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

</div>
