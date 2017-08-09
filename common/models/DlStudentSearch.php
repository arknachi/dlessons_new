<?php

namespace common\models;

use common\components\Myclass;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

/**
 * DlInstructorsSearch represents the model behind the search form about `common\models\DlInstructors`.
 */
class DlStudentSearch extends DlStudent
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'middle_name', 'last_name', 'searchstatus', 'student_dob', 'searchlesson'], 'safe'],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
                'student_dob',
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search_old($params)
    {

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

    public function search($params)
    {

        $datamod = array();
        $search_vals = [];
        $datamod = $_GET;

        $adminid = Yii::$app->user->identity->ParentAdminId;

        $this->load($params);

        if ($this->first_name)
            $search_vals[] = " first_name LIKE '%" . $this->first_name . "%' ";

        if ($this->last_name)
            $search_vals[] = " last_name LIKE '%" . $this->last_name . "%' ";

        if ($this->student_dob) {
            $student_dob = Myclass::dateformat($this->student_dob);
            $search_vals[] = " sp.dob = '" . $student_dob . "' ";
            $datamod['DlStudentSearch']['student_dob'] = $student_dob;
        }

        if ($this->searchlesson) {
            $search_vals[] = " sc.lesson_id = " . $this->searchlesson;
        }

        if ($this->searchstatus == 3) {
            // Registered and Paid
            $search_vals[] = " sc.scr_paid_status = 1 ";
        } else if ($this->searchstatus == 4) {
            // Registered and Not Paid
            $search_vals[] = " sc.scr_paid_status = 0 ";
        }

        $query = "SELECT sc.scr_paid_status,sc.scr_id,sc.lesson_id,sp.dob,a.*,";

        if ($this->searchlesson) {
            $query .= "(SELECT 
                    d.hours - SUM(c.hours) 
                    FROM dl_student_course b
                    JOIN db_schedules c
                    ON c.scr_id = b.scr_id
                    JOIN dl_lessons d
                    ON d.lesson_id = c.lesson_id
                    WHERE b.student_id = a.student_id and d.lesson_id = " . $this->searchlesson . "
                    ) AS t_hur";
        } else {
            $query .= "(SELECT 
                    d.hours - SUM(c.hours) 
                    FROM dl_student_course b
                    JOIN db_schedules c
                    ON c.scr_id = b.scr_id
                    JOIN dl_lessons d
                    ON d.lesson_id = c.lesson_id
                    WHERE b.student_id = a.student_id
                    ) AS t_hur";
        }

        $query .= " FROM dl_student a 
                    JOIN dl_student_course sc ON sc.student_id =  a.student_id 
                    JOIN dl_student_profile sp ON sp.student_id = a.student_id ";


        $search_query = " sc.admin_id = " . $adminid;

        if (!empty($search_vals))
            $search_query = implode(" AND ", $search_vals);

        $query .= ($search_query != "") ? " Where " : "";

        $query .= $search_query;

        $query .= " GROUP BY sc.student_id ";

        if ($this->searchstatus == 1) {
            // Not Assigned
            $query .= "HAVING t_hur IS NULL";
        } else if ($this->searchstatus == 2) {
            // Assigned (Remaining hours need to add)
            $query .= "HAVING t_hur IS NOT NULL and t_hur!=0";
        } else if ($this->searchstatus == 5) {
            // Assigned (No Remaining hours)
            $query .= "HAVING t_hur IS NOT NULL and t_hur=0";
        }

        $query .= " ORDER BY a.student_id DESC";
//echo $query; exit;
        $totalCount = Yii::$app->db->createCommand("SELECT COUNT(*) FROM (" . $query . ") as tc")->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => $query,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => 10,
                'params' => $datamod
            ],
        ]);
        //  $dataProvider->getModels();
        return $dataProvider;
    }
}
