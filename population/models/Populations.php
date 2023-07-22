<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "populations".
 *
 * @property int $population_id
 * @property int|null $prefecture_id
 * @property int|null $year_id
 * @property int|null $population
 *
 * @property Prefectures $prefecture
 * @property Years $year
 */
class Populations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'populations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prefecture_id', 'year_id', 'population'], 'integer'],
            [['prefecture_id'], 'exist', 'skipOnError' => true, 'targetClass' => Prefectures::class, 'targetAttribute' => ['prefecture_id' => 'prefecture_id']],
            [['year_id'], 'exist', 'skipOnError' => true, 'targetClass' => Years::class, 'targetAttribute' => ['year_id' => 'year_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'population_id' => 'Population ID',
            'prefecture_id' => 'Prefecture ID',
            'year_id' => 'Year ID',
            'population' => 'Population',
        ];
    }

    /**
     * Gets query for [[Prefecture]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrefecture()
    {
        return $this->hasOne(Prefectures::class, ['prefecture_id' => 'prefecture_id']);
    }

    /**
     * Gets query for [[Year]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getYear()
    {
        return $this->hasOne(Years::class, ['year_id' => 'year_id']);
    }
}
