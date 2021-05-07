<?php

    declare(strict_types = 1);

    namespace TpgHelper;

    use Exception;
    use MongoDB\Client;

    class MongoDB
    {
        public Client $client;
        private $db;
        private $table;
        public  $collections = [];
        private $searchArray = [];
        private int $limit   = 0;

        public function __construct()
        {
            if ( !extension_loaded ('mongodb') ) {

                throw new Exception('The extension "extension = php_mongodb.dll" must be loaded into php.ini. Do not forget to reload the server afterwards!');

                exit;
            }

            if( ! isset ( $this->client ) ) {

                $this->client = new Client;
            }
        }

        public function setDb(string $db) : MongoDB 
        {
            $this->db = $db;

            return  $this;
        }

        public function setTable(string $table) : MongoDB
        {
            $this->table = $table;

            return  $this;
        }

        public function addIndex( array $index) : MongoDB 
        {

            return $this;
        }

        public function setLimit(int $limit) : MongoDB 
        {
            $this->limit = $limit;

            return  $this;
        }

        public function getClient() : Client
        {
            return $this->client;
        }

        public function getCollectionsName( $db = null ) : array
        {
            if( is_null( $db ) AND empty( $this->db ) ) { return []; }
            elseif( !is_null($db) ){ $this->db = $db; }

            $db = $this->db;

            $colecciones = $this->client->$db->listCollections();

            foreach ($colecciones as $col) {

                $this->collections[] = $col->getName();
            }

            return $this->collections;
        }

        public function getCollections( $db = null, $data = 0 ) : array
        {
            $limit  = [];

            if( $this->limit > 0 ){ $limit  = [ 'limit' => $this->limit ]; }

            if( is_null( $db ) AND empty( $this->db ) ) { return []; }
            elseif( !is_null($db) ){ $this->db = $db; }

            $db = $this->db;

            $colecciones = $this->client->$db->listCollections();

            foreach ($colecciones as $col) {

                $getName = $col->getName();

                if( $data === 0 ){

                    $this->collections[ $getName ] = $col;

                }else{

                    $this->collections[ $getName ]['CollectionInfo'] = $col;
                    // TODO $this->collections[ $getName ]['CollectionData'] = $this->getCollectionTableData( [], $limit );
                } 
            }

            return $this->collections;
        }

        public function getCollectionTableData( $searchArray = [], $db = null, $withCount = true ) : array
        {
            if( is_null( $db ) AND empty( $this->db ) ) { return []; }

            $db    = $this->db;
            $table = $this->table;
            $data  = $limit = []; 

            if( !empty( $searchArray ) ) { $this->searchArray = $searchArray; }

            if( $this->limit > 0 ) { $limit = [ 'limit' => $this->limit ]; }

            $cursor = $this->client->$db->$table->find( $this->searchArray, $limit );

            $counter = 0;

            foreach ($cursor as $row) {

                foreach( $row AS $key => $das ) {

                    $data[ $counter ][ $key ] = $das;
                }

                $counter++;
             };

             if( $withCount == true ){ array_unshift( $data, count($data) ); }
             
            return $data;
        }
    }