<?php
   $formatter = \Yii::$app->formatter;
   echo Yii::$app->formatter->asInteger(105),"<br>";
   echo Yii::$app->formatter->asDecimal(105.41),"<br>";
   echo Yii::$app->formatter->asPercent(0.51),"<br>";
   echo Yii::$app->formatter->asScientific(105),"<br>";
   echo Yii::$app->formatter->asCurrency(105),"<br>";
   echo Yii::$app->formatter->asSize(105),"<br>";
   echo Yii::$app->formatter->asShortSize(105),"<br>";
?>