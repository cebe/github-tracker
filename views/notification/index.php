<?php

/* @var $this yii\web\View */
/* @var $notifications \yii\data\BaseDataProvider */

$this->title = 'Github Notifications';
?>
<div class="notification-index">

    <h1>Github Notifications</h1>

    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $notifications,
        'itemView' => '_view',
    ]) ?>

</div>
