<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "User".
 *
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $googleAUTH
 * @property string $facebookAUTH
 * @property integer $sendEmail
 * @property integer $sendNotifications
 *
 * @property UserFollowInstitute[] $userFollowInstitutes
 * @property Institute[] $institutes
 * @property UserReadNews[] $userReadNews
 * @property News[] $news
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'User';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sendEmail', 'sendNotifications'], 'integer'],
            [['email', 'password', 'googleAUTH', 'facebookAUTH'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'googleAUTH' => 'Google Auth',
            'facebookAUTH' => 'Facebook Auth',
            'sendEmail' => 'Send Email',
            'sendNotifications' => 'Send Notifications',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFollowInstitutes()
    {
        return $this->hasMany(UserFollowInstitute::className(), ['User_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitutes()
    {
        return $this->hasMany(Institute::className(), ['id' => 'Institute_id'])->viaTable('User_follow_Institute', ['User_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserReadNews()
    {
        return $this->hasMany(UserReadNews::className(), ['User_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['id' => 'News_id'])->viaTable('User_read_News', ['User_id' => 'id']);
    }
}
