<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $model \mackiavelly\modules\trinitytv\models\TrinitytvUserInfo */

$form = ActiveForm::begin([
	'id'                     => 'edit-user-info',
	'enableAjaxValidation'   => true,
	'enableClientValidation' => false,
]);

echo Html::activeHiddenInput($model, 'localid');

echo $form->field($model, 'firstName');

echo $form->field($model, 'lastName');

echo $form->field($model, 'middleName');

echo $form->field($model, 'address');

echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']);

ActiveForm::end();
