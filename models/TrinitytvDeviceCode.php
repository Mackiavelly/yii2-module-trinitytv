<?php


namespace mackiavelly\modules\trinitytv\models;

use mackiavelly\modules\trinitytv\TrinitytvModule;
use mackiavelly\trinitytv\TrinityApi;
use Yii;
use yii\base\Model;

class TrinitytvDeviceCode extends Model {

	public $localid;
	public $code;

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['localid', 'code'], 'required'],
			[['localid'], 'integer'],
			[['code'], 'trim'],
			[['code'], 'string', 'min' => 4, 'max' => 4],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'localid' => TrinitytvModule::t('trinitytv', 'Contract Partner'),
			'code'    => TrinitytvModule::t('trinitytv', 'Code'),
		];
	}

	public function addDeviceCode() {
		$trinityApi = new TrinityApi(Yii::$app->params['trinitytv']);
		return $trinityApi->autorizeByCode($this->localid, $this->code);
	}
}
