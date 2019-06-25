<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
	'id'                     => 'add-device',
	'enableAjaxValidation'   => true,
	'enableClientValidation' => false,
]);

echo Html::activeHiddenInput($model, 'localid');


echo $form->field($model, 'mac');

echo $form->field($model, 'uuid');

echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']);

ActiveForm::end();
