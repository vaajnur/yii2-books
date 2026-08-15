<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Subscription extends ActiveRecord
{
    public static function tableName(): string { return '{{%subscription}}'; }
    public function rules(): array
    {
        return [[['author_id', 'phone'], 'required'], [['author_id'], 'integer'],
            [['author_id'], 'exist', 'targetClass' => Author::class, 'targetAttribute' => 'id'],
            [['phone'], 'string', 'max' => 20],
            [['phone'], 'match', 'pattern' => '/^[1-9]\d{7,14}$/', 'message' => 'Введите номер в международном формате, например 79991234567.'],
            [['phone'], 'unique', 'targetAttribute' => ['author_id', 'phone'], 'message' => 'Этот номер уже подписан на автора.']];
    }
    public function beforeValidate(): bool
    {
        if (is_string($this->phone)) {
            $this->phone = preg_replace('/\D+/', '', $this->phone);
            if (strlen($this->phone) === 11 && str_starts_with($this->phone, '8')) $this->phone = '7' . substr($this->phone, 1);
        }
        return parent::beforeValidate();
    }
    public function beforeSave($insert): bool { if ($insert) $this->created_at = time(); return parent::beforeSave($insert); }
    public function attributeLabels(): array { return ['phone' => 'Номер телефона']; }
    public function getAuthor(): ActiveQuery { return $this->hasOne(Author::class, ['id' => 'author_id']); }
}
