<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stage".
 *
 * @property int $id
 * @property int|null $date_stage
 * @property int|null $building_id
 * @property string|null $img
 * @property string|null $description
 *
 * @property Building $building
 */
class Stage extends MyModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stage';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            [['date_stage', 'building_id'], 'integer'],
            [['img', 'description'], 'string', 'max' => 500],
            [['building_id'], 'exist', 'skipOnError' => true, 'targetClass' => Building::className(), 'targetAttribute' => ['building_id' => 'id']],

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
            'id' => 'ID',
            'date_stage' => 'Дата',
            'building_id' => 'Сооружение',
            'img' => 'Фотографии',
            'description' => 'Описание',
        ]);
        return $labels;
    }


    /**
     * Gets query for [[Building]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBuilding()
    {
        return $this->hasOne(Building::className(), ['id' => 'building_id']);
    }

    public function beforeValidate()
    {
        if ($this->date_stage) {
            $this->date_stage = strtotime($this->date_stage);
        }
        return parent::beforeValidate();
    }
}
