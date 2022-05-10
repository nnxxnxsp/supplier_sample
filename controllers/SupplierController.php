<?php

namespace app\controllers;

use app\models\Supplier;
use Yii;
use app\models\SupplierSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SupplierController implements the CRUD actions for Supplier model.
 */
class SupplierController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Supplier models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel  = new SupplierSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * @throws Exception
     */
    public function actionExportToCsv()
    {
        $request = Yii::$app->request;
        $params  = $request->post();
        if (isset($params['selected_ids']) && !empty($params['selected_ids'])) {
            $selectedIds = array_filter(explode(',', $params['selected_ids']));
            if (empty($selectedIds)) {
                throw new Exception(Yii::t('app', 'Invalid selected ids.'));
            }
            $query = Supplier::find()
                ->where(['in', 'id', $selectedIds]);
        } else {
            $query = Supplier::find();
            if (isset($params['t_status']) && !empty($params['t_status'])) {
                $query->andWhere(['=', 't_status', $params['t_status']]);
            }
            if (isset($params['name']) && !empty($params['name'])) {
                $query->andWhere(['like', 'name', $params['name']]);
            }
            if (isset($params['code']) && !empty($params['code'])) {
                $query->andWhere(['like', 'code', $params['code']]);
            }
            if (isset($params['id_compare_number']) && !empty($params['id_compare_number']) &&
                isset($params['id_compare_op']) && !empty($params['id_compare_op'])) {
                $query->andWhere([$params['id_compare_op'], 'id', $params['id_compare_number']]);
            }
        }

        $fileName = sys_get_temp_dir() . '/SupplierExport_' . date('YmdHis') . '.csv';
        $fp       = fopen($fileName, 'w');
        //BOM
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($fp, ['Id', 'Name', 'Code', 'Status']);
        foreach ($query->each(10000) as $supplier) {
            fputcsv($fp, [$supplier->id, $supplier->name, $supplier->code, $supplier->t_status]);
        }

        Yii::$app->response->on(Response::EVENT_AFTER_SEND, function ($event) {
            unlink($event->data);
        }, $fileName);

        return Yii::$app->response->sendFile($fileName);
    }
}
