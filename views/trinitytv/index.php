<?php

use mackiavelly\modules\trinitytv\models\Trinitytv;
use mackiavelly\modules\trinitytv\TrinitytvModule;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider Trinitytv */
/* @var $filterModel Trinitytv */


$this->title = TrinitytvModule::t('trinitytv', 'Trinity TV');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];

Pjax::begin([
	'id' => 'pjax-trinity-index',
	'timeout' => 10000,
]);
if (!empty($alert = Yii::$app->session->get('trinitytv-alert'))) {
	Yii::$app->session->remove('trinitytv-alert');
	if ($alert['result'] == 'success') {
		$text = TrinitytvModule::t('trinitytv', 'Action success!');
		$color = 'success';
	} else {
		$text = TrinitytvModule::t('trinitytv', 'Error! ').$alert['result'];
		$color = 'danger';
	}
	$debugButton = Html::button(Html::tag('span', null, ['class' => 'glyphicon glyphicon-eye-close']), [
		'class' => 'btn btn-default btn-xs btn-'.$color,
		'title' => TrinitytvModule::t('trinitytv', 'Debug'),
		'data'  => [
			'toggle' => 'collapse',
			'target' => '#collapse-trinitytv',
		],
	]);
	$debugResponse = Html::tag('div', '<br>'.Html::tag('pre', print_r($alert, true)), [
		'class' => 'collapse',
		'id'    => 'collapse-trinitytv',
	]);
	$closeButton = Html::button('&times;', ['class' => 'close', 'data-dismiss' => 'alert']);
	echo Html::tag('div', $debugButton.' ['.date('H:i:s').'] '.$text.$closeButton.$debugResponse, [
		'class' => 'alert alert-'.$color.' alert-dismissible',
		'role'  => 'alert',
	]);
}
$pages = range(10, 100, 10);
$perPage = Html::tag('div', TrinitytvModule::t('trinitytv', 'Rows per page'), [
		'class' => 'col-xs-2 form-control-static',
		'style' => 'text-align: right;',
	])
	.Html::beginTag('div', ['class' => 'col-xs-1'])
	.Html::activeDropDownList($filterModel, 'perpage', array_combine($pages, $pages), [
		'class' => 'form-control',
		'id'    => 'trinitytv-perpage',
	])
	.Html::endTag('div');
