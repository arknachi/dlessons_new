<?php
namespace backend\modules\suadmin\controllers;

use common\models\DlAdmin;
use common\models\DlSuperAdmin;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `suadmin` module
 */
class DefaultController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [ 'index', 'changepassword', 'profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   // 'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Renders the changepassword view for the module
     * @return string
     */
    public function actionChangepassword()
    {
        $model = $this->findModel(Yii::$app->user->getId());

        $model->scenario = 'changepassword';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $pwd_details = Yii::$app->request->post();

            $model->password = Yii::$app->myclass->refencryption($pwd_details['DlSuperAdmin']['newpass']);
            $model->save();
           \Yii::$app->getSession()->setFlash('success', 'Changed the password successfully!!!');
            return $this->render('changepassword', [
                'model' => $model,
            ]);
        } else {
            return $this->render('changepassword', [
                'model' => $model,
            ]);
        }
    }

    public function actionProfile(){

        $model = $this->findModel(Yii::$app->user->getId());

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
           \Yii::$app->getSession()->setFlash('success', 'Profile updated successfully!!!');
            return $this->render('updateprofile', [
                'model' => $model,
            ]);
        } else {

            return $this->render('updateprofile', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the DlAdmin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DlAdmin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DlSuperAdmin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
