<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "User_follow_Institute".
 *
 * @property integer $User_id
 * @property integer $Institute_id
 *
 * @property Institute $institute
 * @property User $user
 */
class UserFollowInstitute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'User_follow_Institute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['User_id', 'Institute_id'], 'required'],
            [['User_id', 'Institute_id'], 'integer'],
            [['Institute_id'], 'exist', 'skipOnError' => true, 'targetClass' => Institute::className(), 'targetAttribute' => ['Institute_id' => 'id']],
            [['User_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['User_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'User_id' => 'User ID',
            'Institute_id' => 'Institute ID',
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'User_id']);
    }
}
