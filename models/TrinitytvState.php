<?php

namespace mackiavelly\modules\trinitytv\models;

use mackiavelly\modules\trinitytv\TrinitytvModule;
use mackiavelly\trinitytv\TrinityApi;
use Yii;
use yii\base\Model;

class TrinitytvState extends Model {

	public $localid;
	public $subscrstatusid;

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['localid', 'subscrstatusid'], 'required'],
			[['localid', 'subscrstatusid'], 'integer'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'localid'        => TrinitytvModule::t('trinitytv', 'Contract Partner'),
			'subscrstatusid' => TrinitytvModule::t('trinitytv', 'State'),
		];
	}

	public function changeState() {
		$trinityApi = new TrinityApi(Yii::$app->params['trinitytv']);
		if ($this->subscrstatusid == 0) {
			return $trinityApi->subscription($this->localid, $trinityApi::RESUME);
		}
		return $trinityApi->subscription($this->localid, $trinityApi::SUSPEND);
	}
}
