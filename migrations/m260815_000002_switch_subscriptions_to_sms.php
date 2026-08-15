<?php

use yii\db\Migration;

class m260815_000002_switch_subscriptions_to_sms extends Migration
{
    public function safeUp(): void
    {
        $this->addColumn('{{%subscription}}', 'phone', $this->string(20)->null()->after('author_id'));
        $this->alterColumn('{{%subscription}}', 'email', $this->string(255)->null());
        $this->createIndex('uq-subscription-author-phone', '{{%subscription}}', ['author_id', 'phone'], true);
    }

    public function safeDown(): void
    {
        $this->dropIndex('uq-subscription-author-phone', '{{%subscription}}');
        $this->dropColumn('{{%subscription}}', 'phone');
        $this->delete('{{%subscription}}', ['email' => null]);
        $this->alterColumn('{{%subscription}}', 'email', $this->string(255)->notNull());
    }
}
