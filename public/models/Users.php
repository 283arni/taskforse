<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property int $city_id
 * @property string $password
 * @property string $dt_add
 * @property int $blocked
 * @property string|null $last_activity
 *
 * @property Bookmarks[] $bookmarks
 * @property Bookmarks[] $bookmarks0
 * @property Cities $city
 * @property Events[] $events
 * @property Files[] $files
 * @property Messages[] $messages
 * @property Messages[] $messages0
 * @property Opinions[] $opinions
 * @property Opinions[] $opinions0
 * @property Replies[] $replies
 * @property UserCategories[] $userCategories
 * @property UserSettings $userSettings
 */
class Users extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['last_activity'], 'default', 'value' => null],
            [['blocked'], 'default', 'value' => 0],
            [['email', 'name', 'city_id', 'password'], 'required'],
            [['city_id', 'blocked'], 'integer'],
            [['dt_add', 'last_activity'], 'safe'],
            [['email', 'name'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 64],
            [['email'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'name' => 'Name',
            'city_id' => 'City ID',
            'password' => 'Password',
            'dt_add' => 'Dt Add',
            'blocked' => 'Blocked',
            'last_activity' => 'Last Activity',
        ];
    }

    /**
     * Gets query for [[Bookmarks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarks()
    {
        return $this->hasMany(Bookmarks::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Bookmarks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarks0()
    {
        return $this->hasMany(Bookmarks::class, ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Events]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Events::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(Files::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::class, ['recipient_id' => 'id']);
    }

    /**
     * Gets query for [[Messages0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(Messages::class, ['sender_id' => 'id']);
    }

    /**
     * Gets query for [[Opinions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOpinions()
    {
        return $this->hasMany(Opinions::class, ['owner_id' => 'id']);
    }

    /**
     * Gets query for [[Opinions0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOpinions0()
    {
        return $this->hasMany(Opinions::class, ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[Replies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Replies::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategories::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserSettings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserSettings()
    {
        return $this->hasOne(UserSettings::class, ['user_id' => 'id']);
    }

}
