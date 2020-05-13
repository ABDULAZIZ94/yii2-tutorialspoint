<?php
   namespace app\models;
   use app\components\UppercaseBehavior;
   use Yii;
   /**
   * This is the model class for table "user".
   *
   * @property integer $id
   * @property string $name
   * @property string $email
   */
   class MyUser extends \yii\db\ActiveRecord {
      public function behaviors() {
         return [
            // anonymous behavior, behavior class name only
            UppercaseBehavior::className(),
         ];
      }
      /**
      * @inheritdoc
      */
      public static function tableName() {
         return 'user';
      }
      /**
      * @inheritdoc
      */
      public function rules() {
         return [
            [['name', 'email'], 'string', 'max' => 255]
         ];
      }
      /**
      * @inheritdoc
      */
      public function attributeLabels() {
         return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
         ];
      }
   }
?>