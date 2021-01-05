<?php


namespace mackiavelly\modules\trinitytv\models;

use mackiavelly\modules\trinitytv\TrinitytvModule;
use mackiavelly\trinitytv\TrinityApi;
use Yii;
use yii\base\Model;

class TrinitytvDeviceCode extends Model {

	public $code;

	public $localid;

	public $note;

	public function addDeviceCode() {
		$trinityApi = new TrinityApi(Yii::$app->params['trinitytv']);
		return $trinityApi->autorizeByCode($this->localid, $this->code, $this->note);
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'localid' => TrinitytvModule::t('trinitytv', 'Contract Partner'),
			'code'    => TrinitytvModule::t('trinitytv', 'Code'),
			'note'    => TrinitytvModule::t('trinitytv', 'Note'),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['localid', 'code'], 'required'],
			[['localid'], 'integer'],
			[['code'], 'trim'],
			[['code'], 'string', 'min' => 4, 'max' => 4],
			[['note'], 'string', 'max' => 50],
		];
	}
}
