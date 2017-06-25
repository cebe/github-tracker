<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidCallException;


class Github extends Component
{
	/**
	 * @return \Github\Client
	 */
	public function client()
	{
		if (Yii::$app->user->isGuest) {
			// TODO auto-relogin
			throw new InvalidCallException('Can not create github client for not logged in user.');
		}

		// create client
		$client = new \Github\Client();

		/** @var \yii\authclient\clients\GitHub $authClient */
		$authClient = Yii::$app->authClientCollection->getClient('github');
		$accessToken = $authClient->getAccessToken()->getToken();

		// authenticate
		$client->authenticate($accessToken, '', \Github\Client::AUTH_HTTP_TOKEN);

		return $client;
	}
}
