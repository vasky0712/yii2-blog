<?php
namespace app\modules\admin\controllers;
use app\models\Category;
use app\models\Status;
use app\models\ImageUpload;
use app\models\Tag;
use Yii;
use app\models\Article;
use app\models\ArticleSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\User;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    
    public function actionSetImage($id)
    {
        $modelUser = User::findOne(Yii::$app->user->id);
            if(  $modelUser->isAdmin==1){
           
        
                $model = new ImageUpload;
                if (Yii::$app->request->isPost)
                {
                
                                                                    
                            $article = $this->findModel($id);
                            $file = UploadedFile::getInstance($model, 'image');
                            if($article->saveImage($model->uploadFile($file, $article->image)))
                            {
                                return $this->redirect(['view', 'id'=>$article->id]);
                            }                       

                        
                    

                }
                
                return $this->render('image', ['model'=>$model]);   
            
        }    
        throw new NotFoundHttpException('The requested page does not exist.');                                                  
        
    }
     
    public function actionSetCategory($id){
        
        $article = $this->findModel($id);
        $nameee  = $article->category->title;
        $selectedCategory = $article->category->id;
        $categories = ArrayHelper::map(Category::find()->all(), 'id', 'title');
        if(Yii::$app->request->isPost)
        {
            $category = Yii::$app->request->post('category');
            if($article->saveCategory($category))
            {
                return $this->redirect(['view', 'id'=>$article->id]);
            }
        }
        return $this->render('category', [
            'article'=>$article,
            'selectedCategory'=>$selectedCategory,
            'categories'=>$categories,
            'nameee'->$nameee
        ]);

    }

    public function actionSetStatus($id){
        $article = $this->findModel($id);
        $selectCategory = $article->status->id;
        $categories = ArrayHelper::map(Status::find()->all(), 'id', 'title');

        if(Yii::$app->request->isPost)
        {
            
            
            $status = Yii::$app->request->post('status');

            if($article->saveStatus($status))
            {
                if($article->status == 3){
                    $this->findModel($id)->delete();
    
                    return $this->redirect([Yii::getAlias('@web') . '/site/']);
                };
                return $this->redirect(['view', 'id'=>$article->id]);
            }
        }

        return $this->render('status', [
            'article'=>$article,
            'selectCategory'=>$selectCategory,
            'categories' =>$categories
        ]);
    }


    public function actionSetTags($id)
    {
        $article = $this->findModel($id);
        $selectedTags = $article->getSelectedTags(); //
        $tags = ArrayHelper::map(Tag::find()->all(), 'id', 'title');
        if(Yii::$app->request->isPost)
        {
            $tags = Yii::$app->request->post('tags');
            $article->saveTags($tags);
            return $this->redirect(['view', 'id'=>$article->id]);
        }
        
        return $this->render('tags', [
            'selectedTags'=>$selectedTags,
            'tags'=>$tags
        ]);
    }


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();

        if ($model->load(Yii::$app->request->post()) && $model->saveArticle()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->saveArticle()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $modelUser = User::findOne(Yii::$app->user->id);
        if (($model = Article::findOne($id)) !== null) {
            if($model->user_id==Yii::$app->user->id ||  $modelUser->isAdmin==1)
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    
}
