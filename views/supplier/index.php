<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\models\SupplierSearch */

$this->title                   = 'Suppliers';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/js/supplier.js', ['depends' => [JqueryAsset::class]]);
?>
<div class="supplier-index">

	<h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin([]); ?>
	<a class="btn btn-success supplier-export" href="javascript:">Export to CSV</a>
	<div class="alert alert-primary" id="supplier-message-box" style="display: none;"></div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'pager'        => [
            'options' => [
                'class' => 'supplier-page',
            ]
        ],
        'columns'      => [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            [
                'class'           => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return ['value' => $model->id];
                }
            ],
            [
                'label'     => 'Id',
                'attribute' => 'id',
                'filter'    =>
                    Html::dropDownList(
                        'SupplierSearch[id_compare_op]',
                        $searchModel->id_compare_op,
                        ['=' => '=', '>' => '>', '<' => '<', '>=' => '>=', '<=' => '<=',],
                        ['style' => 'width: 70px; display: inline;', 'class' => 'form-control',]) .
                    Html::textInput(
                        'SupplierSearch[id_compare_number]',
                        $searchModel->id_compare_number,
                        [
                            'style'       => 'width: 100px; display: inline;',
                            'class'       => 'form-control',
                            'placeholder' => 'Search Id'
                        ]
                    ),
            ],
            [
                'label'              => 'Name',
                'attribute'          => 'name',
                'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Search Name',
                ]
            ],
            [
                'label'              => 'Code',
                'attribute'          => 'code',
                'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Search Code',
                ]
            ],
            [
                'label'              => 'Status',
                'attribute'          => 't_status',
                'filter'             => [
                    'ok'   => 'ok',
                    'hold' => 'hold'
                ],
                'filterInputOptions' => [
                    'class'  => 'form-control',
                    'prompt' => 'Select Status',
                ]
            ]
        ],
    ]); ?>
    <?php Pjax::end() ?>
	<input type="hidden" name="select_all_records" value="0">
</div>