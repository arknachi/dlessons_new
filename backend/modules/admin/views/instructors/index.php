<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DlInstructorsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Instructors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dl-instructors-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p style="text-align: right;">
        <?= Html::a('Create Instructor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'instructor_id',
            // 'admin_id',
            [
                'attribute' => 'username',
                'value' => 'username',
                'filter' => true,
                'enableSorting' => true,
            ],
            // 'password',
            'first_name',
            'last_name',
            'email:email',
            // 'address1',
            // 'address2',
            // 'website',
            // 'city',
            // 'state',
            // 'zip',
            // 'work_phone',
            'cell_phone',
            // 'notes:ntext',
            // 'status',
            // 'remember_token',
            // 'created_at',
            // 'modified_at',
            // 'auth_key',
            [
                'attribute' => 'updated_at',
                'value' => 'updated_at',
                'filter' => false,
                'enableSorting' => false,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}&nbsp;&nbsp;&nbsp;{delete}',
            ],
        ],
    ]);
    ?>
</div>
