<?php



namespace app\core;

/**
 * Oturum sınıfı 
 * 
 * Oturum sınıfı, oturum işlemlerini yönetmek için kullanılır.
 * @package app\core
 */
class Session
{

    protected const FLASH_KEY = 'flash_messages';
    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        // '&' işareti sayesinde direkt diziye etki ediyoruz .
        foreach ($flashMessages as $key => &$flashMessage) {
            // Remove özelliklerini true yapıyoruz .
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }


    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        // '&' işareti sayesinde direkt diziye etki ediyoruz .
        foreach ($flashMessages as $key => &$flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
    /**
     * Undocumented function
     *
     * @param [type] $key
     * @param [type] $value
     * @return void
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    /**
     * Session değerini döndürür
     *
     * @param [string] $key istenen session değerinin adı
     * @return string | false
     */
    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }
    /**
     * Session silme işlemi
     *
     * @param [string] $key silinecek session anahtarı
     * @return void
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }
}
