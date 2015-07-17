<?php

namespace wfcreations\satellizer\models;

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
class User extends \yii\db\ActiveRecord {

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

}
