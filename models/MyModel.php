<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;
use yii\helpers\Html;

/**
 * @property UploadedFile imageFile
 */
class MyModel extends ActiveRecord
{
    public $imageFile;
    public $imageFiles = [];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['imageFile'], 'file', 'extensions' => 'png, jpg,jpeg'],
            [['imageFiles'], 'file', 'extensions' => 'jpg,jpeg,gif,png', 'maxSize' => 20 * 1024 * 1024, 'maxFiles' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'imageFile' => Yii::t('app', 'Image'),
            'imageFiles' => Yii::t('app', 'Images'),
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->saveImage();
        //Source::optimizeImage($this->date_from,$this->id,'car'); //uncomment after installing jpegoptim and optipng
    }


    /**
     * @param UploadedFile $imageFile
     * @param string $imgname
     * @param int $id
     */
    protected function saveImage()
    {
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
        $this->imageFiles = UploadedFile::getInstances($this, 'imageFiles');
        if ($this->imageFile || $this->imageFiles) {

            $end_folder = $this->makeDir();

            if ($this->imageFile) {
                $imgname = time() . '_' . rand(1, 100) . '.' . $this->imageFile->extension;
                $this->imageFile->saveAs($end_folder . '/' . $imgname);
                self::resizeImage($end_folder, $imgname);
                $this->updField($imgname);
            }

            if ($this->imageFiles) {
                $images = [];
                if ($this->img) {
                    $images = explode(';', $this->img);
                }
                foreach ($this->imageFiles as $key => $image) {
                    $imgname = time() . rand(1, 100) . '.' . $image->extension;
                    $image->saveAs($end_folder . '/' . $imgname);

                    $this->resizeImage($end_folder, $imgname);
                    $images[] = $imgname;
                }
                $images_str = implode(';', $images);
                $this->updField($images_str);
            }
        }


        //$this->optimizeImage(); //uncomment after installing jpegoptim and optipng
    }

    public static function resizeImage($end_folder, $imgname, $makeThumb = true)
    {

        $image = Image::getImagine()->open($end_folder . '/' . $imgname);
        if (Yii::$app->controller->id == 'slider') {
            $image->thumbnail(new Box(1500, 2000))->save($end_folder . '/' . $imgname);
            if ($makeThumb) {
                $mode = \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND;
                $image->thumbnail(new Box(1400, 450), $mode)->save($end_folder . '/' . $imgname);
                $image->thumbnail(new Box(190, 195), $mode)->save($end_folder . '/s_' . $imgname);
                //Image::thumbnail($end_folder.'/s_'.$imgname,190, 195)->save($end_folder.'/s_'.$imgname);
            }
        } else {
            $image->thumbnail(new Box(1500, 1500))->save($end_folder . '/' . $imgname, ['quality' => 100]);
            if ($makeThumb) {
                //$mode = \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND;
                $image->thumbnail(new Box(400, 400))->save($end_folder . '/s_' . $imgname, ['quality' => 100]);
                //$image->thumbnail(new Box(190, 195))->save($end_folder . '/s_' . $imgname);
                //Image::thumbnail($end_folder.'/s_'.$imgname,190, 195)->save($end_folder.'/s_'.$imgname);
            }
        }
    }

    protected function getIdentities()
    {
        $contr = Yii::$app->controller->id;
        switch ($contr) {
            case 'category':
                $field = 'icon';
                break;
            case 'post':
                $field = 'images';
                break;
            default:
                $field = 'img';
        }
        return ['contr' => $contr, 'field' => $field];
    }

    protected function updField($imgname)
    {
        $iden = $this->identities;
        Yii::$app->db->createCommand("UPDATE {$iden['contr']} SET {$iden['field']}='{$imgname}' WHERE id='{$this->id}'")->execute();
    }

    public function makeDir()
    {
        $cr = false;
        if (!empty($this->created_at)) {
            $cr = $this->created_at;
        }
        return self::makeDirStatic($this->id, $cr);
    }

    public static function makeDirStatic($id, $created_at = false)
    {
        $contr = Yii::$app->controller->id;

        $uploads_folder = Yii::getAlias("@webroot/images/" . $contr);
        if (!is_dir($uploads_folder)) {
            mkdir($uploads_folder);
        }
        if ($created_at && in_array($contr, ['something'])) {
            $month = date('m-Y', $created_at);
            $month_folder = $uploads_folder . '/' . $month;
            $end_folder = $uploads_folder . '/' . $month . '/' . $id;
            if (!is_dir($month_folder)) {
                mkdir($month_folder);
            }
        } else {
            $end_folder = $uploads_folder . '/' . $id;
        }
        if (!is_dir($end_folder)) {
            mkdir($end_folder);
        }
        return $end_folder;
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $iden = $this->identities;
        $frontroot = realpath(Yii::getAlias('@app')  . '/../web');
        if (is_dir($dir = $frontroot . "/images/{$iden['contr']}/" . $this->id)) {
            $scaned = scandir($dir);
            foreach ($scaned as $scan) {
                if ($scan != '.' && $scan != '..') {
                    @unlink($dir . '/' . $scan);
                }
            }
            @rmdir($dir);
        }
    }

    protected function optimizeImage()
    {
        $webroot = Yii::getAlias('@webroot');
        $model_name = Yii::$app->controller->id;
        $folder = $webroot . "/images/{$model_name}/" . $this->id;
        if (is_dir($folder)) {
            $scaned = scandir($folder);
            foreach ($scaned as $scan) {
                if ($scan != '.' && $scan != '..') {
                    $exp = explode('.', $scan);
                    if (isset($exp[1])) {
                        $ext = strtolower($exp[1]);
                        $file = $folder . '/' . $scan;
                        if ($ext == 'jpg' || $ext == 'jpeg') {
                            $command = 'jpegoptim ' . $file . ' --strip-all --all-progressive';
                            shell_exec($command);
                        } elseif ($ext == 'png') {
                            $command = 'optipng ' . $file;
                            shell_exec($command);
                        }
                    }
                }
            }
        }
    }

    public function getMainImg($folder, $thumb = false, $tag = true)
    {
        if ($this->img) {
            $imgs = explode(';', $this->img);
            if ($thumb) {
                $prefix = 's_';
            } else {
                $prefix = '';
            }
            $src = "@web/images/" . $folder . "/" . $this->id . "/" . $prefix . $imgs[0];
            if (!$tag) {
                return $src;
            }
            return Html::img($src);
        }
    }
}
