<?php declare(strict_types=1);
if (!defined('MW_PATH')) {
    exit('No direct script access allowed');
}

/**
 * This file is part of the MailWizz EMA application.
 *
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.3.6.2
 */

/** @var Controller $controller */
$controller = controller();

/** @var CampaignTrackUnsubscribe $trackUnsubscribe */
$trackUnsubscribe = $controller->getData('trackUnsubscribe');

?>

<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <?php echo CHtml::activeLabelEx($trackUnsubscribe, 'reason'); ?>
            <?php echo CHtml::activeTextArea($trackUnsubscribe, 'reason', $trackUnsubscribe->fieldDecorator->getHtmlOptions('reason', [
                'name' => 'unsubscribe_reason',
                'id'   => 'unsubscribe_reason',
            ])); ?>
            <?php echo CHtml::error($trackUnsubscribe, 'reason'); ?>
        </div>
    </div>
</div>
