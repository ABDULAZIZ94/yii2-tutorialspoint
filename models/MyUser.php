<?php
   namespace app\models;
   use Yii;
   /**
   * This is the model class for table "user".
   *
   * @property integer $id
   * @property string $name
   * @property string $email
   */
   class MyUser extends \yii\db\ActiveRecord {
      const EVENT_NEW_USER = 'new-user';
      public function init() {
         // first parameter is the name of the event and second is the handler.
         $this->on(self::EVENT_NEW_USER, [$this, 'sendMailToAdmin']);
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
      public function sendMailToAdmin($event) {
         echo 'mail sent to admin using the event';
      }
   }
?>
