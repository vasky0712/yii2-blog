<?php

namespace app\models;

use yii\base\Model;
use yii;

class CommentForm extends Model
{
    public $comment;

    public function rules()
    {
        return[
            [['comment'], 'required'],
            [['comment'], 'string', 'length'=>[3,255]]
        ];
    }

    public function saveComment($articleId){

        $comment = new Comment;

        $comment->text = $this->comment;

        $comment->userId = Yii::$app->user->id;

        $comment->articleId = $articleId;

        $comment->status = 0;

        $comment->date = date('Y-m-d');

        return $comment->save();

    }
}