<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment}}`.
 */
class m191029_164049_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'text'=>$this->string(),
            'userId'=>$this->integer(),
            'articleId'=>$this->integer(),
            'status'=>$this->integer(),
        ]);
        // creates index for column `user_id`
        $this->createIndex(
            'idx-post-userId',
            'comment',
            'userId'
        );
        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-postUserId',
            'comment',
            'userId',
            'user',
            'id',
            'CASCADE'
        );
        // creates index for column `article_id`
        $this->createIndex(
            'idx-articleId',
            'comment',
            'articleId'
        );
        // add foreign key for table `article`
        $this->addForeignKey(
            'fk-articleId',
            'comment',
            'articleId',
            'article',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%comment}}');
    }
}
