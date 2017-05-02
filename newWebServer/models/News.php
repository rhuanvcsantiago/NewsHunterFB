<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "News".
 *
 * @property integer $id
 * @property integer $access_key
 * @property string $created_time
 * @property string $type
 * @property string $title
 * @property string $content
 * @property string $expanded_content
 * @property string $shared_link
 * @property string $full_picture_link
 *
 * @property InstituteHasBroadcasterHasNews[] $instituteHasBroadcasterHasNews
 * @property InstituteHasBroadcaster[] $instituteHasBroadcasters
 * @property NewsHasTag[] $newsHasTags
 * @property Tag[] $tags
 * @property UserReadNews[] $userReadNews
 * @property User[] $users
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'News';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['access_key'], 'integer'],
            [['created_time'], 'safe'],
            [['title', 'content', 'expanded_content'], 'string'],
            [['type'], 'string', 'max' => 45],
            [['shared_link', 'full_picture_link'], 'string', 'max' => 600],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'access_key' => 'Access Key',
            'created_time' => 'Created Time',
            'type' => 'Type',
            'title' => 'Title',
            'content' => 'Content',
            'expanded_content' => 'Expanded Content',
            'shared_link' => 'Shared Link',
            'full_picture_link' => 'Full Picture Link',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstituteHasBroadcasterHasNews()
    {
        return $this->hasMany(InstituteHasBroadcasterHasNews::className(), ['News_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstituteHasBroadcasters()
    {
        return $this->hasMany(InstituteHasBroadcaster::className(), ['id' => 'Institute_has_Broadcaster_id'])->viaTable('Institute_has_Broadcaster_has_News', ['News_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsHasTags()
    {
        return $this->hasMany(NewsHasTag::className(), ['News_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'Tag_id'])->viaTable('News_has_Tag', ['News_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserReadNews()
    {
        return $this->hasMany(UserReadNews::className(), ['News_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'User_id'])->viaTable('User_read_News', ['News_id' => 'id']);
    }
}
