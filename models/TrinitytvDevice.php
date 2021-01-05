<?php


namespace mackiavelly\modules\trinitytv\models;

use mackiavelly\modules\trinitytv\TrinitytvModule;
use mackiavelly\trinitytv\TrinityApi;
use Yii;
use yii\base\Model;

class TrinitytvDevice extends Model {

	public $localid;

	public $mac;

	public $note;

	public $uuid;

	public function addDevice() {
		$trinityApi = new TrinityApi(Yii::$app->params['trinitytv']);
		//return $trinityApi->autorizeDevice($this->localid, $this->mac, $this->uuid);
		return $trinityApi->autorizeDeviceNote($this->localid, $this->mac, $this->uuid, $this->note);
	}

	public function addPlayList() {
		$trinityApi = new TrinityApi(Yii::$app->params['trinitytv']);
		return $trinityApi->getPlayList($this->localid);
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'localid'   => TrinitytvModule::t('trinitytv', 'Contract Partner'),
			'mac'       => TrinitytvModule::t('trinitytv', 'MAC'),
			'uuid'      => TrinitytvModule::t('trinitytv', 'UUID'),
			'note'      => TrinitytvModule::t('trinitytv', 'Note'),
		];
	}

	public function deleteDevice() {
		$trinityApi = new TrinityApi(Yii::$app->params['trinitytv']);
		return $trinityApi->deleteDevice($this->localid, $this->mac, $this->uuid);
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['localid'], 'required'],
			[['localid'], 'integer'],
			[['mac', 'uuid'], 'trim'],
			[['mac'], 'string', 'min' => 12, 'max' => 12],
			[['mac'], 'validateMac'],
			[['uuid'], 'string', 'min' => 12, 'max' => 50],
			[['uuid'], 'validateUuid'],
			[['mac', 'uuid'], 'validateOne', 'skipOnEmpty' => false],
			[['note'], 'string', 'max' => 50],
		];
	}

	public function validateMac() {
		if (!empty($this->mac) && !preg_match('/[0-9a-f]{12}/i', $this->mac)) {
			$this->addError('mac', TrinitytvModule::t('trinitytv', 'No valid MAC-address'));
		}
	}

	public function validateOne($attribute) {
		if (empty($this->mac) && empty($this->uuid)) {
			$this->addError($attribute, TrinitytvModule::t('trinitytv', 'At least 1 of the field must be filled up properly'));
		}
	}

	public function validateUuid() {
		if (!empty($this->mac) && !preg_match('/[-0-9a-z]{12,50}/i', $this->mac)) {
			$this->addError('mac', TrinitytvModule::t('trinitytv', 'No valid MAC-address'));
		}
	}
}
