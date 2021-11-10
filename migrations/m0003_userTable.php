
<?php
class m0003_userTable
{
    public function up()
    {

        $db = \app\core\Application::$app->db;
        $sql = "CREATE  TABLE IF NOT EXISTS users(
            id INT AUTO_INCREMENT PRIMARY KEY ,
            email VARCHAR(255) NOT NULL,
            firstname VARCHAR(255) NOT NULL,
            lastname VARCHAR(255) NOT NULL,
            status TINYINT NOT NULL , 
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE = INNODB";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        echo "Down migration".PHP_EOL;
    }
}
