<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prefectures".
 *
 * @property int $prefecture_id
 * @property string $prefecture_name
 *
 * @property Populations[] $populations
 */
class Prefectures extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prefectures';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prefecture_name'], 'required'],
            [['prefecture_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prefecture_id' => 'Prefecture ID',
            'prefecture_name' => 'Prefecture Name',
        ];
    }

    /**
     * Gets query for [[Populations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPopulations()
    {
        return $this->hasMany(Populations::class, ['prefecture_id' => 'prefecture_id']);
    }
}
