<?php 
namespace app\models;

use yii\base\Model;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\User;

class SignupForm extends Model
{
    public $name;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['name'], 'string'],
            [['name', 'email', 'password'], 'required'],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass'=>'app\models\User', 'targetAttribute'=>'email'],
        ];
    }

    public function signup(){
        if($this->validate())
        {
            $user = new User();

            $user->attributes = $this->attributes;

            return $user->create();
        }
    }
}