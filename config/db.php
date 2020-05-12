<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'sqlite:@app/helloworld.db', //gii need @app system path
    // 'username' => 'root',
    // 'password' => '',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
