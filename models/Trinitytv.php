<?php

namespace mackiavelly\modules\trinitytv\models;

use mackiavelly\modules\trinitytv\TrinitytvModule;
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

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'localid'         => TrinitytvModule::t('app', 'Contract Partner'),
			'subscrid'        => TrinitytvModule::t('app', 'Tariff'),
			'subscrprice'     => TrinitytvModule::t('app', 'Tariff Price'),
			'subscrstatusid'  => TrinitytvModule::t('app', 'Status'),
			'contracttrinity' => TrinitytvModule::t('app', 'Contract Trinity'),
			'devicescount'    => TrinitytvModule::t('app', 'Devices Count'),
			'contractdate'    => TrinitytvModule::t('app', 'Contract Date'),
		];
	}
}