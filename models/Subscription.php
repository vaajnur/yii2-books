<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Subscription extends ActiveRecord
{
    public static function tableName(): string { return '{{%subscription}}'; }
    public function rules(): array
    {
        return [[['author_id', 'email'], 'required'], [['author_id'], 'integer'],
            [['author_id'], 'exist', 'targetClass' => Author::class, 'targetAttribute' => 'id'],
            [['email'], 'email'], [['email'], 'string', 'max' => 255], [['email'], 'trim'],
            [['email'], 'unique', 'targetAttribute' => ['author_id', 'email'], 'message' => 'Этот email уже подписан на автора.']];
    }
    public function beforeSave($insert): bool { if ($insert) $this->created_at = time(); return parent::beforeSave($insert); }
    public function attributeLabels(): array { return ['email' => 'Email']; }
    public function getAuthor(): ActiveQuery { return $this->hasOne(Author::class, ['id' => 'author_id']); }
}
