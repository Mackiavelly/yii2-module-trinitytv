<?php


namespace mackiavelly\modules\trinitytv\models;


use mackiavelly\modules\trinitytv\TrinitytvModule;
use mackiavelly\trinitytv\TrinityApi;
use Yii;
use yii\base\Model;

class TrinitytvTariff extends Model {

	public $localid;
	public $subscrid;

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['localid', 'subscrid'], 'required'],
			[['localid', 'subscrid'], 'integer'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'localid'  => TrinitytvModule::t('trinitytv', 'Contract Partner'),
			'subscrid' => TrinitytvModule::t('trinitytv', 'Tariff'),
		];
	}

	public function changeTariff() {
		$trinityApi = new TrinityApi(Yii::$app->params['trinitytv']);
		return $trinityApi->create($this->localid, $this->subscrid);
	}
}
