<?php

declare(strict_types=1);


namespace Radix\Database;

use PDO;
use PDOException;
use Radix\Config\Configurable;
use Radix\Database\Exception\DatabaseException;

class DatabaseConnection
{
    protected ?PDO $pdo = null;
    protected Configurable $config;

    public function __construct(Configurable $config)
    {
        $this->config = $config;

        if ($this->pdo === null) {
            try {

                $dsn = $config->get('db.driv') .
                    ':host='   . $config->get('db.host') .
                    ';port='   . $config->get('db.port') .
                    ';dbname='  . $config->get('db.name') .
                    ';charset=' . $config->get('db.char');

                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_FOUND_ROWS => true,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING,
                ];

                $this->pdo = new PDO($dsn, $this->config->get('db.user'), $this->config->get('db.pass'), $options);

            } catch (PDOException $exception) {
                throw new DatabaseException($exception->getMessage(), 500);
            }
        }
    }

    public function get(): PDO
    {
        return $this->pdo;
    }
}