<?php

namespace app\modules\admin\controllers;

use app\models\Comment;
use yii\web\Controller;
use app\models\User;
use yii;

class CommentController extends Controller
{
    public function actionIndex()
    {
        $modelUser = User::findOne(Yii::$app->user->id);
            if($model2->user_id==Yii::$app->user->id ||  $modelUser->isAdmin==1){
           
        
                $comments = Comment::find()->orderBy('id desc')->all();
                
                return $this->render('index',['comments'=>$comments]);  
            }
               
    }

    public function actionDelete($id)
    {
        $comment = Comment::findOne($id);
        if($comment->delete())
        {
            return $this->redirect(['comment/index']);
        }
    }

    public function actionAllow($id)
    {
        $comment = Comment::findOne($id);
        if($comment->allow())
        {
            return $this->redirect(['index']);
        }
    }
    
    public function actionDisallow($id)
    {
        $comment = Comment::findOne($id);
        if($comment->disallow())
        {
            return $this->redirect(['index']);
        }
    }
}