<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $model \mackiavelly\modules\trinitytv\models\TrinitytvDeviceNote */

$form = ActiveForm::begin([
	'id'                     => 'edit-device-note',
	'enableAjaxValidation'   => true,
	'enableClientValidation' => false,
]);

echo Html::activeHiddenInput($model, 'device_id');

echo $form->field($model, 'note');

echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']);

ActiveForm::end();

$js = <<< JS
$(document).on('beforeSubmit', '#edit-device-note', function() {
	let form = $(this),
		modal = $('#modal-trinitytv');
	modal.find('.modal-body').html('...');
	$.ajax({
		url : form.attr('action')+'&submit=true',
		type: form.attr('method'),
		data: form.serializeArray(),
	})
	.done(function(result) {
		modal.find('.modal-body').html(result);
		modal.modal('show');
	})
	.fail(function() {
		console.log('internal server error');
	});
	return false;
});
JS;
$this->registerJs($js);