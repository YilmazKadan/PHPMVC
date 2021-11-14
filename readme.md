# PHP OWN MVC FRAMEWORK

Kendimi geliştirmek ve modern PHP Framework'lerinin işleyişini kavram amacıyla inşa ettiğim profesyonele yakın PHP MVC Framework'üm.

## Kazanımlar 

- MVC Mimarisini derinlemesine kavramak .
- OOP Bilgisini pekiştirmek .
- Laravel , Codeigniter vb. framework'lerin çalışma yapısını kavramak .
- Kendi composer package'mi oluşturmak .
- Gitignore kullanımı .
- Env uzantılı ayar dosyalarının kullanımı .
- Docblock kullanımı .

# Projede bizleri neler bekliyor ?

Proje içerisinde modern bir framework'de olan çoğu şey bulunmakta . Örneğin :

- Routing(Rota) sistemi .
- Middleware (request-response arasını handle etmek)
- Model (Veritabanı , Validation vb. işlemler katmanı.)
- Controller (View ile Model arasındaki bağlantıyı sağlayan ara katman)
- View (Görüntülenecek front kısımlarını yükleyen sınıf (Dinamik meta desteği) )
- Migration (Mysql , Mssql gibi veritabanı araçlarını kullanmadan tablolar, sütunlar vb. işlemleri yapan araç)
- FormField Sınıfı ( Bu sınıf sayesinde html kodu yazmadan hızlıca input elemanları oluşturabilmekteyiz .)
- Response sınıfı 
- Request sınıfı
- Session sınıfı
- Database sınıfı
- Exception sınıfı 

# Kurulum 

- Git kullanarak bir klonu indirin .
- Veritabanınızı oluşturun .
- Bir adet `env` dosyası oluşturun ve içerisine dosyalar arasında bulunan `env.example` dosyasındaki gibi bilgileri girin .
- Komut satırından `composer install` komutunu çalıştırın .
- Ana dizinden `php migrations.php` komutu ile tüm migration dosyalarınızı çalıştırın .
- Komut satırından `cd public` komutu ile public klasörüne girin .
- `php -S localhost:8080` komutu ile yerel sunucunuzu başlatın .
- Ardından tarayıcınızdan `localhost:8080` adresine girerek işlemi tamamlayabilirsiniz .

