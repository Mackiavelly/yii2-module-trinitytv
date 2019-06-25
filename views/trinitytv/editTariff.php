<?php

use mackiavelly\modules\trinitytv\TrinitytvModule;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
	'id'                     => 'edit-tariff',
	'enableAjaxValidation'   => true,
	'enableClientValidation' => false,
]);

echo Html::activeHiddenInput($model, 'localid');

echo $form->field($model, 'subscrid')
	->dropDownList(Yii::$app->params['trinitytv']['serviceId'], ['prompt' => TrinitytvModule::t('trinitytv', 'Select item...')]);

echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']);

ActiveForm::end();
