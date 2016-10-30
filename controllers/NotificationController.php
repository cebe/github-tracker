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
use yii\web\Controller;

class NotificationController extends Controller
{
	public function actionIndex()
	{
		$notifications = $this->getGithub()->client()->notifications()->all();


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