<?php

namespace app\models;

use Yii;
use app\models\Objects;
use app\models\Entry;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;

/**
 * This is the model class for table "building".
 *
 * @property integer $id
 * @property integer $object_id
 * @property string $title
 * @property string $img
 * @property string $address
 * @property string $description
 * @property integer $stores_amount
 */
class Building extends \yii\db\ActiveRecord
{
    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'building';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'address', 'description', 'stores_amount'], 'required'],
            [['object_id', 'stores_amount'], 'integer'],
            [['object_id', 'img'], 'safe'],
            [['file'], 'file'],
            [['title', 'img', 'address'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'object_id' => Yii::t('app', 'Объект'),
            'title' => Yii::t('app', 'Заголовок'),
            'img' => Yii::t('app', 'Рисунок'),
            'address' => Yii::t('app', 'Адрес'),
            'description' => Yii::t('app', 'Описание'),
            'stores_amount' => Yii::t('app', 'Количество этажей'),
        ];
    }

    public function getObject()
    {
        return $this->hasOne(Objects::className(), ['id' => 'object_id']);
    }

    public function getEntry()
    {
        return $this->hasMany(Entry::className(), ['building_id' => 'id'])->orderBy('number');
    }

    public function getApartments()
    {
        return $this->hasMany(Apartment::className(), ['building_id' => 'id']);
    }

    public function getEntryCount()
    {
        return $this->hasMany(Entry::className(), ['building_id' => 'id'])->count();
    }

    public function getApartmentCount()
    {
        return $this->hasMany(Apartment::className(), ['building_id' => 'id'])->count();
    }

    public function beforeValidate()
    {
        $this->file = UploadedFile::getInstance($this, 'file');
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if ($this->file)
            $this->img = $this->file->baseName . '.' . $this->file->extension;
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->file) {
            $imageName = $this->file->baseName . '.' . $this->file->extension;
            $path = Yii::getAlias("@webroot/images/building");
            FileHelper::createDirectory($path);
            $this->file->saveAs($path . '/' . $imageName);
            Image::thumbnail($path . '/' . $imageName, 500, 300)->save($path . '/s-' . $imageName, ['quality' => 100]);
        }
    }
}
