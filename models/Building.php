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
 * @property integer $due_quarter
 * @property integer $due_year
 * @property boolean $is_ready
 */
class Building extends MyModel
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
        $rules = parent::rules();
        $rules = array_merge($rules, [
            [['title', 'address', 'description', 'due_quarter', 'due_year', 'stores_amount'], 'required'],
            [['object_id', 'stores_amount', 'due_quarter', 'due_year', 'is_ready'], 'integer'],
            [['object_id', 'img'], 'safe'],
            [['file'], 'file'],
            [['title', 'img', 'address'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
        ]);
        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels = array_merge($labels, [
            'id' => Yii::t('app', 'ID'),
            'object_id' => Yii::t('app', 'Объект'),
            'title' => Yii::t('app', 'Заголовок'),
            'img' => Yii::t('app', 'Рисунок'),
            'address' => Yii::t('app', 'Адрес'),
            'description' => Yii::t('app', 'Описание'),
            'stores_amount' => Yii::t('app', 'Количество этажей'),
            'due_quarter' => Yii::t('app', 'Квартал сдачи'),
            'due_year' => Yii::t('app', 'Год сдачи'),
            'is_ready' => 'Сдан в эксплуатацию'
        ]);
        return $labels;
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
    public function getStages()
    {
        return $this->hasMany(Stage::className(), ['building_id' => 'id']);
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
        parent::afterSave($insert, $changedAttributes);
        if ($this->file) {
            $imageName = $this->file->baseName . '.' . $this->file->extension;
            $path = Yii::getAlias("@webroot/images/building");
            FileHelper::createDirectory($path);
            $this->file->saveAs($path . '/' . $imageName);
            Image::thumbnail($path . '/' . $imageName, 500, 300)->save($path . '/s-' . $imageName, ['quality' => 100]);
        }
    }
}
