<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "assignment_user".
 *
 * @property integer $user_id
 * @property integer $assignment_id
 */
class AssignmentUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assignment_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'assignment_id'], 'required'],
            [['user_id', 'assignment_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'assignment_id' => Yii::t('app', 'Assignment ID'),
        ];
    }
}
