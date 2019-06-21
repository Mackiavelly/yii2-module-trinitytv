<?php

namespace mackiavelly\modules\trinitytv\controllers;

use mackiavelly\modules\trinitytv\models\Trinitytv;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `trinitytv` module
 */
class TrinitytvController extends Controller {
	/**
	 * Renders the index view for the module
	 *
	 * @return string
	 */
	public function actionIndex() {
		$model = new Trinitytv();
		$dataProvider = $model->search(Yii::$app->request->get());
		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'filterModel'  => $model,
		]);
	}
}
