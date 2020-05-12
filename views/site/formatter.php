<?php
   $formatter = \Yii::$app->formatter;
   echo $formatter->asDate(date('Y-m-d'), 'long'),"<br>";
   echo $formatter->asTime(date("Y-m-d")),"<br>";
   echo $formatter->asDatetime(date("Y-m-d")),"<br>";

   echo $formatter->asTimestamp(date("Y-m-d")),"<br>";
   echo $formatter->asRelativeTime(date("Y-m-d")),"<br>";
?>