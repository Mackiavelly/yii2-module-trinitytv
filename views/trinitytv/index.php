<?php

use mackiavelly\modules\trinitytv\models\Trinitytv;
use mackiavelly\modules\trinitytv\TrinitytvModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider Trinitytv */
/* @var $filterModel Trinitytv */


$this->title = TrinitytvModule::t('trinitytv', 'Trinity TV');
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin([
	'id'      => 'pjax-trinity-index',
	'timeout' => 5000,
]);
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
					'filter'    => Yii::$app->params['trinitytv']['serviceId'],
					'value'     => function($model) {
						return Yii::$app->params['trinitytv']['serviceId'][$model['subscrid']];
					},
				],
				//'subscrprice',
				[
					'attribute' => 'subscrstatusid',
					'filter'    => $filterModel->getStatus(),
					'format'    => 'raw',
					'value'     => function($model) {
						return Html::tag('span', TrinitytvModule::t('trinitytv', Trinitytv::STATUS[$model['subscrstatusid']]['name']), ['class' => Trinitytv::STATUS[$model['subscrstatusid']]['class']]);
					},
				],
				[
					'attribute' => 'devicescount',
					'filter'    => [
						0 => TrinitytvModule::t('trinitytv', 'No device'),
						1 => TrinitytvModule::t('trinitytv', 'One device'),
						2 => TrinitytvModule::t('trinitytv', 'Two devices'),
						3 => TrinitytvModule::t('trinitytv', 'Three devices'),
						4 => TrinitytvModule::t('trinitytv', 'Four devices'),
					],
				],
				[
					'attribute'          => 'contractdate',
					'filterInputOptions' => ['class' => 'form-control', 'autocomplete' => 'off'],
				],
				/*[
					'class'      => 'yii\grid\ActionColumn',
					'urlCreator' => function($action, $model) {
						return Url::to([$action, 'id' => $model['localid']]);
					},
				],*/
			],
		]); ?>
	</div>
<?php
Pjax::end();
