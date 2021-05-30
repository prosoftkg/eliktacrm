<?php

namespace app\assets\core;

use yii\web\AssetBundle;

/**
 * Class Asset
 * @package app\assets\core
 */
class Asset extends AssetBundle
{
    /** @inheritdoc */
    public $js = [
        'js/script.js'
    ];
    /** @inheritdoc */
    public $sourcePath = "@app/assets/core/dist";
    /** @inheritdoc */
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'kartik\date\DatePickerAsset'
    ];
}
