<?php

namespace mackiavelly\modules\trinitytv\models;

use mackiavelly\modules\trinitytv\TrinitytvModule;
use mackiavelly\trinitytv\TrinityApi;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

class Trinitytv extends Model {

	const STATUS = [
		0   => ['name' => 'Active', 'class' => 'text-success'],
		768 => ['name' => 'Block', 'class' => 'text-danger'],
	];
	public static $cacheExpire = 60;

	public $localid;
	public $subscrid;
	public $subscrname;
	public $subscrprice;
	public $subscrstatusid;
	public $subscrstatus;
	public $contracttrinity;
	public $devicescount;
	public $contractdate;
	public $devices = [];
	public $last_session_date;
	public $note;
	public $middlename;
	public $name;
	public $lastname;
	public $address;
	public $balance;
	public $login_code;
	public $perpage = 10;

	private $_filtered = false;

	public function getStatus($id = null) {
		if (empty($id)) {
			$result = ArrayHelper::getColumn($this::STATUS, 'name');
			foreach ($result as &$item) {
				$item = TrinitytvModule::t('trinitytv', $item);
			}
			return $result;
		}
		if (isset($this::STATUS[$id])) {
			return TrinitytvModule::t('trinitytv', $this::STATUS[$id]['name']);
		}
		return null;
	}

	public function rules() {
		return [
			[['contractdate', 'localid', 'contracttrinity', 'subscrprice', 'subscrname', 'subscrstatus'], 'string'],
			[['subscrid', 'subscrstatusid', 'devicescount', 'perpage'], 'integer'],
			[['devices', 'last_session_date', 'note', 'middlename', 'name', 'lastname', 'address', 'balance', 'login_code'], 'safe'],
		];
	}

	public function search($params) {
		/**
		 * $params is the array of GET parameters passed in the actionExample().
		 * These are being loaded and validated.
		 * If validation is successful _filtered property is set to true to prepare
		 * data source. If not - data source is displayed without any filtering.
		 */
		if ($this->load($params) && $this->validate()) {
			$this->_filtered = true;
		}

		return new ArrayDataProvider([
			'allModels'  => $this->getData(),
			'modelClass' => self::class,
			'pagination' => [
				'defaultPageSize' => $this->perpage,
				'pageSizeLimit'   => false,
			],
			'sort'       => [
				'attributes' => $this->attributes(),
			],
		]);
	}

	protected function getData() {
		$data = $this->findAllTvUsers();
		if ($this->_filtered) {
			$data = array_filter($data, function($value) {
				$conditions = [true];
				foreach ($this->toArray() as $attribute => $search) {
					if (isset($value[$attribute])) {
						if (in_array($attribute, ['subscrid', 'subscrstatusid', 'devicescount']) && ($search != null)) {
							$conditions[] = (int) $value[$attribute] == (int) $search;
						} elseif (!empty($search)) {
							$conditions[] = strpos($value[$attribute], $search) !== false;
						}
					}
				}
				return array_product($conditions);
			});
		}
		return $data;
	}

	/**
	 * @return bool|mixed
	 */
	public function findAllTvUsers() {
		if (Yii::$app->session->get('trinitytv-cache') === false) {
			$data = $this->buildTrinitytvData();
			Yii::$app->cache->set('trinitytv-data', $data, $this::$cacheExpire);
		} else {
			$data = Yii::$app->cache->getOrSet('trinitytv-data', function() {
				return $this->buildTrinitytvData();
			}, $this::$cacheExpire);
		}
		Yii::$app->session->set('trinitytv-cache', true);
		return $data;
	}

	public function buildTrinitytvData() {
		$trinityApi = new TrinityApi(Yii::$app->params['trinitytv']);
		$data = $trinityApi->subscriberList();
		if ($data['result'] == 'success') {
			foreach ($data['subscribers'] as $index => &$datum) {
				$datum['localid'] = $index;
			}
			return $data['subscribers'];
		} else {
			return [];
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'localid'           => TrinitytvModule::t('trinitytv', 'Contract Partner'),
			'subscrid'          => TrinitytvModule::t('trinitytv', 'Tariff'),
			'subscrname'        => TrinitytvModule::t('trinitytv', 'Tariff Name'),
			'subscrprice'       => TrinitytvModule::t('trinitytv', 'Tariff Price'),
			'subscrstatusid'    => TrinitytvModule::t('trinitytv', 'State'),
			'subscrstatus'      => TrinitytvModule::t('trinitytv', 'State Name'),
			'contracttrinity'   => TrinitytvModule::t('trinitytv', 'Contract Trinity'),
			'devicescount'      => TrinitytvModule::t('trinitytv', 'Devices Count'),
			'contractdate'      => TrinitytvModule::t('trinitytv', 'Contract Date'),
			'devices'           => TrinitytvModule::t('trinitytv', 'Devices'),
			'last_session_date' => TrinitytvModule::t('trinitytv', 'Last Session Date'),
			'note'              => TrinitytvModule::t('trinitytv', 'Note'),
			'middlename'        => TrinitytvModule::t('trinitytv', 'Middle Name'),
			'name'              => TrinitytvModule::t('trinitytv', 'First Name'),
			'lastname'          => TrinitytvModule::t('trinitytv', 'Last Name'),
			'address'           => TrinitytvModule::t('trinitytv', 'Address'),
			'balance'           => TrinitytvModule::t('trinitytv', 'Balance'),
			'login_code'        => TrinitytvModule::t('trinitytv', 'Login Code'),
		];
	}

	public function fullInfo() {
		$trinityApi = new TrinityApi(Yii::$app->params['trinitytv']);
		$response = $trinityApi->subscriptionInfo($this->localid);
		if ($response['result'] == 'success') {
			$this->load($response['subscriptions'], '');
			if ($this->devicescount != 0) {
				$response = $trinityApi->deviceList($this->localid);
				if ($response['result'] == 'success') {
					$this->devices = $response['devices'];
				}
			} else {
				$this->devices = [];
			}
		}
		return $this;
	}
}
