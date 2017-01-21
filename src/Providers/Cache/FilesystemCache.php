<?php
namespace Providers\Cache;

require_once __DIR__ . '/CacheInterface.php';

class FilesystemCache implements CacheInterface
{
    //Attuazione del Singleton
    static private $istance = null;

    static private $definableOptions = [
        'cacheDirFromRoot', //Cartella di cache
    ];

    //Ritorna l'istanza della classe
    public static function getIstance(): FilesystemCache
    {
        if (self::$istance === null) {
            self::$istance = new FilesystemCache();
        }

        return self::$istance;
    }

    //Setta le variabili globali nell'istanza della classe
    //in base all'array passato come argomento alla seguente funzione, il
    //quale deve contenere le chiavi presenti in self::$definableOptions
    public static function setGlobal(array $options)
    {
        $cacheIstance = self::getIstance();

        foreach ($options as $key => $option) {
            if (in_array($key, self::$definableOptions)) {
                $cacheIstance->$key = $option;
            }
        }
    }

    //Setta la cache sul filesystem.
    //Ritorna true se il file di cache è stato creato.
    //Ritorna false nel caso opposto.
    public function set(string $key, $data): bool
    {
        if ($this->cacheDirFromRoot && $fullPathFile = fopen($this->cacheDirFromRoot . '/' . $key, 'w')) {
            fwrite($fullPathFile, $data);
            fclose($fullPathFile);
            return true;
        }

        return false;
    }

    //Se esiste, prelevo la cache dal filesystem.
    //Ritorna true se la cache è stata prelevata.
    //Ritorna false nel caso opposto.
    public function get(string $key): bool
    {
        if ($this->cacheDirFromRoot) {
            $fullPathFile = $this->cacheDirFromRoot . '/' . $key;
            if (file_exists($fullPathFile)) {
                require $fullPathFile;
                return true;
            }
        }

        return false;
    }
}
