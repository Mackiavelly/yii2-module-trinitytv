<?php

namespace mackiavelly\modules\trinitytv\controllers;

use mackiavelly\modules\trinitytv\models\Trinitytv;
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
		return $this->render('index', [
			'dataProvider' => (new Trinitytv)->buildAllTvUsersProvider(),
		]);
	}
}
