<?php 

if(Yii::$app->user->isGuest || !Yii::$app->user->isGuest ):?>
                <div class="leave-comment"><!--leave comment-->
                    <h4>Leave a reply</h4>

<?php if(Yii::$app->session->getFlash('comment')): ?>
    <div class="alert alert-success" role="alert">
        <?= Yii::$app->session->getFlash('comment'); ?>
            </div>
<?php endif;?>            

                    <?php
                        $form = \yii\widgets\ActiveForm::begin([
                            'action'=>['site/comment', 'id'=>$article->id],
                            'options'=>['class'=>'form-horizontal contact-form', 'role'=>'form']]);
                    ?>

                    <form class="form-horizontal contact-form" role="form" method="post" action="#">
                        
                        <div class="form-group">
                            <div class="col-md-12">

                            <?= $form->field($commentForm, 'comment')->textarea(['class'=>'form-control', 'placeholder'=>'Write Message'])->label(false) ;?>
										
                            </div>
                        </div>
                        <button type=submit"" class="btn send-btn">Post Comment</button>

                        <?php 
                            \yii\widgets\ActiveForm::end();
                        ?>
                        
                    </form>
                </div><!--end leave comment-->
                
<?php endif;?>