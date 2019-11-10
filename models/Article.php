<?php

namespace app\models;

use Yii;
    use yii\helpers\ArrayHelper;
    use yii\data\Pagination;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $date
 * @property string $image
 * @property int $viewed
 * @property int $user_id
 * @property int $status
 * @property int $category_id
 *
 * @property ArticleTag[] $articleTags
 * @property Comment[] $comments
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'description', 'content'], 'string'],
            [['date'], 'date', 'format'=>'php:Y-m-d'],
            [['date'], 'default', 'value'=> date('Y-m-d')],
            [['title'], 'string', 'max'=>255],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'date' => 'Date',
            'image' => 'Image',
            'viewed' => 'Viewed',
            'user_id' => 'User ID',
            'status' => 'Status',
            'category_id' => 'Category ID',
        ];
    }

    public function getImage(){
        if($this->image){
            return '/uploads/' .  $this->image;
        }

        return '/uploads/' . 'blog.png';
    }

    public function saveImage($filename){
        $this->image = $filename;
        return $this->save(false);
    }
    public function deleteImage(){
        $imageUploadModel = new ImageUpload();
        $imageUploadModel-> deleteCurrentImage($this->image);  
    
    }

    public function beforeDelete(){
        $this->deleteImage();
        return parent::beforeDelete();
    }
    
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status']);
    }

    public function saveCategory($category_id)
    {
        $category = Category::findOne($category_id);
        if($category != null)
        {
            $this->link('category', $category);
            return true;            
        }
    }


    public function saveStatus($status){
        $statuss = Status::findOne($status);
        if($statuss != null)
        {
            $this->link('status', $statuss);
            return true;            
        }
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('article_tag', ['article_id' => 'id']);
    }

    public function getSelectedTags(){
        $selectedTags = $this->getTags()->select('id')->asArray()->all();
        return ArrayHelper::getColumn($selectedTags, 'id'); 
    }

    public function saveTags($tags)
    {
        if (is_array($tags))
        {
            $this->clearCurrentTags();
            foreach($tags as $tag_id)
            {
                $tag = Tag::findOne($tag_id);
                $this->link('tags', $tag);
            }
        }
    }
    public function clearCurrentTags()
    {
        ArticleTag::deleteAll(['article_id'=>$this->id]);
    }

    public function getDate(){
        return Yii::$app->formatter->asDate($this->date);
    }

    public static function getAll($pageSize = 5){
         // build a DB query to get all articles
         $query = Article::find();
         // get the total number of articles (but do not fetch the article data yet)
         $count = $query->count();
         // create a pagination object with the total count
         $pagination = new Pagination(['totalCount' => $count, 'pageSize'=>$pageSize]);
         // limit the query using the pagination and retrieve the articles
         $articles = $query->offset($pagination->offset)
             ->limit($pagination->limit)
             ->all();
         
         $data['articles'] = $articles;
         $data['pagination'] = $pagination;
         
         return $data;
    }

    public static function getPopular(){
        return Article::find()->orderBy('viewed desc')->limit(3)->all();
    }

    public static function getRecent()
    {
        return Article::find()->orderBy('date desc')->limit(2)->all();
    }

    public function saveArticle(){
        $this->user_id = Yii::$app->user->id;
        return $this->save();
    }

    

    public function getComments(){
        return  $this->hasMany(Comment::className(), ['articleId'=>'id' ]);
    }

    public function getArticleComments(){

        return $this->getComments()->where(['status'=>1])->all();
    }


    public function getAuthor(){

        return $this->hasOne(User::className(), ['id'=>'user_id']);

    }

    public function viewedCounter(){
        $this->viewed += 1;
        return $this->save(false);
    }
    
}
