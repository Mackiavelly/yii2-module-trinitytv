<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $model \mackiavelly\modules\trinitytv\models\TrinitytvDevice */

$form = ActiveForm::begin([
	'id'                     => 'add-device-code',
	'enableAjaxValidation'   => true,
	'enableClientValidation' => false,
]);

echo Html::activeHiddenInput($model, 'localid');

echo $form->field($model, 'code');

echo $form->field($model, 'note');

echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']);

ActiveForm::end();
