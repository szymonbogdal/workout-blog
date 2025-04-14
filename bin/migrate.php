<?php
require_once __DIR__ . '/../src/database/DatabaseSetup.php';

try{
    $options = getopt('r', ['rollback']);
    $setup = new DatabaseSetup();
    if(isset($options['r']) || isset($opption['rollback'])){
        $setup->rollbackMigrations();
    }else{
        $setup->runMigrations();
    }
}catch (Exception $e){
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}