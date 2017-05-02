<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Broadcaster".
 *
 * @property integer $id
 * @property string $name
 *
 * @property InstituteHasBroadcaster[] $instituteHasBroadcasters
 */
class Broadcaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Broadcaster';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstituteHasBroadcasters()
    {
        return $this->hasMany(InstituteHasBroadcaster::className(), ['Broadcaster_id' => 'id']);
    }
}
