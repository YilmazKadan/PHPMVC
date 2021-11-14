<?php
class m0005_contactTable
{
    public function up()
    {

        $db = \app\core\Application::$app->db;
        
        $sql = "CREATE  TABLE IF NOT EXISTS iletisim(
            id INT AUTO_INCREMENT PRIMARY KEY ,
            subject  VARCHAR(255) NOT NULL,
            body VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE = INNODB";
        $db->pdo->exec($sql);
    }
    public function down()
    {
    }
}
