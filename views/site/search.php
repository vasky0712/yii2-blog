<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

?>

<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
             
<?=
Html::a('Main page', ['index', 'id' => $model->id], ['class' => 'btn btn-primary']) ;
echo('<br/><br/>');
if(!$find){
    echo('<h1>Nothing finded</h1>');
}

                    foreach ($articles as $article):?>
                    <?php if($article->status ==1){ ?>
                <article class="post">
                    <div class="post-thumb" style="text-align: center;">
                        <a href="<?= Url::toRoute(['site/view', 'id'=>$article->id ]); ?>"><img src="<?= $article->getImage(); ?>" style="max-height:400px
                        margin-top: 20px;
                        max-width: 720px;" alt=""></a>

                        <a href="<?= Url::toRoute(['site/view', 'id'=>$article->id ]); ?>" class="post-thumb-overlay text-center">
                            <div class="text-uppercase text-center">View Post</div>
                        </a>
                    </div>
                    <div class="post-content">
                        <header class="entry-header text-center text-uppercase">
                            <h6><a href="<?= Url::toRoute(['site/category', 'id'=>$article->category_id])?>"><?= $article->category->title; ?></a></h6>

                            <h1 class="entry-title"><a href="<?= Url::toRoute(['site/view', 'id'=>$article->id ]); ?>"><?= $article->title; ?></a></h1>


                        </header>
                        <div class="entry-content">
                            <p><?= $article->description; ?>
                            </p>

                            <div class="btn-continue-reading text-center text-uppercase">
                                <a href="<?= Url::toRoute(['site/view', 'id'=>$article->id ]); ?>" class="more-link">Continue Reading</a>
                            </div>
                        </div>
                        <div class="social-share">
                            <span class="social-share-title pull-left text-capitalize">By <?= $article->author->name; ?></span>
                            <ul class="text-center pull-right">
                                <li><a class="s-facebook" href="#"><i class="fa fa-eye"></i></a></li><?= (int) $article->viewed; ?>
                            </ul>
                        </div>
                    </div>
                </article>
                    <?php }?>
                <?php endforeach;
                
                ?>  
            </div>
        </div>
    </div>
</div>