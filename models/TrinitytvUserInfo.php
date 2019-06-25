<?php


namespace mackiavelly\modules\trinitytv\models;

use mackiavelly\modules\trinitytv\TrinitytvModule;
use mackiavelly\trinitytv\TrinityApi;
use Yii;
use yii\base\Model;

class TrinitytvUserInfo extends Model {

	public $localid;
	public $firstName;
	public $lastName;
	public $middleName;
	public $address;

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['localid', 'firstName', 'lastName', 'middleName', 'address'], 'required'],
			[['localid'], 'integer'],
			[['firstName', 'lastName', 'middleName', 'address'], 'trim'],
			[['firstName', 'lastName', 'middleName', 'address'], 'string', 'min' => 1, 'max' => 25],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'localid'    => TrinitytvModule::t('trinitytv', 'Contract Partner'),
			'firstName'  => TrinitytvModule::t('trinitytv', 'First Name'),
			'lastName'   => TrinitytvModule::t('trinitytv', 'Last Name'),
			'middleName' => TrinitytvModule::t('trinitytv', 'Middle Name'),
			'address'    => TrinitytvModule::t('trinitytv', 'Address'),
		];
	}

	public function changeUserInfo() {
		$trinityApi = new TrinityApi(Yii::$app->params['trinitytv']);
		return $trinityApi->updateUser($this->localid, $this->firstName, $this->lastName, $this->middleName, $this->address);
	}
}
