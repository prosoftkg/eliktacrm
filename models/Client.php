<?php

namespace app\models;

use app\controllers\ApartmentController;
use app\models\Company;
use app\models\Deal;
use app\models\Apartment;
use Yii;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property string $fullname
 * @property string $phone
 * @property string $phone2
 */
class Client extends \yii\db\ActiveRecord
{
    public $client_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname', 'phone', 'phone2'], 'required'],
            [['birthday', 'passport_num', 'client_name', 'email', 'address', 'apartment_id', 'company_id'], 'safe'],
            [['fullname', 'phone', 'phone2'], 'string', 'max' => 255],
            [['company_id'], 'integer'],
        ];
    }

    public function getPayment()
    {
        return $this->hasMany(Payment::className(), ['client_id' => 'id']);
    }

    public function getDeal()
    {
        return $this->hasMany(Deal::className(), ['client_id' => 'id']);
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    public function getApartment()
    {
        return $this->hasMany(Apartment::className(), ['client' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fullname' => Yii::t('app', 'ФИО клиента'),
            'phone' => Yii::t('app', 'Телефон'),
            'phone2' => Yii::t('app', 'Дополнительный телефон'),
            'email' => Yii::t('app', 'E-mail'),
            'address' => Yii::t('app', 'Адрес'),
            //'primary_sum' => Yii::t('app', 'Первоначальный взнос'),
            //'left_sum' => Yii::t('app', 'Остаток'),
            //'discount' => Yii::t('app', 'Скидка'),
            'reference' => Yii::t('app', 'Канал продаж'),
            'passport_num' => Yii::t('app', 'Номер пасспорта'),
            'birthday' => Yii::t('app', 'Дата рождения'),
            'prepay' => Yii::t('app', 'Предоплата'),
            'apartment_id'=>'Сделка'
        ];
    }
}
