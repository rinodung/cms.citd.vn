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
 * @since 1.5.2
 */

/** @var Controller $controller */
$controller = controller();

/** @var array $actions */
$actions = (array)$controller->getData('actions');

?>
<div class="btn-group dropup subscribers-gridview-options-btn-group">
    <button type="button" class="btn btn-primary btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php echo IconHelper::make('fa-cogs'); ?>
    </button>
    <ul class="dropdown-menu" style="min-width: <?php echo(count($actions) * 32) + 10; ?>px">
        <?php foreach ($actions as $action) { ?>
            <li><?php echo $action; ?></li>
        <?php } ?>
    </ul>
</div>