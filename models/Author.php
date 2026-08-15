<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Author extends ActiveRecord
{
    public static function tableName(): string { return '{{%author}}'; }
    public function behaviors(): array { return [TimestampBehavior::class]; }
    public function rules(): array { return [[['full_name'], 'required'], [['full_name'], 'string', 'max' => 255], [['full_name'], 'trim']]; }
    public function attributeLabels(): array { return ['full_name' => 'ФИО']; }
    public function getBooks(): ActiveQuery { return $this->hasMany(Book::class, ['id' => 'book_id'])->viaTable('{{%book_author}}', ['author_id' => 'id']); }
    public function getSubscriptions(): ActiveQuery { return $this->hasMany(Subscription::class, ['author_id' => 'id']); }
}
