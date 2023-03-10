<?php declare(strict_types=1);
if (!defined('MW_PATH')) {
    exit('No direct script access allowed');
}

/**
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.0
 */

/** @var ExtensionController $controller */
$controller = controller();

/** @var TourSlideshowSlide $slide */
$slide = $controller->getData('slide');

/** @var TourSlideshow $slideshow */
$slideshow = $controller->getData('slideshow');

/** @var string $pageHeading */
$pageHeading = (string)$controller->getData('pageHeading');

/**
 * This hook gives a chance to prepend content or to replace the default view content with a custom content.
 * Please note that from inside the action callback you can access all the controller view
 * variables via {@CAttributeCollection $collection->controller->data}
 * In case the content is replaced, make sure to set {@CAttributeCollection $collection->renderContent} to false
 * in order to stop rendering the default content.
 * @since 1.3.3.1
 */
hooks()->doAction('before_view_file_content', $viewCollection = new CAttributeCollection([
    'controller'    => $controller,
    'renderContent' => true,
]));

// and render if allowed
if ($viewCollection->itemAt('renderContent')) {
    $controller->renderPartial($controller->getExtension()->getPathAlias('backend.views._tabs'));
    /**
     * This hook gives a chance to prepend content before the active form or to replace the default active form entirely.
     * Please note that from inside the action callback you can access all the controller view variables
     * via {@CAttributeCollection $collection->controller->data}
     * In case the form is replaced, make sure to set {@CAttributeCollection $collection->renderForm} to false
     * in order to stop rendering the default content.
     * @since 1.3.3.1
     */
    hooks()->doAction('before_active_form', $collection = new CAttributeCollection([
        'controller'    => $controller,
        'renderForm'    => true,
    ]));

    // and render if allowed
    if ($collection->itemAt('renderForm')) {
        /** @var CActiveForm $form */
        $form = $controller->beginWidget('CActiveForm', [
            'htmlOptions' => [
                'enctype' => 'multipart/form-data',
            ],
        ]); ?>
        <div class="box box-primary borderless">
            <div class="box-header">
                <div class="pull-left">
                    <h3 class="box-title"><?php echo IconHelper::make('glyphicon-book') . $pageHeading; ?></h3>
                </div>
                <div class="pull-right">
                    <?php if (!$slideshow->getIsNewRecord()) { ?>
                        <?php echo CHtml::link(IconHelper::make('create') . t('app', 'Create new'), $controller->getExtension()->createUrl('slideshow_slides/create', ['slideshow_id' => $slideshow->slideshow_id]), ['class' => 'btn btn-primary btn-flat', 'title' => t('app', 'Create new')]); ?>
                    <?php } ?>
                    <?php echo CHtml::link(t('app', 'Cancel'), $controller->getExtension()->createUrl('slideshow_slides/index', ['slideshow_id' => $slideshow->slideshow_id]), ['class' => 'btn btn-primary btn-flat', 'title' => t('app', 'Cancel')]); ?>
                </div>
                <div class="clearfix"><!-- --></div>
            </div>
            <div class="box-body">
                <?php
                /**
                 * This hook gives a chance to prepend content before the active form fields.
                 * Please note that from inside the action callback you can access all the controller view variables
                 * via {@CAttributeCollection $collection->controller->data}
                 * @since 1.3.3.1
                 */
                hooks()->doAction('before_active_form_fields', new CAttributeCollection([
                    'controller'    => $controller,
                    'form'          => $form,
                ])); ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <?php echo $form->labelEx($slide, 'title'); ?>
                            <?php echo $form->textField($slide, 'title', $slide->fieldDecorator->getHtmlOptions('title')); ?>
                            <?php echo $form->error($slide, 'title'); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <?php echo $form->labelEx($slide, 'content'); ?>
                            <?php echo $form->textArea($slide, 'content', $slide->fieldDecorator->getHtmlOptions('content')); ?>
                            <?php echo $form->error($slide, 'content'); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-3">
                                <label><a href="javascript:;" class="tour-clear-image" data-default="<?php echo $slide->getDefaultImageUrl(120, 60); ?>"><?php echo t('settings', 'Clear'); ?></a></label>
                                <img src="<?php echo $slide->getImageUrl(120, 60); ?>" class="img-thumbnail"/>
                            </div>
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <?php echo $form->labelEx($slide, 'image'); ?>
                                    <?php echo $form->fileField($slide, 'image_up', $slide->fieldDecorator->getHtmlOptions('image')); ?>
                                    <?php echo $form->hiddenField($slide, 'image'); ?>
                                    <?php echo $form->error($slide, 'image'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <?php echo $form->labelEx($slide, 'sort_order'); ?>
                            <?php echo $form->dropDownList($slide, 'sort_order', $slide->getSortOrderList(), $slide->fieldDecorator->getHtmlOptions('sort_order')); ?>
                            <?php echo $form->error($slide, 'sort_order'); ?>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <?php echo $form->labelEx($slide, 'status'); ?>
                            <?php echo $form->dropDownList($slide, 'status', $slide->getStatusesList(), $slide->fieldDecorator->getHtmlOptions('status')); ?>
                            <?php echo $form->error($slide, 'status'); ?>
                        </div>
                    </div>
                </div>
                <?php
                /**
                 * This hook gives a chance to append content after the active form fields.
                 * Please note that from inside the action callback you can access all the controller view variables
                 * via {@CAttributeCollection $collection->controller->data}
                 * @since 1.3.3.1
                 */
                hooks()->doAction('after_active_form_fields', new CAttributeCollection([
                    'controller'    => $controller,
                    'form'          => $form,
                ])); ?>
                <div class="clearfix"><!-- --></div>
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <button type="submit" class="btn btn-primary btn-flat"><?php echo IconHelper::make('save') . t('app', 'Save changes'); ?></button>
                </div>
                <div class="clearfix"><!-- --></div>
            </div>
        </div>
        <?php
        $controller->endWidget();
    }
    /**
     * This hook gives a chance to append content after the active form.
     * Please note that from inside the action callback you can access all the controller view variables
     * via {@CAttributeCollection $collection->controller->data}
     * @since 1.3.3.1
     */
    hooks()->doAction('after_active_form', new CAttributeCollection([
        'controller'      => $controller,
        'renderedForm'    => $collection->itemAt('renderForm'),
    ]));
}
/**
 * This hook gives a chance to append content after the view file default content.
 * Please note that from inside the action callback you can access all the controller view
 * variables via {@CAttributeCollection $collection->controller->data}
 * @since 1.3.3.1
 */
hooks()->doAction('after_view_file_content', new CAttributeCollection([
    'controller'        => $controller,
    'renderedContent'   => $viewCollection->itemAt('renderContent'),
]));
