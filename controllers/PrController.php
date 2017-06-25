<?php

namespace app\controllers;

use app\components\Github;
use app\models\PrCollection;
use app\models\PrFilter;
use Github\Client;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class PrController extends Controller
{
	public function actionIndex()
	{
		$prs = PrCollection::fromRepo('yiisoft', 'yii2');

		$filterModel = new PrFilter();
		if ($filterModel->load(Yii::$app->request->get(), '') && $filterModel->validate()) {
			$filteredPrs = $filterModel->filter($prs->getData());
		} else {
			$filteredPrs = $prs->getData();
		}

		$dataProvider = new ArrayDataProvider([
			'allModels' => $filteredPrs,
		]);

		return $this->render('index', [
			'prs' => $dataProvider,
			'filterModel' => $filterModel,
			'milestones' => $prs->getMilestones(),
			'authors' => $prs->getAuthors(),
			'assignees' => $prs->getAssignees(),
			'types' => $prs->getTypes(),
		]);
	}

	/**
	 * @return Github
	 */
	public function getGithub()
	{
		return Yii::$app->github;
	}
}