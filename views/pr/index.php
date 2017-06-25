<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

/* @var $prs \yii\data\BaseDataProvider */
/* @var $filterModel \app\models\PrFilter */
/* @var $milestones array */
/* @var $authors array */
/* @var $assignees array */
/* @var $types array */

$this->title = 'Github Notifications';
?>
<div class="pr-index">

    <h1>Pull Requests</h1>

    <div>
        Filters:
        <?php $form = \yii\bootstrap\ActiveForm::begin([
            'action' => [''],
            'method' => 'get',
            'layout' => 'inline',
        ]) ?>

            <?= $form->field($filterModel, 'milestone')->dropDownList($milestones, ['prompt' => '']) ?>
            <?= $form->field($filterModel, 'author')->dropDownList($authors, ['prompt' => '']) ?>
            <?= $form->field($filterModel, 'assignee')->dropDownList($assignees, ['prompt' => '']) ?>
            <?= $form->field($filterModel, 'type')->dropDownList($types, ['prompt' => '']) ?>
            <?= Html::submitButton('Filter') ?>

        <?php \yii\bootstrap\ActiveForm::end() ?>

    </div>


    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $prs,
        'itemView' => '_view',
    ]) ?>

</div>
