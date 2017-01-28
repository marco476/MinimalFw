# MinimalFw - PHP Minimal Framework per piccoli progetti

Il Framework nasce dall'esigenza di creare in maniera veloce, sicura e rapida un sistema automatizzato performante in cui sviluppatore software di piccole dimensioni *senza dipendenze da altre librerie*.

## Installazione

Il Framework è installabile facilmente via Composer :

```
composer require minimalfw/minimalfw
```

## Configurazione

Configurare il framework è semplicissimo :

```PHP
<?php
//Carico Autoloader di Composer
require_once __DIR__ . '/vendor/autoload.php';
use Kernel\Kernel;

$kernel = new Kernel();

$kernel->setGlobal([
        //Directory delle View
        'viewsDirFromRoot' => __DIR__ . '/src/Views'
        ]);
        
$kernel->setRoutes([
        'homepage' => [
            'route' => '/^\/$/',
            'controller' => 'IndexController',
            'action' => 'showHomeAction',
            'params' => []
        ]
]);

if ($kernel->findRoute()) {
    $kernel->executeAction();
} else {
    http_response_code(404);
}
```

Il Kernel è il cuore del sistema. Con il metodo **setGlobal** del *Kernel* avete la possibilità di definire la cartella delle *View*, partendo dalla root del progetto.I controller, invece, possono essere definiti in qualsiasi file, purchè rispettino le direttive PSR0 e PSR4, e facciano quindi parte dello stesso namespace *Controller*.
Di default, qualora non impostato, il path delle *View* è il seguente :

```PHP
viewsDirFromRoot = $_SERVER["DOCUMENT_ROOT"] . '/src/Views';
```

Il metodo **setRoutes** del *Kernel* accetta un array di rotte con cui intendiamo effettuare il match con la URI. Esso ha il seguente formato:

```PHP
$kernel->setRoutes([
        'NOME ROTTA' => [
            'route' => '/REGULAR EXPRESSION/',
            'controller' => 'CONTROLLER DA RICHIAMARE IN CASO DI MATCH',
            'action' => 'ACTION DEL CONTROLLER DA RICHIAMARE IN CASO DI MATCH',
            'params' => [] //Array di parametri passati al Controller
        ],
        'NOME ROTTA_2' => [
            'route' => '/REGULAR EXPRESSION/',
            'controller' => 'CONTROLLER DA RICHIAMARE IN CASO DI MATCH',
            'action' => 'ACTION DEL CONTROLLER DA RICHIAMARE IN CASO DI MATCH',
            'params' => [ 'mioNome' => 'Marco' ] //Array di parametri passati al Controller
        ],
]);
```

Il metodo **findRoute** del *Kernel* restituisce true se è stato effettuato il match della URI con una delle Regular Expression definite nel settaggio delle rotte. False nel caso opposto.
Qualora l'esito sia positivo, è possibile richiamare il metodo **executeAction** del *Kernel* che richiamerà il metodo del Controller associato alla rotta con cui è stato effettuato il match.

## Controller e View

Tutti i controller vanno inseriti nella directory indicata in **controllerDirFromRoot** (vedere *Configurazioni*).
Il nome del file contenente il Controller deve essere uguale al nome della classe ivi contenuta.

```PHP
<?php
namespace Controller;

//In src/Controller/IndexController.php 
class IndexController
{
    //$params indica l'array di parametri settati nella definizione della rotta
    public function showHomeAction(array $params): array
    {
        //Lista di pagine da includere secondo una gestione FIFO
        return [
            'common/html/open-page.html',
            'homepage/html/head.php',
            'homepage/html/body.php',
            'common/html/footer.php',
            'common/html/close-page.html',
        ];
    }
}
```

Ogni controller deve restituire un array con il nome dei file da includere secondo l'ordine di definizione. Nel nostro esempio, *common/html/open-page.html* sarà il primo e *common/html/close-page.html* l'ultimo. Il path definito per ogni file viene ricercato all'interno della directory impostata in **viewsDirFromRoot** (vedere *Configurazione*).

## Cache

MinimalFw prevede anche un efficiente gestore di cache sul filesystem, in grado di cachare **HTML e/o dati**.

```PHP
<?php
require_once __DIR__ . '/vendor/autoload.php';
use Kernel\Kernel;
use Providers\Cache\FilesystemCache;

FilesystemCache::setGlobal([
    //Directory della cache
    'cacheDirFromRoot' => __DIR__ . '/cache'
    ]);

//Abilito la cache html passando true al kernel!
$kernel = new Kernel(true);
        
$kernel->setRoutes([
        'homepage' => [
            'route' => '/^\/$/',
            'controller' => 'IndexController',
            'action' => 'showHomeAction',
            'params' => []
        ]
]);

if ($kernel->findRoute()) {
    $kernel->executeAction();
} else {
    http_response_code(404);
}
```

La classe **FilesystemCache** è un *Singleton*. Possiamo definire la directory della cache usando il metodo statico **setGlobal** della classe **FilesystemCache**. Se non definita, la directory di default sarà:

```
cacheDirFromRoot = $_SERVER["DOCUMENT_ROOT"] . '/cache';
```

**Attenzione : è necessario dare i permessi di lettura e scrittura alla cartella di cache!**.

Successivamente, possiamo decidere se cachare sul filesystem l'intero HTML generato, oppure solamenti i dati.
In particolare, se passiamo true come primo argomento della classe *Kernel*, verranno salvate:

* Tutte le rotte definite nel **setRoutes**.
* HTML generato dalle rotte in cui è stato effettuato un match.

Se intendiamo salvare dei dati, o qualsiasi altra cosa ci venga in mente, possiamo farci ritornare l'istanza di **FilesystemCache** ed usare le sue funzioni di **set** e di **get**.
Mentre la funziona di **get** è identica per HTML e dati, accettano solo il nome della chiave come argomento, la funziona di **set** varia leggermente. Ecco un esempio di salvataggio HTML:

```PHP
$cache = FilesystemCache::getIstance();

if ($cache->get('HTMLHomepage')) {
    exit(); //E' ritornato tutto l'HTML. Posso anche uscire!
} else {
    $htmlHomePage = ...
    ...
    ...
    $cache->set('HTMLHomepage', $htmlHomePage);
}
```

Per il salvataggio HTML il **set** è semplicissimo: ci basta settare il nome della chiave come primo argomento, e passare l'interno HTML come secondo argomento.
Leggermente differente è il salvataggio dei dati. Ecco un esempio:

```PHP
$cache = FilesystemCache::getIstance();

if ($cache->get('datiHomePage')) {
    $datiHomepage = DATI_HOME_PAGE;
} else {
    $datiHomepage = ...
    ...
    ...
    $cache->set('datiHomePage', $datiHomepage, 'data', 'DATI_HOME_PAGE');
}
```

Per il salvataggio dei dati, il **set** prevede il nome della chiave come primo argomento, i dati da salvare come secondo argomento, la stringa *data* come terzo argomento, ed il nome della costante che verrà creata quando verrà effettuato il **get** della medesima chiave.

## SERVER WEB
Per permettere a tutte le rotte di "passare" nel vostro index.php (o qualsiasi altro file definito come Front Controller) avete bisogno di cambiare le configurazioni del vostro Web Server.
Con Apache, potete farlo in modo molto semplice:

```
<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteRule ^([a-zA-Z0-9])+$ /index.php [L]
</IfModule>
```
