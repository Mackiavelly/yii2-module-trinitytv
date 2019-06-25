<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
	'id'                     => 'add-device-code',
	'enableAjaxValidation'   => true,
	'enableClientValidation' => false,
]);

echo Html::activeHiddenInput($model, 'localid');

echo $form->field($model, 'code');

echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']);

ActiveForm::end();
