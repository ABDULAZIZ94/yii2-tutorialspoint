# start teh server

    php -S 0.0.0.0:80 -t web

# push all branches to origin
    
    git push --all origin

# reset current branch to xxxxxx commit

    git reset xxxxxxxxxx

# database migration
create migration

    ./yii migrate/create test_table

run migration

    ./yii migrate

# Data access object test
    
    <server:port>/site/test-db