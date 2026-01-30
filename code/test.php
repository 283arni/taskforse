<?php
require_once '../vendor/autoload.php';
ini_set('assert.exception', 1);

use taskforce\classes\converters\CsvToSqlConverter;

$csvData = [
    'categories' => './data/categories.csv',
    'cities' => './data/cities.csv',
];

try {
    $converter = new CsvToSqlConverter($csvData);
    $converter -> convert();
    $sqlData = $converter->getSqlData();

    foreach ($sqlData as $key => $sql) {
        file_put_contents("$key.sql", $sql);
        echo "Конвертация завершена. SQL сохранён в $key.sql\n";
    }
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}