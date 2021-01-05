<?php

use mackiavelly\modules\trinitytv\models\Trinitytv;
use mackiavelly\modules\trinitytv\TrinitytvModule;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $model \mackiavelly\modules\trinitytv\models\TrinitytvState */

$form = ActiveForm::begin([
	'id'                     => 'edit-state',
	'enableAjaxValidation'   => true,
	'enableClientValidation' => false,
]);

echo Html::activeHiddenInput($model, 'localid');

echo $form->field($model, 'subscrstatusid')
	->dropDownList((new Trinitytv)->getStatus(), ['prompt' => TrinitytvModule::t('trinitytv', 'Select item...')]);

echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']);

ActiveForm::end();
