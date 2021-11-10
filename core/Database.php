<?php



namespace app\core;



class Database
{
    public \PDO $pdo;
    /**
     * Database constructor
     */
    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';

        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigration()
    {

        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];

        $files = scandir(Application::$ROOT_DIR . '/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once Application::$ROOT_DIR . '/migrations/' . $migration;

            $className = pathinfo($migration, PATHINFO_FILENAME);

            $instance = new $className();

            $this->log("$migration uygulanıyor ..");

            $instance->up();

            $this->log("$migration uygulandı.");
            $newMigrations[] = $migration;
        }
        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("Tüm migration'lar uygulanmış .");
        }
    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;");
    }

    public function getAppliedMigrations()
    {

        $statment =  $this->pdo->prepare("SELECT migration FROM migrations");
        $statment->execute();

        return $statment->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrations)
    {
        $migrationsStr =  implode(',', array_map(fn ($m) => "('$m')", $migrations));
        $statment = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES 
            $migrationsStr
        ");
        $statment->execute();
    }

    protected  function log($message)
    {
        echo '[' . date("Y-m-d H:i:s") . '] - ' . $message . PHP_EOL;
    }
}
