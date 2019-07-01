<?php

namespace mackiavelly\modules\trinitytv\controllers;

use mackiavelly\modules\trinitytv\models\Trinitytv;
use mackiavelly\modules\trinitytv\models\TrinitytvDevice;
use mackiavelly\modules\trinitytv\models\TrinitytvDeviceCode;
use mackiavelly\modules\trinitytv\models\TrinitytvState;
use mackiavelly\modules\trinitytv\models\TrinitytvTariff;
use mackiavelly\modules\trinitytv\models\TrinitytvUserInfo;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\widgets\ActiveForm;

/**
 * Default controller for the `trinitytv` module
 */
class TrinitytvController extends Controller {
	/**
	 * Renders the index view for the module
	 *
	 * @return string
	 */
	public function actionIndex() {
		$model = new Trinitytv();
		$get = Yii::$app->request->get();
		$dataProvider = $model->search($get);
		Url::remember();
		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'filterModel'  => $model,
		]);
	}

	public function actionState() {
		$model = new TrinitytvState();
		$get = Yii::$app->request->get();
		if ($model->load(Yii::$app->request->post())) {
			if (Yii::$app->request->isAjax) {
				return $this->asJson(ActiveForm::validate($model));
			}
			if ($get['subscrstatusid'] != $model->subscrstatusid) {
				Yii::$app->session->set('trinitytv-alert', $model->changeState());
			}
			Yii::$app->session->set('trinitytv-cache', false);
			return $this->redirect(Url::previous());
		}
		$model->load($get, '');
		return $this->renderAjax('editState', [
			'model' => $model,
		]);
	}

	public function actionTariff() {
		$model = new TrinitytvTariff();
		$get = Yii::$app->request->get();
		if ($model->load(Yii::$app->request->post())) {
			if (Yii::$app->request->isAjax) {
				return $this->asJson(ActiveForm::validate($model));
			}
			if ($get['subscrid'] != $model->subscrid) {
				Yii::$app->session->set('trinitytv-alert', $model->changeTariff());
			}
			Yii::$app->session->set('trinitytv-cache', false);
			return $this->redirect(Url::previous());
		}
		$model->load($get, '');
		return $this->renderAjax('editTariff', [
			'model' => $model,
		]);
	}

	public function actionUserInfo() {
		$model = new TrinitytvUserInfo();
		$get = Yii::$app->request->get();
		if ($model->load(Yii::$app->request->post())) {
			if (Yii::$app->request->isAjax) {
				return $this->asJson(ActiveForm::validate($model));
			}
			Yii::$app->session->set('trinitytv-alert', $model->changeUserInfo());
			Yii::$app->session->set('trinitytv-cache', true);
			return $this->redirect(Url::previous());
		}
		$model->load($get, '');
		return $this->renderAjax('editUserInfo', [
			'model' => $model,
		]);
	}

	public function actionAddDevice() {
		$model = new TrinitytvDevice();
		$get = Yii::$app->request->get();
		if ($model->load(Yii::$app->request->post())) {
			if (Yii::$app->request->isAjax) {
				Yii::warning(ActiveForm::validate($model));
				return $this->asJson(ActiveForm::validate($model));
			}
			Yii::$app->session->set('trinitytv-alert', $model->addDevice());
			Yii::$app->session->set('trinitytv-cache', false);
			return $this->redirect(Url::previous());
		}
		$model->load($get, '');
		return $this->renderAjax('addDevice', [
			'model' => $model,
		]);
	}

	public function actionAddDeviceCode() {
		$model = new TrinitytvDeviceCode();
		$get = Yii::$app->request->get();
		if ($model->load(Yii::$app->request->post())) {
			if (Yii::$app->request->isAjax) {
				Yii::warning(ActiveForm::validate($model));
				return $this->asJson(ActiveForm::validate($model));
			}
			Yii::$app->session->set('trinitytv-alert', $model->addDeviceCode());
			Yii::$app->session->set('trinitytv-cache', false);
			return $this->redirect(Url::previous());
		}
		$model->load($get, '');
		return $this->renderAjax('addDeviceCode', [
			'model' => $model,
		]);
	}

	public function actionDeleteDevice() {
		$get = Yii::$app->request->get();
		$model = new TrinitytvDevice();
		$model->load($get, '');
		$model->deleteDevice();
		return $this->actionFullInfo();
	}

	public function actionFullInfo() {
		$model = new Trinitytv();
		$model->load(Yii::$app->request->get(), '');
		Yii::$app->session->set('trinitytv-cache', false);
		return $this->renderAjax('fullInfo', [
			'model' => $model->fullInfo(),
		]);
	}

	public function actionAddPlayList() {
		$get = Yii::$app->request->get();
		$model = new TrinitytvDevice();
		$model->load($get, '');
		$model->addPlayList();
		return $this->actionFullInfo();
	}
}
