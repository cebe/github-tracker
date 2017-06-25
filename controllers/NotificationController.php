<?php
/**
 *
 *
 * @author Carsten Brandt <mail@cebe.cc>
 */

namespace app\controllers;


use app\components\Github;
use Github\Client;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

class NotificationController extends Controller
{
	public function actionIndex()
	{
		// https://developer.github.com/v3/activity/notifications/
		$notifications = $this->getGithub()->client()->notifications()->all();

		$notifications = new ArrayDataProvider([
			'allModels' => $notifications,
		]);

		// filter by:
		// - type (Issue/PR)
		// - reason (author, mention, subscribed, ...)
		// - repo

		return $this->render('index', [
			'notifications' => $notifications,
		]);
	}

	/**
	 * @return Github
	 */
	protected function getGithub()
	{
		return Yii::$app->github;
	}
}