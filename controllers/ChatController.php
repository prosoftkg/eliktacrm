<?php

namespace app\controllers;

use Yii;
use app\models\Chat;
use app\models\ChatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use frontend\models\User;
use yii\filters\AccessControl;

/**
 * ChatController implements the CRUD actions for Chat model.
 */
class ChatController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Chat models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ChatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Chat model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Chat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Chat();
        if (!$user_id = Yii::$app->user->id) $model->scenario = 'guest';
        else {
            $model->sender_id = $user_id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Chat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateModal()
    {
        $post = Yii::$app->request->post();
        $user_id = Yii::$app->user->id;
        $chat_id = Chat::send($post, $user_id);

        if ($chat_id) {
            $view = Yii::$app->request->post('view');
            $view_id = Yii::$app->request->post('view_id');
            Yii::$app->session->setFlash('success', Yii::t('app', 'Message sent to author.'));
            return $this->redirect(['/' . $view . '/view', 'id' => $view_id]);
        }
        return false;
    }

    public function actionLoad()
    {
        $user_id = Yii::$app->user->id;
        $id = Yii::$app->request->post('chat_id');
        $offset = Yii::$app->request->post('offset');
        $chat = '';
        $curdate = '';
        $dao = Yii::$app->db;

        if ($id) {
            $rows = (new \yii\db\Query())->from('chatline')->where(['chat_id' => $id])
                ->filterWhere(['<', 'id', $offset])->orderBy(['id' => SORT_DESC])->limit(15)->all($dao);
            krsort($rows);

            $dao->createCommand("UPDATE chatline SET is_read='1' WHERE chat_id='{$id}' AND receiver_id='{$user_id}'")->execute();
            $read = '';
            foreach ($rows as $row) {
                //if($curname!=$nr[$row['sender_id']]){$cname="<div class='chat_contact'>".$nr[$row['sender_id']]."</div>"; $curname=$nr[$row['sender_id']];} else {$cname='';}
                $day = date('d/m/Y', $row['sent_at']);
                if ($curdate != $day) {
                    $date = Html::tag('div', $day, ['class' => 'text-center font12 color5 mb10 pt10 clear js_date']);
                    $curdate = $day;
                } else {
                    $date = '';
                }
                if ($row['sender_id'] == $user_id) {
                    $cl = 'my_text chat_text js_chat_text';
                    if ($row['is_read']) {
                        $read = Html::tag('div', 'Прочитано', ['class' => 'is_read']);
                    } else $read = '';
                } else {
                    $cl = 'chat_text js_chat_text';
                    $read = '';
                }
                $line_time = Html::tag('div', date('H:i', $row['sent_at']), ['class' => 'font12 gray5 abs chat_date']);
                $chat .= $date . Html::tag('div', $row['text'] . $line_time, ['class' => $cl, 'data-lineid' => $row['id']]);
            }
            //return "<div style='display:none'>".$chat."</div>";
            return $chat . $read;
        }
        return false;
    }

    public function actionPost()
    {
        $post = Yii::$app->request->post();
        $user_id = Yii::$app->user->id;
        Chat::send($post, $user_id);
        //$dao->createCommand("UPDATE chat SET `created_at`=NOW() WHERE id='{$chat_id}'")->execute();
        return true;
    }

    public function actionIsRead()
    {
        $chat_id = Yii::$app->request->post('chat_id');
        $user_id = Yii::$app->user->id;
        if ($chat_id && $user_id) {
            $dao = Yii::$app->db;
            $dao->createCommand("UPDATE chatline SET `is_read`=1 WHERE is_read=0 AND chat_id='{$chat_id}' AND receiver_id='{$user_id}'")->execute();
        }
    }

    public function actionCheck()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $user_id = Yii::$app->user->id;
        $rows = Yii::$app->db->createCommand("SELECT chat_id FROM chatline WHERE is_read='0' AND receiver_id='{$user_id}' GROUP BY chat_id")->queryAll();
        return $rows;
    }

    public function actionCountNew()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $user_id = Yii::$app->user->id;
        $rows = Yii::$app->db->createCommand("SELECT COUNT(*) FROM chatline WHERE is_read='0' AND receiver_id='{$user_id}' GROUP BY receiver_id")->queryScalar();
        return $rows;
    }

    public function actionArchive()
    {
        $id = Yii::$app->request->post('chat_id');
        Yii::$app->db->createCommand("UPDATE chat SET archive='1' WHERE id='{$id}'")->execute();
    }
    /**
     * Updates an existing Chat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Chat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Chat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Chat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Chat::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
