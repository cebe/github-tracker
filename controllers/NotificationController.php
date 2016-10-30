<?php
/**
 *
 *
 * @author Carsten Brandt <mail@cebe.cc>
 */

namespace app\controllers;


use Github\Client;
use yii\web\Controller;

class NotificationController extends Controller
{
	public function actionIndex()
	{
		// TODO cache

//		$api = new Client();
//		$api
//
		return $this->render('index', [

		]);
	}
}