<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class SupplierSearch extends Supplier
{
    public $id_compare_op;
    public $id_compare_number;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['t_status'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 3],
            [['code'], 'unique'],
            [['name', 'code', 't_status', 'id_compare_op', 'id_compare_number'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Supplier::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['t_status' => $this->t_status])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere([$this->id_compare_op, 'id', $this->id_compare_number]);

        return $dataProvider;
    }
}