?>
	<div class="trinitytv-index">
		<?= GridView::widget([
			'id'             => 'grid-users',
			'dataProvider'   => $dataProvider,
			'filterModel'    => $filterModel,
			'filterSelector' => '#trinitytv-perpage',
			'pager'          => [
				'firstPageLabel' => '<span class="glyphicon glyphicon-backward"></span>',
				'lastPageLabel'  => '<span class="glyphicon glyphicon-forward"></span>',
				'prevPageLabel'  => '<span class="glyphicon glyphicon-triangle-left"></span>',
				'nextPageLabel'  => '<span class="glyphicon glyphicon-triangle-right"></span>',
			],
			'tableOptions'   => [
				'class' => 'table table-bordered table-hover table-responsive table-condensed',
			],
			'layout'         => "<div class='row form-group'><div class='col-xs-9 form-control-static'>{summary}</div>".$perPage."</div>\n{items}\n{pager}",
			'columns'        => [
				[
					'attribute'          => 'localid',
					'filterInputOptions' => ['class' => 'form-control', 'autocomplete' => 'off'],
					'value'              => function($model) {
						return $model['localid'];
					},
				],
				[
					'attribute'          => 'contracttrinity',
					'filterInputOptions' => ['class' => 'form-control', 'autocomplete' => 'off'],
				],
				[
					'attribute' => 'subscrid',
					'format'    => 'raw',
					'filter'    => Yii::$app->params['trinitytv']['serviceId'],
					'value'     => function($model) {
						$button = Html::button(Html::tag('span', null, ['class' => 'glyphicon glyphicon-pencil']), [
							'class' => 'btn btn-xs btn-primary trinitytv-modal',
							'data'  => [
								'url'   => Url::to(['tariff'] + $model),
								'model' => $model,
							],
							'title' => TrinitytvModule::t('trinitytv', 'Change Tariff'),
						]);
						$tariff = Yii::$app->params['trinitytv']['serviceId'][$model['subscrid']] ?? null;
						return $tariff != null ? $button.' '.$tariff : null;
					},
				],
				[
					'attribute' => 'subscrstatusid',
					'filter'    => $filterModel->getStatus(),
					'format'    => 'raw',
					'value'     => function($model) {
						$button = Html::button(Html::tag('span', null, ['class' => 'glyphicon glyphicon-off']), [
							'class' => 'btn btn-xs btn-warning trinitytv-modal',
							'data'  => [
								'url'   => Url::to(['state'] + $model),
								'model' => $model,
							],
							'title' => TrinitytvModule::t('trinitytv', 'Enable/Disable'),
						]);
						return $button.' '.Html::tag('span', TrinitytvModule::t('trinitytv', Trinitytv::STATUS[$model['subscrstatusid']]['name']), ['class' => Trinitytv::STATUS[$model['subscrstatusid']]['class']]);
					},
				],
				[
					'attribute' => 'devicescount',
					'format'    => 'raw',
					'filter'    => [
						0 => TrinitytvModule::t('trinitytv', 'No device'),
						1 => TrinitytvModule::t('trinitytv', 'One device'),
						2 => TrinitytvModule::t('trinitytv', 'Two devices'),
						3 => TrinitytvModule::t('trinitytv', 'Three devices'),
						4 => TrinitytvModule::t('trinitytv', 'Four devices'),
					],
					'value'     => function($model) {
						$button[] = Html::button(Html::tag('span', null, ['class' => 'glyphicon glyphicon-plus']).' CODE', [
							'class' => 'btn btn-xs btn-success trinitytv-modal',
							'data'  => [
								'url'   => Url::to(['add-device-code'] + $model),
								'model' => $model,
							],
							'title' => TrinitytvModule::t('trinitytv', 'Add Device by Code'),
						]);
						$button[] = Html::button(Html::tag('span', null, ['class' => 'glyphicon glyphicon-plus']).' MAC/UUID', [
							'class' => 'btn btn-xs btn-success trinitytv-modal',
							'data'  => [
								'url'   => Url::to(['add-device'] + $model),
								'model' => $model,
							],
							'title' => TrinitytvModule::t('trinitytv', 'Add Device'),
						]);
						$button[] = Html::button(Html::tag('span', null, ['class' => 'glyphicon glyphicon-plus']).' '.TrinitytvModule::t('trinitytv', 'Playlist'), [
							'class' => 'btn btn-xs btn-success trinitytv-modal',
							'data'  => [
								'url'   => Url::to(['add-play-list'] + $model),
								'model' => $model,
							],
							'title' => TrinitytvModule::t('trinitytv', 'Add Playlist'),
						]);
						$buttonCount = Html::tag('span', $model['devicescount'], ['id' => 'devicescount-'.$model['localid']]);
						return Html::tag('div', implode('', $button), ['class' => 'btn-group']).' '.$buttonCount;
					},
				],
				[
					'attribute'          => 'contractdate',
					'filterInputOptions' => ['class' => 'form-control', 'autocomplete' => 'off'],
				],
				[
					'class'         => 'yii\grid\ActionColumn',
					'header'        => TrinitytvModule::t('trinitytv', 'Actions'),
					'template'      => '<div class="btn-group" role="group" aria-label="trinitytv-actions">{user-info}{full-info}</div>',
					'urlCreator'    => function($action, $model) {
						return Url::to([$action] + $model);
					},
					'buttonOptions' => ['class' => 'trinitytv-modal'],
					'buttons'       => [
						'user-info' => function($url, $model) {
							return Html::button(Html::tag('span', null, ['class' => 'glyphicon glyphicon-user']), [
								'class' => 'btn btn-xs btn-info trinitytv-modal',
								'data'  => [
									'url'   => $url,
									'model' => $model,
								],
								'title' => TrinitytvModule::t('trinitytv', 'Change User Info'),
							]);
						},
						'full-info' => function($url, $model) {
							return Html::button(Html::tag('span', null, ['class' => 'glyphicon glyphicon-cog']), [
								'class' => 'btn btn-xs btn-info trinitytv-modal',
								'data'  => [
									'url'   => $url,
									'model' => $model,
								],
								'title' => TrinitytvModule::t('trinitytv', 'Show Full User Info'),
							]);
						},
					],
				],
			],
		]); ?>
	</div>
<?php
Pjax::end();
echo Modal::widget([
	'id'      => 'modal-trinitytv',
	'options' => [
		'data' => [
			/*'keyboard' => false,*/
			'backdrop' => 'static',
		],
	],
	'size'    => Modal::SIZE_LARGE,
]);
$js = <<< JS
$(document).on('click', '.trinitytv-modal', function () {
	let	close = '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>',
		modal = $('#modal-trinitytv'),
		element = $(this),
		data = element.data();
	modal.find('.modal-header').html('<h4>'+element.attr('title')+': '+data.model.localid+close+'</h4>');
	modal.find('.modal-body').html('...').load(data.url);
	modal.modal('show');
});
JS;
$this->registerJs($js);
