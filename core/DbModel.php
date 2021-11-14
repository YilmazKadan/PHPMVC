<?php

namespace app\core;

abstract class DbModel extends Model
{

    /**
     * Abstract metot sınıfın tablo adını döndürür.
     *
     * @return string
     */
    abstract public static function tableName(): string;
    /**
     * Abstract metot sınıfın tablo sütunlarını döndürür.
     *
     * @return array
     */
    abstract public function attributes(): array;

    /**
     * Abstract metot sınıfın primary key sütununu döndürür.
     *
     * @return string
     */
    abstract public static function primaryKey (): string;
/**
 * Veritabanına kayıt metodu
 * 
 * Bu metod veritabanına kayıt işlemi yapar.
 * Miras alınan sınıf bu metodu çağırır.
 * Çağrıldığı sınıfın primaryKey() metodu ile primary key alınır.
 * Çağrıldığı sınıfın attributes() metodu ile kayıt için gerekli alanlar alınır.
 * Sql sorgusu oluşturulur.
 * Sql sorgusu çalıştırılır.
 * Geriye dönen değer döndürülür.
 *
 * @return bool
 */
    public function save()
    {

        $tableName = $this->tableName();
        $attributes = $this->attributes();

        $params = array_map(fn ($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName (" . implode(',', $attributes) . ") 
        
            VALUES (" . implode(',', $params) . ")
        ");

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        $statement->execute();

        return true;
    }
   /**
    * Parametreleri alıp veritabanından kayıt olup olmadığını kontrol eder.
    * Kayıt var ise çağrıldığı sınıftan bir nesne olarak kayıtı döndürür .
    * @param [array] [$where] sql sorgusuna yollancak parametreler ve değerlerini tutar .
    * @return object | null
    */
    public static function findOne(array $where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode(" AND ",array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchObject(static::class);
        
    }
    /**
     * Prepare metotu
     *
     * Bu metot veritabanından veri çekmek için kullanılan pdo sorgusunu hazırlar.
     * 
     */
    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}
