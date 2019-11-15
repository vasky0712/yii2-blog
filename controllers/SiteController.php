<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Article;
use app\models\User;
use app\models\CommentForm;
use yii\data\Pagination;
use app\models\Category;
use app\models\ArticleSearch;
use app\models\ImageUpload;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use app\models\Status;
use app\models\Tag;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $layout = 'main';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    public function actionSearchByAuthor($status, $find){
        $userId = User::getUserId($status);
        $byAarticles = Article::getArticles($userId);
        return $this->render('search',[
            'articles'=>$byAarticles,
            'find' =>$find
        ]);
    }

    

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $data = Article::getAll(5);

        $popular = Article::getPopular();

        $recent = Article::getRecent();

        $categories = Category::getAll();


        if(Yii::$app->request->isPost)
        {
            
            
            if($status = Yii::$app->request->post('author') and !$status2 = Yii::$app->request->post('date') ){
                if($userId = User::getUserId($status) ){
                    $byAarticles = Article::getArticles($userId);

                    return $this->redirect(['site/search-by-author', 'status' => $status, 'find'=>true]);
                    
                }
                else{
                    return $this->redirect(['site/search-by-author', 'status' => $status, 'find'=>false]);
                }
            }

            if($status = Yii::$app->request->post('date') and !$status2 = Yii::$app->request->post('author')){
                if($articles = Article::getDateByDate($status) ){
                    return $this->render('search',[
                        'articles'=>$articles,
                        'find' =>$find=true
                    ]);
                }else{
                    return $this->redirect(['site/search-by-author', 'status' => $status, 'find'=>false]);
                }
            }

            if($author = Yii::$app->request->post('author') and $date = Yii::$app->request->post('date')){
                if($userId = User::getUserId($author) ){
                    $byAarticles = Article::getArticlesByAuthorAndData($userId, $date);

                    return $this->redirect(['site/search-by-author', 'status' => $byAarticles, 'find'=>true]);
                    
                }
                else{
                    return $this->redirect(['site/search-by-author', 'status' => $status, 'find'=>false]);
                }
            }
            
           
        }

        return $this->render('index',[
            'articles'=>$data['articles'], 
            'pagination' => $data['pagination'],
            'popular'=>$popular,
            'recent'=>$recent,
            'categories'=>$categories,
            'byAarticles'=>$byAarticles
        ]);
    }

   

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSingle($id){

        $popular = Article::getPopular();

        $recent = Article::getRecent();

        $categories = Category::getAll();
        $article= Article::findOne($id);

        $comments  = $article->getArticleComments();

        $commentForm = new CommentForm();

        $article->viewedCounter();

        return $this->render('single',[
            'article'=>$article,
            'popular'=>$popular,
            'recent'=>$recent,
            'categories'=>$categories,
            'comments'=>$comments,
            'commentForm'=> $commentForm
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    

    public function actionCategory($id){
        $data = Category::getArticlesByCategory($id);
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();
        
        return $this->render('category',[
            'articles'=>$data['articles'],
            'pagination'=>$data['pagination'],
            'popular'=>$popular,
            'recent'=>$recent,
            'categories'=>$categories
        ]);
    }

    public function actionComment($id){
        $model = new CommentForm();

        if(Yii::$app->request->isPost){

            $model->load(Yii::$app->request->post());

            if($model->saveComment($id)){


                Yii::$app->getSession()->setFlash('comment', 'Your comment wil be added soon!');
                return $this->redirect(['site/single', 'id'=>$id]);

            }
        }
    }

    public function actionArticles(){

        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('articles', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

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

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    
    
    
}
