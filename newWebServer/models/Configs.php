<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configs".
 *
 * @property integer $institute_id
 * @property string $institute_name
 * @property integer $broadcaster_id
 * @property string $broadcaster_name
 * @property string $userName
 * @property integer $ib_id
 * @property string $link
 * @property integer $last_news_access_key
 */
class Configs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'configs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['institute_id', 'broadcaster_id', 'ib_id', 'last_news_access_key'], 'integer'],
            [['institute_name', 'broadcaster_name', 'userName'], 'string', 'max' => 45],
            [['link'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'institute_id' => 'Institute ID',
            'institute_name' => 'Institute Name',
            'broadcaster_id' => 'Broadcaster ID',
            'broadcaster_name' => 'Broadcaster Name',
            'userName' => 'User Name',
            'ib_id' => 'Ib ID',
            'link' => 'Link',
            'last_news_access_key' => 'Last News Access Key',
        ];
    }
}
