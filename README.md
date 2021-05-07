# helper

use:

    require_once './vendor/autoload.php';
    
    use TpgHelper\MongoDB;
    use TpgHelper\TpgHelper;

    $mongoDB = new MongoDB;

    $mongoDB->setDb('test')
            ->setTable('user')
            ->setLimit(20);

    $collection = $mongoDB->getCollectionTableData( );

    or

    $collection = $mongoDB->getCollectionTableData(['name' => 'Tiger']);

    TpgHelper::e($collection);
