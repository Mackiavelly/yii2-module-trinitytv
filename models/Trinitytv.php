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

	public $localid;
	public $subscrid;
	public $subscrprice;
	public $subscrstatusid;
	public $contracttrinity;
	public $devicescount;
	public $contractdate;
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
			[['contractdate', 'localid', 'contracttrinity', 'subscrprice',], 'string'],
			[['subscrid', 'subscrstatusid', 'devicescount', 'perpage'], 'integer'],
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
						} else if (!empty($search)) {
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
		$data = Yii::$app->cache->getOrSet('trinitytv', function() {
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
		}, 60);
		return $data;
	}

	/**
	 * @return ArrayDataProvider
	 */
	public function buildAllTvUsersProvider() {
		return new ArrayDataProvider([
			'allModels'  => $this->findAllTvUsers(),
			'modelClass' => self::class,
			'sort'       => [
				'attributes' => $this->attributes(),
			],
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'localid'         => TrinitytvModule::t('trinitytv', 'Contract Partner'),
			'subscrid'        => TrinitytvModule::t('trinitytv', 'Tariff'),
			'subscrprice'     => TrinitytvModule::t('trinitytv', 'Tariff Price'),
			'subscrstatusid'  => TrinitytvModule::t('trinitytv', 'Status'),
			'contracttrinity' => TrinitytvModule::t('trinitytv', 'Contract Trinity'),
			'devicescount'    => TrinitytvModule::t('trinitytv', 'Devices Count'),
			'contractdate'    => TrinitytvModule::t('trinitytv', 'Contract Date'),
		];
	}
}