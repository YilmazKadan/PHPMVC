<?php
namespace app\core\db;

use app\core\Application;



class Database
{
    public \PDO $pdo;
    /**
     * Database constructor
     * Veritabanı bağlantısı için kullanılır .
     * @param array $config bu array $config['db'] olarak gelir içerisinden veritabanı bilgileri alınır.
     */
    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';

        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->query("SET NAMES UTF8");
    }
/**
 * Migration sınıflarını uygulayıp, veritabanının güncellenmesini sağlar.
 * 
 * Bu metot sayesinde uygulanmamış olan tüm migration dosyaları tek tek uygulanır .
 *
 * @return void
 */
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
    /**
     * Migration tablosunu oluşturur .
     *
     * @return void
     */
    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;");
    }

    /**
     * Uygulanan migration'ları döndürür .
     *
     * @return array
     */
    public function getAppliedMigrations()
    {

        $statment =  $this->pdo->prepare("SELECT migration FROM migrations");
        $statment->execute();

        return $statment->fetchAll(\PDO::FETCH_COLUMN);
    }
    /**
     * Migration'ları veritabanına  kaydeder .
     *
     * Metodun amacı : migration'ları tekrar tekrar çalıştırmaktan kurtarır .
     * @param array $migrations
     * @return void
     */
    public function saveMigrations(array $migrations)
    {
        $migrationsStr =  implode(',', array_map(fn ($m) => "('$m')", $migrations));
        $statment = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES 
            $migrationsStr
        ");
        $statment->execute();
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }
    /**
     * Migrationlar veya farklı bir uygulama yapılırken console'ye log yazdırır .
     *
     * @param [string] $message
     * @return void
     */
    protected  function log($message)
    {
        echo '[' . date("Y-m-d H:i:s") . '] - ' . $message . PHP_EOL;
    }
}
