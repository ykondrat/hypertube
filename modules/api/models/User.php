<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $user_id
 * @property string $user_name
 * @property string $user_secondname
 * @property string $user_email
 * @property string $user_avatar
 * @property string $user_avatar2
 * @property string $user_facebook_id
 * @property string $user_google_id
 * @property string $user_password
 * @property string $user_rep_password
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_name', 'user_secondname', 'user_email'], 'required'],
            [['user_facebook_id'], 'integer'],
            [['user_name', 'user_secondname', 'user_email'], 'string', 'max' => 100],
            [['user_avatar', 'user_avatar2'], 'string', 'max' => 255],
            [['user_google_id'], 'string', 'max' => 30],
            [['user_password', 'user_rep_password'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'user_secondname' => 'User Secondname',
            'user_email' => 'User Email',
            'user_avatar' => 'User Avatar',
            'user_avatar2' => 'User Avatar2',
            'user_facebook_id' => 'User Facebook ID',
            'user_google_id' => 'User Google ID',
            'user_password' => 'User Password',
            'user_rep_password' => 'User Rep Password',
        ];
    }
}
