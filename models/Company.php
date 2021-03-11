<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $email
 * @property string $img
 * @property integer $owner_id
 */
class Company extends \yii\db\ActiveRecord
{

    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['phone', 'address', 'email', 'img', 'owner_id'], 'safe'],
            [['name', 'phone', 'address', 'email', 'img'], 'string', 'max' => 255],
            [['file'], 'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название компании',
            'phone' => 'Телефон',
            'address' => 'Адрес',
            'email' => 'Email',
            'file' => 'Логотип',
            'owner_id' => 'Пользователь'
        ];
    }

    public function beforeValidate()
    {
        $this->file = UploadedFile::getInstance($this, 'file');
        return parent::beforeValidate();
    }

    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    public function beforeSave($insert)
    {
        if ($this->file)
            $this->img = $this->file->baseName . '.' . $this->file->extension;

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $user = User::find()->where(['id' => Yii::$app->user->id])->one();
        $user->company_id = $this->id;
        $user->save();
        if ($this->file) {
            $imageName = $this->file->baseName . '.' . $this->file->extension;
            $path = Yii::getAlias("@webroot/images/company/");
            FileHelper::createDirectory($path);
            $this->file->saveAs($path . '/' . $imageName);
        }
    }
}
