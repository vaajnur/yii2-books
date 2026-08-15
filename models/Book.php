<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Book extends ActiveRecord
{
    public array $authorIds = [];
    public ?UploadedFile $coverFile = null;
    public static function tableName(): string { return '{{%book}}'; }
    public function behaviors(): array { return [TimestampBehavior::class]; }
    public function rules(): array
    {
        return [
            [['title', 'publication_year', 'isbn', 'authorIds'], 'required'], [['description'], 'string'],
            [['publication_year'], 'integer', 'min' => 1000, 'max' => (int) date('Y') + 1],
            [['title'], 'string', 'max' => 255], [['isbn'], 'string', 'max' => 20], [['isbn'], 'unique'],
            [['title', 'isbn'], 'trim'],
            [['authorIds'], 'each', 'rule' => ['exist', 'targetClass' => Author::class, 'targetAttribute' => 'id']],
            [['coverFile'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'webp'], 'maxSize' => 5242880, 'skipOnEmpty' => true],
        ];
    }
    public function attributeLabels(): array { return ['title' => 'Название', 'publication_year' => 'Год выпуска', 'description' => 'Описание', 'isbn' => 'ISBN', 'coverFile' => 'Обложка', 'authorIds' => 'Авторы']; }
    public function getAuthors(): ActiveQuery { return $this->hasMany(Author::class, ['id' => 'author_id'])->viaTable('{{%book_author}}', ['book_id' => 'id']); }
    public function afterFind(): void { parent::afterFind(); $this->authorIds = array_map('intval', $this->getAuthors()->select('id')->column()); }
    public function uploadCover(): bool
    {
        if (!$this->coverFile) return true;
        $directory = Yii::getAlias('@webroot/uploads/covers');
        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) return false;
        $old = $this->cover; $this->cover = Yii::$app->security->generateRandomString(20) . '.' . $this->coverFile->extension;
        if (!$this->coverFile->saveAs($directory . '/' . $this->cover)) { $this->cover = $old; return false; }
        if ($old && is_file($directory . '/' . $old)) @unlink($directory . '/' . $old);
        return true;
    }
    public function syncAuthors(): void
    {
        $this->unlinkAll('authors', true);
        foreach (Author::findAll(['id' => $this->authorIds]) as $author) $this->link('authors', $author);
    }
}
