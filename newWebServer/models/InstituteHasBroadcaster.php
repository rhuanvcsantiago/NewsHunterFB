<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Institute_has_Broadcaster".
 *
 * @property integer $id
 * @property integer $Institute_id
 * @property integer $Broadcaster_id
 * @property string $userName
 * @property string $link
 *
 * @property Institute $institute
 * @property Broadcaster $broadcaster
 * @property InstituteHasBroadcasterHasNews[] $instituteHasBroadcasterHasNews
 * @property News[] $news
 */
class InstituteHasBroadcaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Institute_has_Broadcaster';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Institute_id', 'Broadcaster_id'], 'required'],
            [['Institute_id', 'Broadcaster_id'], 'integer'],
            [['userName'], 'string', 'max' => 45],
            [['link'], 'string', 'max' => 200],
            [['Institute_id'], 'exist', 'skipOnError' => true, 'targetClass' => Institute::className(), 'targetAttribute' => ['Institute_id' => 'id']],
            [['Broadcaster_id'], 'exist', 'skipOnError' => true, 'targetClass' => Broadcaster::className(), 'targetAttribute' => ['Broadcaster_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'Institute_id' => 'Institute ID',
            'Broadcaster_id' => 'Broadcaster ID',
            'userName' => 'User Name',
            'link' => 'Link',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitute()
    {
        return $this->hasOne(Institute::className(), ['id' => 'Institute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBroadcaster()
    {
        return $this->hasOne(Broadcaster::className(), ['id' => 'Broadcaster_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstituteHasBroadcasterHasNews()
    {
        return $this->hasMany(InstituteHasBroadcasterHasNews::className(), ['Institute_has_Broadcaster_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['id' => 'News_id'])->viaTable('Institute_has_Broadcaster_has_News', ['Institute_has_Broadcaster_id' => 'id']);
    }
}
