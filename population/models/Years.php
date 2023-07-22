<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "years".
 *
 * @property int $year_id
 * @property int $year
 *
 * @property Populations[] $populations
 */
class Years extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'years';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year'], 'required'],
            [['year'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'year_id' => 'Year ID',
            'year' => 'Year',
        ];
    }

    /**
     * Gets query for [[Populations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPopulations()
    {
        return $this->hasMany(Populations::class, ['year_id' => 'year_id']);
    }
}
