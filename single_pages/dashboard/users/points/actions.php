<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Support\Facade\Url;

/**
 * @var PortlandLabs\CommunityBadges\User\Point\Action\ActionList $actionList
 * @var Pagerfanta\Pagerfanta $pagination
 * @var bool $showForm
 * @var int $upaID
 * @var bool $upaHasCustomClass
 * @var string $upaHandle
 * @var string $upaName
 * @var string $upaDefaultPoints
 * @var array $badges
 * @var int $gBadgeID
 * @var array $actions
 * @var int $upaIsActive
 */

$upaID = $upaID ?? null;
$showForm = $showForm ?? false;
$upaIsActive = isset($upaIsActive) ? $upaIsActive : 1;
$upaHasCustomClass = isset($upaHasCustomClass) ? $upaHasCustomClass : false;
$upaName = $upaName ?? '';
$upaHandle = $upaHandle ?? '';
$upaDefaultPoints = $upaDefaultPoints ?? 0;

if ($showForm) {
    ?>
<form method="post" action="<?= $view->action('save') ?>" id="ccm-community-points-action">
    <?php $token->output('add_action') ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->hidden('upaID', $upaID) ?>

        	<div class="form-check">
                <?= $form->checkbox('upaIsActive', 1, ($upaIsActive == 1 || (!$upaID))) ?>
                <?= $form->label('upaIsActive', t('Enabled'), ['class' => 'form-check-label']) ?>
            </div>

        	<div class="form-group">
        	    <?= $form->label('upaHandle', t('Action Handle')) ?>
                <?= $form->text('upaHandle', $upaHandle, $upaHasCustomClass ? ['disabled' => 'disabled'] : []) ?>
        	</div>

        	<div class="form-group">
        	    <?= $form->label('upaName', t('Action Name')) ?>
        		<?= $form->text('upaName', $upaName) ?>
        	</div>

        	<div class="form-group">
                <?= $form->label('upaDefaultPoints', t('Default Points')) ?>
        		<?= $form->number('upaDefaultPoints', $upaDefaultPoints) ?>
        	</div>

            <?php
                $label = t('Add Action');
                if ($upaID > 0) {
                    $label = t('Update Action');
                }
            ?>

            <div class="ccm-dashboard-form-actions-wrapper">
                <div class="ccm-dashboard-form-actions">
                    <a href="<?= Url::to('/dashboard/users/points/actions') ?>" class="btn btn-secondary float-start"><?=t('Back to List')?></a>
                    <button class="btn btn-primary float-end" type="submit"><?= $label ?></button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php
} else {
    ?>
	<div class="ccm-dashboard-header-buttons">
	    <a href="<?=$view->action('add')?>" class="btn btn-primary"><?=t('Add Action')?></a>
	</div>

	<?php
    if (count($actions) > 0) {
        ?>
        <div class="table-responsive">
			<table class="ccm-search-results-table compact-results">
    			<thead>
    				<tr>
                        <th><span><?=t('Active')?></span></th>
                        <th class="<?=$actionList->getSortClassName('upa.upaName')?>"><a href="<?=$actionList->getSortURL('upa.upaName', 'desc')?>"><?=t('Action Name')?></a></th>
                        <th class="<?=$actionList->getSortClassName('upa.upaHandle')?>"><a href="<?=$actionList->getSortURL('upa.upaHandle')?>"><?=t('Action Handle')?></a></th>
                        <th class="<?=$actionList->getSortClassName('upa.upaDefaultPoints')?>"><a href="<?=$actionList->getSortURL('upa.upaDefaultPoints')?>"><?=t('Default Points')?></a></th>
                        <th></th>
                    </tr>
    			</thead>

                <tbody>
            		<?php
                    foreach ($actions as $upa) {
                        ?>
                        <tr class="">
                            <td style="text-align: center"><?php if ($upa['upaIsActive']) { ?><i class="fas fa-check"></i><?php } ?></td>
                            <td><?= h($upa['upaName']) ?></td>
                            <td><?= h($upa['upaHandle']) ?></td>
                            <td><?= number_format($upa['upaDefaultPoints']) ?></td>
                            <td class="text-right">
                                <?php
                                    $deleteUrl = \Concrete\Core\Url\Url::createFromUrl($view->action('delete', $upa['upaID']));
                                    $deleteUrl->setQuery([
                                        'ccm_token' => $token->generate('delete_action'),
                                    ]);
                                ?>
                                <a href="<?= $view->action($upa['upaID']) ?>" class="btn btn-sm btn-secondary"><?= t('Edit') ?></a>
                                <a href="<?= $deleteUrl ?>" class="btn btn-sm btn-danger"><?= t('Delete') ?></a>
                            </td>
                        </tr>
            		<?php
                    }
                    ?>
                </tbody>
    		</table>
        </div>
		<?php
    } else {
        ?>
			<p><?=t('No Actions found.')?></p>
		<?php
    }
    ?>

    <div class="ccm-search-results-pagination">
        <?php
            if ($pagination->haveToPaginate()) {
                echo $pagination->renderView('dashboard');
            }
        ?>
    </div>

<?php
} ?>
