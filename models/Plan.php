<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\helpers\Url;
use app\models\Room;

/**
 * This is the model class for table "Plan".
 *
 * @property integer $id
 * @property string $title
 * @property string $img
 */

/**
 * This is the model class for table "plan".
 *
 * @property integer $id
 * @property string $title
 * @property string $img
 * @property double $area
 * @property string $rooms
 * @property integer $room_count
 * @property integer $owner_id
 */
class Plan extends \yii\db\ActiveRecord
{

   public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'plan';
    }

    /**
     * @inheritdoc
     */
        /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'room_count'], 'required'],
            [['img', 'area', 'rooms', 'owner_id','company_id'], 'safe'],
            [['title', 'img'], 'string', 'max' => 255],
            [['room_count'], 'integer'],
            [['area'], 'number'],
            [['file'], 'file'],
        ];
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
            $path = Yii::getAlias("@webroot/images/plan/{$this->id}");
            FileHelper::createDirectory($path);
            $this->file->saveAs($path . '/' . $imageName);
            Image::thumbnail($path . '/' . $imageName, 250, 160)->save($path . '/s-' . $imageName, ['quality' => 100]);
        }
    }


    public function getImageFile()
    {
        return isset($this->img) ? Url::base() . '/images/plan/' . $this->id . '/' . $this->img : null;
    }

    public function getThumbFile()
    {
        return isset($this->img) ? Url::base() . '/images/plan/' . $this->id . '/s-' . $this->img : null;
    }

    public function getImageUrl()
    {
        // return a default image placeholder if your source avatar is not found
        $img = isset($this->img) ? $this->img : 'default_user.jpg';
        return Yii::$app->params['uploadUrl'] . '/plan/' . $img;
    }

    public function uploadImage()
    {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $image = UploadedFile::getInstance($this, 'image');

        // if no image was uploaded abort the upload
        if (empty($image)) {
            return false;
        }

        // store the source file name
        $this->img = time() . '.' . $image->extension;

        // the uploaded image instance
        return $image;
    }

    public function deleteImage()
    {
        $file = $this->getImageFile();
        // check if file exists on server
        if (empty($file) || !file_exists($file)) {
            return false;
        }

        // check if uploaded file can be deleted on server
        if (!unlink($file)) {
            return false;
        }
        // if deletion successful, reset your file attributes
        $this->img = null;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Названия плана'),
            'file' => Yii::t('app', 'Рисунок'),
            'dollar_price' => Yii::t('app', 'Ориентировочная цена в долларах'),
            'som_price' => Yii::t('app', 'Ориентировочная цена в сомах'),
            'room_count' => Yii::t('app', 'Количество комнат'),
            'area' => Yii::t('app', 'Общая площадь'),
        ];
    }
}
