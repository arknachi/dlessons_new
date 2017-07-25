<?php

namespace common\models;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\base\Model;
use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;

class UploadForm extends Model {

    /**
     * @var UploadedFile
     */
   // public $imageFile;

    public function rules() {
        return [
         //   [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload() {
        
       // if ($this->validate()) {

            $path = \Yii::$app->params['basepath'] . '/uploads';
            if (!is_dir($path)) {
                $image_path = BaseFileHelper::createDirectory($path, 0777, true);
            }
            print_r($this->imageFile);
            echo $image_path; exit;
            $this->imageFile->saveAs($image_path . $this->imageFile->baseName . '.' . $this->imageFile->extension);
          //  return true;
            
//        } else {
//            return false;
//        }
    }

}
