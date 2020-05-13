<?php
   use yii\grid\GridView;
   echo GridView::widget([
      'dataProvider' => $dataProvider,
      'columns' => [
         'id',
         [
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'label' => 'Name and email',
            'value' => function ($data) {
               return $data->name . " writes from " . $data->email;
            },
         ],
      ],
   ]);
?>