<?php

namespace wfcreations\satellizer\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use wfcreations\satellizer\Satellizer;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $email
 * @property string $password
 * @property string $display_name
 * @property string $facebook
 * @property string $foursquare
 * @property string $github
 * @property string $google
 * @property string $linkedin
 * @property string $twitter
 * @property string $created_at
 * @property string $updated_at
 * @property string $status
 *
 * @property UserProfile $userProfile
 */
class User extends ActiveRecord implements IdentityInterface {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['email'], 'required'],
            [['facebook', 'foursquare', 'github', 'google', 'linkedin', 'twitter'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string'],
            [['email'], 'string', 'max' => 254],
            [['password'], 'string', 'max' => 60],
            [['display_name'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['facebook'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'display_name' => 'Display Name',
            'facebook' => 'Facebook',
            'foursquare' => 'Foursquare',
            'github' => 'Github',
            'google' => 'Google',
            'linkedin' => 'Linkedin',
            'twitter' => 'Twitter',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    public function facebookLink($profile) {
        
    }

    public function getAuthKey() {
        return null;
    }

    public function getId() {
        return $this->id;
    }

    public function validateAuthKey($authKey) {
        return false;
    }

    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        $payload = (array) Satellizer::getComponent()->decodeToken($token);
        return static::findIdentity($payload['sub']);
    }

}
