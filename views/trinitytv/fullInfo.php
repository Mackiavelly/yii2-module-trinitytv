<?php

use mackiavelly\modules\trinitytv\TrinitytvModule;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

echo DetailView::widget([
	'id'         => 'detail-user',
	'model'      => $model,
	'attributes' => [
		'localid',
		'contracttrinity',
		'subscrname',
		'subscrprice',
		'subscrstatus',
		'contractdate',
		'devicescount',
		[
			'attribute' => 'devices',
			'label'     => TrinitytvModule::t('trinitytv', 'Device List'),
			'format'    => 'raw',
			'value'     => function($model) {
				return GridView::widget([
					'id'           => 'grid-user-devices',
					'dataProvider' => new ArrayDataProvider([
						'allModels'  => $model->devices,
						'pagination' => false,
						'sort'       => false,
					]),
					'tableOptions' => [
						'class' => 'table table-bordered table-hover table-responsive table-condensed',
					],
					'layout'       => '{items}',
					'columns'      => [
						['class' => 'yii\grid\SerialColumn'],
						'mac',
						'uuid',
						[
							'label'  => Yii::t('app', 'Delete'),
							'format' => 'raw',
							'value'  => function($device) use ($model) {
								$modelArray = $model->toArray();
								return Html::button(Html::tag('span', null, ['class' => 'glyphicon glyphicon-trash']), [
									'title' => Yii::t('yii', 'Delete'),
									'class' => 'btn btn-xs btn-danger trinitytv-delete-device',
									'data'  => [
										'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
										'url'     => Url::to(['delete-device'] + $modelArray + $device),
									],
								]);
							},
						],
					],
				]);
			},
		],
	],
]);

$js = <<< JS
$('.trinitytv-delete-device').click(function() {
	let	modal = $('#modal-trinitytv'),
		element = $(this),
		data = element.data();
	console.log(data);
	modal.find('.modal-body').html('...').load(data.url, function() {
	  $.pjax.reload({container: '#pjax-trinity-index'});
	});
	modal.modal('show');
});
JS;
$this->registerJs($js);
