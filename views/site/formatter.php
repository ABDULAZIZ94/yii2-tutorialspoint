<?php
   $formatter = \Yii::$app->formatter;
   // output: January 1, 2016
   echo $formatter->asDate('2016-01-01', 'long'),"<br>";
   // output: 51.50%
   echo $formatter->asPercent(0.515, 2),"<br>";
   // output: <a href = "mailto:test@test.com">test@test.com</a>
   echo $formatter->asEmail('test@test.com'),"<br>";
   // output: Yes
   echo $formatter->asBoolean(true),"<br>";
   // output: (Not set)
   echo $formatter->asDate(null),"<br>";
?>