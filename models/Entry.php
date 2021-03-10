<?php

namespace app\models;

use Yii;
use app\models\Building;
use app\models\Apartment;

/**
 * This is the model class for table "entry".
 *
 * @property integer $id
 * @property integer $building_id
 * @property integer $number
 * @property integer $apartment_amount
 */
class Entry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entry';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'building_id' => Yii::t('app', 'Здание'),
            'number' => Yii::t('app', 'Номер подъезда'),
            'apartment_amount' => Yii::t('app', 'Кол-во квартир в этаже'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['building_id', 'number', 'apartment_amount'], 'required'],
            [['building_id', 'number', 'apartment_amount'], 'integer'],
            [['number', 'apartment_amount'], 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number'],
            //            [['number'], 'unique'],
            ['number', 'unique', 'targetAttribute' => ['building_id', 'number']],
        ];
    }

    public function getBuilding()
    {
        return $this->hasOne(Building::className(), ['id' => 'building_id']);
    }

    public function getApartments()
    {
        return $this->hasMany(Apartment::className(), ['entry_id' => 'id'])->orderBy('number');
    }

    public function afterSave($insert, $changedAttributes)
    {
        $entry = $this->number;
        $entryId = $this->id;
        $building = $this->building_id;
        $amount = $this->apartment_amount;
        $object = $this->building->object->id;

        $stores = Yii::$app->request->post('stores');

        $floor = 1;
        $count = Apartment::find()->where(['building_id' => $building])->count();
        if (!$count) {
            $count = 0;
        }
        $fix = $stores * $amount + $count;
        for ($i = $count + 1; $i <= $fix; $i++) {
            Yii::$app->db->createCommand()
                ->insert('apartment', [
                    'entry_num' => $entry,
                    'building_id' => $building,
                    'number' => $i,
                    'entry_id' => $entryId,
                    'object_id' => $object,
                    'floor' => $floor,
                ])->execute();

            if ($i % $amount == 0) {
                $floor++;
            }
        }
    }
}
