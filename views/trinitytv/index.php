<?php

use mackiavelly\modules\trinitytv\models\Trinitytv;
use mackiavelly\modules\trinitytv\TrinitytvModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider Trinitytv */

$this->title = TrinitytvModule::t('app', 'Trinity TV');
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin([
	'id'      => 'pjax-trinity-index',
	'timeout' => 5000,
]);
?>
	<div class="trinitytv-index">
		<h1><?= Html::encode($this->title) ?></h1>
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'columns'      => [
				[
					'label' => 'LocalId',
					'value' => function($model, $key, $index, $column) {
						return $key;
					},
				],
				'subscrid',
				'subscrprice',
				'subscrstatusid',
				'contracttrinity',
				'devicescount',
				'contractdate',
				//['class' => 'yii\grid\ActionColumn'],
			],
		]); ?>
	</div>
<?php
Pjax::end();
