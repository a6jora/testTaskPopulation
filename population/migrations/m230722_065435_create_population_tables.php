<?php

use yii\db\Migration;

/**
 * Class m230722_065435_create_population_tables
 */
class m230722_065435_create_population_tables extends Migration
{
    public function safeUp()
    {
        // Таблица "prefectures"
        $this->createTable('{{%prefectures}}', [
            'prefecture_id' => $this->primaryKey(),
            'prefecture_name' => $this->string(100)->notNull(),
        ]);

        // Таблица "years"
        $this->createTable('{{%years}}', [
            'year_id' => $this->primaryKey(),
            'year' => $this->integer()->notNull(),
        ]);

        // Таблица "populations"
        $this->createTable('{{%populations}}', [
            'population_id' => $this->primaryKey(),
            'prefecture_id' => $this->integer(),
            'year_id' => $this->integer(),
            'population' => $this->integer(),
        ]);

        // Индексы
        $this->createIndex('idx_prefectures_prefecture_name', '{{%prefectures}}', 'prefecture_name');
        $this->createIndex('idx_years_year', '{{%years}}', 'year');
        $this->createIndex('idx_populations_prefecture_year', '{{%populations}}', ['prefecture_id', 'year_id']);

        // Внешние ключи
        $this->addForeignKey('fk_populations_prefecture', '{{%populations}}', 'prefecture_id', '{{%prefectures}}', 'prefecture_id');
        $this->addForeignKey('fk_populations_year', '{{%populations}}', 'year_id', '{{%years}}', 'year_id');
    }

    public function safeDown()
    {
        // Удаление внешних ключей
        $this->dropForeignKey('fk_populations_year', '{{%populations}}');
        $this->dropForeignKey('fk_populations_prefecture', '{{%populations}}');

        // Удаление индексов
        $this->dropIndex('idx_populations_prefecture_year', '{{%populations}}');
        $this->dropIndex('idx_years_year', '{{%years}}');
        $this->dropIndex('idx_prefectures_prefecture_name', '{{%prefectures}}');

        // Удаление таблиц
        $this->dropTable('{{%populations}}');
        $this->dropTable('{{%years}}');
        $this->dropTable('{{%prefectures}}');
    }
}
