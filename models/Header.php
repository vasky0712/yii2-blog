<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\assets\PublicAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;


class Header extends \yii\db\ActiveRecord{


    public function header(){ ?>
        <nav class="navbar main-menu navbar-default">
            <div class="container">
                <div class="menu-content">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="/"><img src="/assets/images/logo.jpg" alt=""></a>
                    </div>


                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                       
                        <div class="i_con">
                            <ul class="nav navbar-nav text-uppercase">
                                <?php if(Yii::$app->user->isGuest):?>
                                    <li><a href="<?= Url::toRoute([Yii::getAlias('@web') . '/auth/login'])?>">Login</a></li>
                                    <li><a href="<?= Url::toRoute([Yii::getAlias('@web') . '/auth/signup'])?>">Register</a></li>
                                <?php else: ?>
                                    <?= Html::beginForm([Yii::getAlias('@web') . '/auth/logout'], 'post')
                                    . Html::submitButton(
                                        'Logout (' . Yii::$app->user->identity->name . ')',
                                        ['class' => 'btn btn-link logout', 'style'=>"padding-top:10px;"]
                                    )
                                    . Html::endForm() ?>
                                    
                                <li><a href="<?= Url::toRoute([Yii::getAlias('@web') . '/admin/article'])?>">Articles</a></li>
                                <li><a href="<?= Url::toRoute([Yii::getAlias('@web') . '/admin/tag/index'])?>">Tags</a></li>
                                   <?php if(Yii::$app->user->identity->isAdmin==1){?> 
                                <li><a href="<?= Url::toRoute([Yii::getAlias('@web') . '/admin/category/index'])?>">Category</a></li>
                                <li><a href="<?= Url::toRoute([Yii::getAlias('@web') . '/admin/comment/index'])?>">Comment</a></li>
                                      <?php }?>
                                <?php endif;?>
                                
                                
                            </ul>
                        </div>

                    </div>
                    <!-- /.navbar-collapse -->
                </div>
            </div>
            <!-- /.container-fluid -->
        </nav>

     
<?php }  } ?>