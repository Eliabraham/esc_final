<?php
    include_once(__DIR__."/../info.php");
    class Cnx extends PDO {
        public function __construct() {
            try {
                parent::__construct(
                    CONFIG['DB_CONNECTION'].":host=".CONFIG['DB_HOST'].";dbname=".CONFIG['DB_DATABASE'],
                    CONFIG['DB_USERNAME'],
                    CONFIG['DB_PASSWORD']
                );
            } catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
    