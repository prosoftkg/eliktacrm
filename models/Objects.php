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
 * @property float $base_dollar_price
 * @property float $base_som_price
 * @property integer $city
 * @property string $description
 * @property integer $company_id
 * @property float $lat
 * @property float $lng
 * @property string $img
 */
class Objects extends MyModel
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
        $rules = parent::rules();
        $rules = array_merge($rules, [
            [['title', 'base_dollar_price', 'base_som_price', 'city', 'description'], 'required'],
            [['base_dollar_price', 'base_som_price', 'lat', 'lng'], 'number'],
            [['file'], 'file'],
            [['company_id'], 'integer'],
            [['title', 'logo', 'city', 'img'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
        ]);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels = array_merge($labels, [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Название объекта'),
            'logo' => Yii::t('app', 'Логотип'),
            'base_dollar_price' => Yii::t('app', 'Цена в долларах (м²)'),
            'base_som_price' => Yii::t('app', 'Цена в сомах (м²)'),
            'city' => Yii::t('app', 'Город'),
            'description' => Yii::t('app', 'Описание'),
            'company_id' => Yii::t('app', 'Компания'),
            'apartment_id' => Yii::t('app', 'Сделка'),
            'lat' => 'Широта',
            'lng' => 'Долгота',
            'file' => 'Фотографии',
        ]);
        return $labels;
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    public function getBuilding()
    {
        return $this->hasMany(Building::className(), ['object_id' => 'id']);
    }

    public function getApartments()
    {
        return $this->hasMany(Apartment::className(), ['object_id' => 'id']);
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
        parent::afterSave($insert, $changedAttributes);
        if ($this->file) {
            $imageName = $this->file->baseName . '.' . $this->file->extension;
            $path = Yii::getAlias("@webroot/images/object");
            FileHelper::createDirectory($path);
            $this->file->saveAs($path . '/' . $imageName);
            Image::thumbnail($path . '/' . $imageName, 500, 300)->save($path . '/' . $imageName, ['quality' => 100]);
        }

        if ($insert || !empty($changedAttributes['base_dollar_price']) || !empty($changedAttributes['base_som_price'])) {
            $apts = Apartment::find()->where(['object_id' => $this->id, 'base_dollar_price_custom' => null])->with('plan')->all();
            foreach ($apts as $apt) {
                if ($apt->plan) {
                    $apt->dollar_price = $this->base_dollar_price * $apt->plan->area;
                    $apt->som_price = $this->base_som_price * $apt->plan->area;
                    $apt->save();
                }
            }
        }
    }
}
