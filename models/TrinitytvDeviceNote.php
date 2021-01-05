<?php


namespace mackiavelly\modules\trinitytv\models;

use mackiavelly\modules\trinitytv\TrinitytvModule;
use mackiavelly\trinitytv\TrinityApi;
use Yii;
use yii\base\Model;

class TrinitytvDeviceNote extends Model {

	public $device_id;

	public $note;

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'note'      => TrinitytvModule::t('trinitytv', 'Note'),
			'device_id' => TrinitytvModule::t('trinitytv', 'Device ID'),
		];
	}

	public function editNote() {
		$trinityApi = new TrinityApi(Yii::$app->params['trinitytv']);
		return $trinityApi->updateNoteByDevice($this->device_id, $this->note);
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['device_id'], 'required'],
			[['device_id'], 'integer'],
			[['note'], 'trim'],
			[['note'], 'string', 'max' => 100],
		];
	}
}
