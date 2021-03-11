<?php

namespace app\models;

use Yii;
use app\models\Company;
use app\models\Building;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;

/**
 * This is the model class for table "object".
 *
 * @property integer $id
 * @property string $title
 * @property string $logo
 * @property double $base_dollar_price
 * @property double $base_som_price
 * @property string $city
 * @property integer $due_quarter
 * @property integer $due_year
 * @property string $description
 * @property integer $company_id
 */
class Objects extends \yii\db\ActiveRecord
{
    public $file;
    const CITY_BISHKEK = "Бишкек";
    const CITY_OSH = "Ош";
    public static $cities = [self::CITY_BISHKEK, self::CITY_OSH];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'base_dollar_price', 'base_som_price', 'city', 'due_quarter', 'due_year', 'description'], 'required'],
            [['base_dollar_price', 'base_som_price'], 'number'],
            [['file'], 'file'],
            [['due_quarter', 'due_year', 'company_id'], 'integer'],
            [['title', 'logo', 'city'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
        ];
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    public function getBuilding()
    {
        return $this->hasMany(Building::className(), ['object_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Название объекта'),
            'logo' => Yii::t('app', 'Логотип'),
            'base_dollar_price' => Yii::t('app', 'Цена в долларах'),
            'base_som_price' => Yii::t('app', 'Цена в сомах'),
            'city' => Yii::t('app', 'Город'),
            'due_quarter' => Yii::t('app', 'Квартал сдачи'),
            'due_year' => Yii::t('app', 'Год сдачи'),
            'description' => Yii::t('app', 'Описание'),
            'company_id' => Yii::t('app', 'Компания'),
            'apartment_id' => Yii::t('app', 'Сделка'),
            'file' => 'Фотографии',
        ];
    }

    public function beforeValidate()
    {
        $this->file = UploadedFile::getInstance($this, 'file');
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if ($this->file) {
            $this->logo = $this->file->baseName . '.' . $this->file->extension;
        }
        if ($this->isNewRecord) {
            $this->company_id = Yii::$app->user->identity->company_id;
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->file) {
            $imageName = $this->file->baseName . '.' . $this->file->extension;
            $path = Yii::getAlias("@webroot/images/object");
            FileHelper::createDirectory($path);
            $this->file->saveAs($path . '/' . $imageName);
            Image::thumbnail($path . '/' . $imageName, 500, 300)->save($path . '/' . $imageName, ['quality' => 100]);
        }
    }
}
