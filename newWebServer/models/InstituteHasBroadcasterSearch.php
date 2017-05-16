<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InstituteHasBroadcaster;

/**
 * InstituteHasBroadcasterSearch represents the model behind the search form about `app\models\InstituteHasBroadcaster`.
 */
class InstituteHasBroadcasterSearch extends InstituteHasBroadcaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['userName', 'link', 'institute.name', 'broadcaster.name'], 'safe'],
        ];
    }

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['institute.name', 'broadcaster.name']);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = InstituteHasBroadcaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        
        $query->joinWith('broadcaster as broadcaster');
        $query->joinWith('institute as institute');
       
        $dataProvider->sort->attributes['broadcaster.name'] = [
            'asc' => ['broadcaster.name' => SORT_ASC],
            'desc' => ['broadcaster.name' => SORT_DESC],
        ];
       
        $dataProvider->sort->attributes['institute.name'] = [
            'asc' => ['institute.name' => SORT_ASC],
            'desc' => ['institute.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'Institute_id' => $this->Institute_id,
            'Broadcaster_id' => $this->Broadcaster_id,
        ]);

        $query->andFilterWhere(['like', 'userName', $this->userName])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'institute.name', $this->getAttribute('institute.name')])
            ->andFilterWhere(['like', 'broadcaster.name', $this->getAttribute('broadcaster.name')]);


        return $dataProvider;
        
        

    }
}
