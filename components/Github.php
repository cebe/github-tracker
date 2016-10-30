<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;


class Github extends Component
{
	/**
	 * @return \Github\Client
	 */
	public function client()
	{
		// create client
		$client = new \Github\HttpClient\CachedHttpClient();
		$client->setCache(new \Github\HttpClient\Cache\FilesystemCache(__DIR__ . '/../tmp/github-cache'));
		$client = new \Github\Client($client);

		if (empty(Yii::$app->params['github_token'])) {
			throw new Exception('Config param "github_token" is not configured!');
		}
		if (empty(Yii::$app->params['github_username'])) {
			throw new Exception('Config param "github_username" is not configured!');
		}

		// authenticate
		$client->authenticate(Yii::$app->params['github_token'], '', \Github\Client::AUTH_HTTP_TOKEN);

		return $client;
	}

}
