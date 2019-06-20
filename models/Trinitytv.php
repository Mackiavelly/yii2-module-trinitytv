<?php

namespace mackiavelly\modules\trinitytv\models;

use mackiavelly\trinitytv\TrinityApi;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;

class Trinitytv extends Model {
	/**
	 * {@inheritdoc}
	 */
	public function init() {
		parent::init();
	}

	/**
	 * @return ArrayDataProvider
	 */
	public function buildAllTvUsersProvider() {
		$users = $this->findAllTvUsers();
		return new ArrayDataProvider([
			'allModels' => $users['result'] == 'success' ? $users['subscribers'] : [],
		]);
	}

	/**
	 * @return bool|mixed
	 */
	public function findAllTvUsers() {
		$trinityApi = new TrinityApi(Yii::$app->params['trinitytv']);
		return $trinityApi->subscriberList();
	}
}