<?php

namespace common\models;

use common\components\Myclass;
use common\models\DlStudent;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DlInstructorsSearch represents the model behind the search form about `common\models\DlInstructors`.
 */
class DlStudentSearch extends DlStudent {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['first_name', 'middle_name', 'last_name', 'searchstatus', 'student_dob','searchlesson'], 'safe'],
        ];
    }

    public function attributes() {
        return array_merge(parent::attributes(), [
            'student_dob',
                ]
        );
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        
        $datamod = array();
        $datamod = $_GET;
        
        $adminid = Yii::$app->user->identity->ParentAdminId;

        //$admin_model = DlAdmin::find()->where(['admin_id' => $adminid])->with("studentCourses")->one();

        $students_list = DlStudentCourse::find()->joinWith(['student', 'student.studentProfile'])->where(['admin_id' => $adminid]);

        //$students_list = $admin_model->getMystudents();

        $this->load($params);

        $students_list->andFilterWhere(['like', 'dl_student.first_name', $this->first_name])
                ->andFilterWhere(['like', 'dl_student.last_name', $this->last_name]);

        if ($this->student_dob) {
            $student_dob = Myclass::dateformat($this->student_dob);
            $students_list->andWhere(['dl_student_profile.dob' => $student_dob]);
            $datamod['DlStudentSearch']['student_dob'] = $student_dob;
        }
        
        if ($this->searchlesson) {
             $students_list->andWhere(['lesson_id' => $this->searchlesson]);
        }
        
        if ($this->searchstatus == 1) {
            // Not Assigned
            $students_list->andWhere('schedule_id=0');
        } else if ($this->searchstatus == 2) {
            // Assigned
            $students_list->andWhere('schedule_id > 0');
        } else if ($this->searchstatus == 3) {
            // Registered and Paid
            $students_list->andWhere('scr_paid_status=1');
        } else if ($this->searchstatus == 4) {
            // Registered and Not Paid
            $students_list->andWhere('scr_paid_status=0');
        }

        $students_list->groupBy("student_id");

        $dataProvider = new ActiveDataProvider([
            'query' => $students_list,
            'sort' => ['defaultOrder' => ['student_id' => SORT_DESC]],
            'pagination' => array(               
                'params' => $datamod
            )
        ]);

        return $dataProvider;
    }

}
