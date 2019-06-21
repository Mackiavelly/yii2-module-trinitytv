<?php

namespace mackiavelly\modules\trinitytv;

use Yii;
use yii\base\Module;

/**
 * trinitytv module definition class
 */
class TrinitytvModule extends Module {
	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'mackiavelly\modules\trinitytv\controllers';

	/**
	 * {@inheritdoc}
	 */
	public $defaultRoute = 'trinitytv';

	/**
	 * I18N helper
	 *
	 * @param string      $category
	 * @param string      $message
	 * @param array       $params
	 * @param null|string $language
	 * @return string
	 */
	public static function t($category, $message, $params = [], $language = null) {
		return Yii::t('modules/trinitytv/'.$category, $message, $params, $language);
	}

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		parent::init();
		$this->registerTranslations();
	}

	public function registerTranslations() {
		if (!isset(Yii::$app->i18n->translations['modules/trinitytv/*'])) {
			Yii::$app->i18n->translations['modules/trinitytv/*'] = [
				'class'          => 'yii\i18n\PhpMessageSource',
				'sourceLanguage' => 'en-US',
				'basePath'       => __DIR__.'/messages',
				'fileMap'        => [
					'modules/trinitytv/trinitytv' => 'trinitytv.php',
				],
			];
		}
	}
}
