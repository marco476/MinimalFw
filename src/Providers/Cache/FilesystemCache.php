<?php
namespace Providers\Cache;

class FilesystemCache implements CacheInterface
{
    //Attuazione del Singleton
    static private $istance = null;

    //Elenco di chiavi settabili globalmente con setGlobal
    static private $definableOptions = [
        'cacheDirFromRoot', //Cartella di cache
    ];

    //Contiene il path della cache
    private $cacheDirFromRoot;

    //Ritorna l'istanza della classe
    public static function getIstance(): FilesystemCache
    {
        if (self::$istance === null) {
            self::$istance = new FilesystemCache();
            self::$istance->init();
        }

        return self::$istance;
    }

    //Metodo di init richiamato alla creazione dell'istanza
    private function init()
    {
        $this->cacheDirFromRoot = $_SERVER["DOCUMENT_ROOT"] . '/cache';
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
    public function set(string $key, $data, $format = null, $nameDefine = null): bool
    {
        if ($this->cacheDirFromRoot && $fullPathFile = fopen($this->cacheDirFromRoot . '/' . md5($key), 'w')) {
            if ($format == 'data' && $nameDefine) {
                $data = "<?php\ndefine('" . $nameDefine ."'," . var_export($data, true) . ");\n?>";
            }
            fwrite($fullPathFile, $data);
            fclose($fullPathFile);
            return true;
        }

        return false;
    }

    //Se esiste, prelevo la cache dal filesystem.
    public function get(string $key): bool
    {
        if ($this->cacheDirFromRoot) {
            $fullPathFile = $this->cacheDirFromRoot . '/' . md5($key);
            if (file_exists($fullPathFile)) {
                require $fullPathFile;
                return true;
            }
        }

        return false;
    }

    //Restituisce la directory della cache
    public function getCacheDir()
    {
        return !empty($this->cacheDirFromRoot) ? $this->cacheDirFromRoot : false;
    }

    //Restituisce un array contenente tutte le possibili opzioni
    //settabili globalmente mediante setGlobal
    public function getDefinableOptions(): array
    {
        return $this->definableOptions;
    }
}
