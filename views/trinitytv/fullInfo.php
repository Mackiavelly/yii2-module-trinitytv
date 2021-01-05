<?php

use mackiavelly\modules\trinitytv\models\Trinitytv;
use mackiavelly\modules\trinitytv\TrinitytvModule;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var object $model */
Yii::debug($model->toArray());

echo DetailView::widget([
	'id'         => 'detail-user',
	'model'      => $model,
	'options'    => [
		'class' => 'table table-striped table-bordered table-hover table-responsive table-condensed detail-view',
	],
	'attributes' => [
		'localid',
		'contracttrinity',
		'subscrname',
		'subscrprice',
		'subscrstatus',
		'contractdate',
		'devicescount',
		'last_session_date',
		'middlename',
		'name',
		[
			'attribute' => 'lastname',
			'format'    => 'raw',
			'value'     => function($model) {
				/**
				 * @var $model Trinitytv
				 */
				if (isset(Yii::$app->params['trinitytv']['debug']) && Yii::$app->params['trinitytv']['debug'] === 'mack') {
					return app\components\Helper::buildUserBillingLink([$model->middlename, $model->lastname]);
				}
				return $model->lastname;
			},
		],
		'address',
		'balance',
		'note',
		[
			'attribute' => 'devices',
			'label'     => TrinitytvModule::t('trinitytv', 'Device List'),
			'format'    => 'raw',
			'value'     => function($model) {
				/**
				 * @var $model Trinitytv
				 */
				$button[] = Html::button(Html::tag('span', null, ['class' => 'glyphicon glyphicon-plus']).' CODE', [
					'class' => 'btn btn-xs btn-success trinitytv-modal',
					'data'  => [
						'url'   => Url::to(['add-device-code'] + $model->toArray()),
						'model' => $model->toArray(),
					],
					'title' => TrinitytvModule::t('trinitytv', 'Add Device by Code'),
				]);
				$button[] = Html::button(Html::tag('span', null, ['class' => 'glyphicon glyphicon-plus']).' MAC/UUID', [
					'class' => 'btn btn-xs btn-success trinitytv-modal',
					'data'  => [
						'url'   => Url::to(['add-device'] + $model->toArray()),
						'model' => $model->toArray(),
					],
					'title' => TrinitytvModule::t('trinitytv', 'Add Device'),
				]);
				$button[] = Html::button(Html::tag('span', null, ['class' => 'glyphicon glyphicon-plus']).' '.TrinitytvModule::t('trinitytv', 'Playlist'), [
					'class' => 'btn btn-xs btn-success trinitytv-modal',
					'data'  => [
						'url'   => Url::to(['add-play-list'] + $model->toArray()),
						'model' => $model->toArray(),
					],
					'title' => TrinitytvModule::t('trinitytv', 'Add Playlist'),
				]);
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
					'layout'       => '<p><div class="btn-group" role="group" aria-label="trinitytv-actions">'.implode($button).'</div></p>{items}',
					'columns'      => [
						['class' => 'yii\grid\SerialColumn'],
						'mac',
						'uuid',
						'note',
						[
							'label'  => Yii::t('app', 'Delete'),
							'format' => 'raw',
							'value'  => function($device, $key) use ($model) {
								$modelArray = $model->toArray();
								$result = Html::beginTag('div', ['class' => 'btn-group', 'role' => 'group']);
								$result .= Html::button(Html::tag('span', null, ['class' => 'glyphicon glyphicon-pencil']), [
									'title' => TrinitytvModule::t('trinitytv', 'Edit Note'),
									'class' => 'btn btn-xs btn-info trinitytv-edit-device-note',
									'data'  => [
										'url'     => Url::to(['edit-device-note'] + $modelArray + ['device_id' => $key]),
									],
								]);
								$result .= Html::button(Html::tag('span', null, ['class' => 'glyphicon glyphicon-trash']), [
									'title' => TrinitytvModule::t('trinitytv', 'Delete'),
									'class' => 'btn btn-xs btn-danger trinitytv-delete-device',
									'data'  => [
										'confirm_msg' => TrinitytvModule::t('trinitytv', 'Are you sure you want to delete this item?'),
										'url'        => Url::to(['delete-device'] + $modelArray + $device),
										'localid' => $model->localid,
									],
								]);
								$result .= Html::endTag('div');
								return $result;
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
	if (confirm(data.confirm_msg)) {
		modal.find('.modal-body').html('...').load(data.url, function() {
			let countTag = $('#devicescount-'+data.localid),
			count = countTag.html();
			countTag.html(count-1);
		});
		modal.modal('show');
	}
});
$('.trinitytv-edit-device-note').click(function() {
	let	modal = $('#modal-trinitytv'),
		element = $(this),
		data = element.data();
	console.log(data);
	modal.find('.modal-body').html('...').load(data.url);
	modal.modal('show');
});
JS;
$this->registerJs($js);
