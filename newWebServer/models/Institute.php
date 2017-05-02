<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Institute".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property resource $image
 *
 * @property InstituteHasBroadcaster[] $instituteHasBroadcasters
 * @property UserFollowInstitute[] $userFollowInstitutes
 * @property User[] $users
 */
class Institute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Institute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image'], 'string'],
            [['name', 'description'], 'string', 'max' => 45],
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
            'description' => 'Description',
            'image' => 'Image',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstituteHasBroadcasters()
    {
        return $this->hasMany(InstituteHasBroadcaster::className(), ['Institute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFollowInstitutes()
    {
        return $this->hasMany(UserFollowInstitute::className(), ['Institute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'User_id'])->viaTable('User_follow_Institute', ['Institute_id' => 'id']);
    }
}
