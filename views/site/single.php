<?php

use yii\helpers\Url;

?>
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <article class="post">
                    <div class="post-thumb">
                        <a href="<?= Url::toRoute(['site/single', 'id'=>$article->id ])?>"><img src="<?= $article->getImage(); ?>" alt=""></a>
                    </div>
                    <div class="post-content">
                        <header class="entry-header text-center text-uppercase">
                            <h6><a href="<?= Url::toRoute(['site/category', 'id'=>$article->category_id])?>"> <?= $article->category->title; ?></a></h6>

                            <h1 class="entry-title"><a href="<?= Url::toRoute(['site/single', 'id'=>$article->id ]); ?>"><?= $article->title; ?></a></h1>


                        </header>
                        <div class="entry-content">
                            <?= $article->content?>
                        </div>
                    

                    </div>
                </article>
                
                <!-- end bottom comment-->

                <?php if(!empty($comments)):?>

                    <?php foreach($comments as $comment):?>

                        
                        <div class="bottom-comment"><!--bottom comment-->
                            <h4>3 comments</h4>

                            <div class="comment-img">
                                <img class="img-circle" src="assets/images/comment-img.jpg" alt="">
                            </div>

                            <div class="comment-text">
                                <h5><?= $comment->user->name;?></h5>

                                <p class="comment-date">
                                    <?= $comment->getDate(); ?>
                                </p>


                                <p class="para"><?= $comment->text;?></p>
                            </div>
                        </div>

                    <?php endforeach;?>

                <?php endif; ?>        

                <!-- end bottom comment-->
<?= $this->render('/partials/comment', [
    'article'=>$article,
    'comments'=>$comments,
    'commentForm'=>$commentForm
])?>
            </div>
            <div class="col-md-4" data-sticky_column>
                <div class="primary-sidebar">
                    
                    <aside class="widget">
                        <h3 class="widget-title text-uppercase text-center">Popular Posts</h3>
                    <?php foreach($popular as $article):?>
                    
                        <?php if($article->status ==1){ ?>
                        <div class="popular-post">


                            <a href="<?= Url::toRoute(['site/single', 'id'=>$article->id ]); ?>" class="popular-img"><img src="<?= $article->getImage(); ?>" alt="">

                                <div class="p-overlay"></div>
                            </a>

                            <div class="p-content">
                                <a href="<?= Url::toRoute(['site/single', 'id'=>$article->id ]); ?>" class="text-uppercase"><?= $article->title; ?></a>
                                <span class="p-date"><?= $article->getDate(); ?></span>

                            </div>
                        </div>
                        <?php }?>
                    <?php endforeach;?>
                    </aside>
                    <aside class="widget pos-padding">
                        <h3 class="widget-title text-uppercase text-center">Recent Posts</h3>
                    <?php foreach($recent as $article): ?>
                    
                        <?php if($article->status ==1){ ?>
                        <div class="thumb-latest-posts">


                            <div class="media">
                                <div class="media-left">
                                    <a href="<?= Url::toRoute(['site/single', 'id'=>$article->id ]); ?>" class="popular-img"><img src="<?= $article->getImage(); ?>" alt="">
                                        <div class="p-overlay"></div>
                                    </a>
                                </div>
                                <div class="p-content">
                                    <a href="<?= Url::toRoute(['site/single', 'id'=>$article->id ]); ?>" class="text-uppercase"><?= $article->title; ?></a>
                                    <span class="p-date"><?= $article->getDate(); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php }?>
                    <?php endforeach;?>
                    
                    </aside>
                    <aside class="widget border pos-padding">
                        <a style="margin-left:30%;"href="<?= Url::toRoute(['site/category', 'id'=>$article->category_id])?>"><h3 class="widget-title text-uppercase text-center">Categories</h3></a>
                        <ul>
                            <?php foreach($categories as $category):?>
                    <?php if($article->status ==1 and $category->getArticlescount()!=0){ ?>
                            <li>
                                <a href="<?= Url::toRoute(['site/category', 'id'=>$category->id])?>"><?= $category->title; ?></a>
                                <span class="post-count pull-right"> (<?= $category->getArticlescount(); ?>)</span>
                            </li>
                    <?php }?>
                            <?php endforeach;?>
                        </ul>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end main content-->
<!--footer start-->