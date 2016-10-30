<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidCallException;


class Github extends Component
{
	/**
	 * @return \Github\Client
	 */
	public function client()
	{
		if (Yii::$app->user->isGuest) {
			throw new InvalidCallException('Can not create github client for not logged in user.');
		}

		// create client
		$client = new \Github\HttpClient\CachedHttpClient();
		$client->setCache(new \Github\HttpClient\Cache\FilesystemCache(Yii::getAlias('@runtime/github-cache')));
		$client = new \Github\Client($client);

		/** @var \yii\authclient\clients\GitHub $authClient */
		$authClient = Yii::$app->authClientCollection->getClient('github');
		$accessToken = $authClient->getAccessToken()->getToken();

		// authenticate
		$client->authenticate($accessToken, '', \Github\Client::AUTH_HTTP_TOKEN);

		return $client;
	}

}
