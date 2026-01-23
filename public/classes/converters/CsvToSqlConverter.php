<?php

namespace taskforce\classes\converters;

use SplFileObject;

class CsvToSqlConverter
{
    private array $csvFilesPath;
    private array $csvFiles;
    private array $sqlData;


    public function __construct(array $csvFilesPath)
    {
        $this -> csvFilesPath = $csvFilesPath;
    }

    private function getFiles(): void
    {
        foreach ($this -> csvFilesPath as $key => $csvFilePath) {
            $this->csvFiles[$key] = new SplFileObject($csvFilePath, 'r');
        }
    }

    public function convert(): void
    {
        $this->getFiles();

        foreach ($this->csvFiles as $key => $csvFile) {

        $csvFile->setFlags(SplFileObject::READ_CSV);

        $sql = '';
        $header = null;

        // Читаем первую строку как заголовок, если нужно
        if (!$csvFile->eof()) {
            $header = $csvFile->current();
            $csvFile->next();
        }

        while (!$csvFile->eof()) {
            $row = $csvFile->current();
            $csvFile->next();

            if ($row === [null] || count(array_filter($row)) === 0) {
                continue; // Пропускаем пустые строки
            }

            $values = array_map([$this, 'escape'], $row);
            $columns = $header ? '`' . implode('`, `', $header) . '`' : $this->generateColumnPlaceholders(count($values));

            $sql .= "INSERT INTO `$key` ($columns) VALUES ('" . implode("', '", $values) . "');\n";
        }

          $this->sqlData[$key] = $sql;
        }
    }

    public function getSqlData(): array
    {
        return $this->sqlData;
    }

    private function escape(string|null $value): string
    {
        if ($value === null) {
            return '';
        }
        // Простое экранирование одинарных кавычек
        return str_replace("'", "''", trim($value));
    }

    private function generateColumnPlaceholders(int $count): string
    {
        return implode(', ', array_map(fn($i) => "col$i", range(1, $count)));
    }
}