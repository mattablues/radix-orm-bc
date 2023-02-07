<?php

declare(strict_types=1);


namespace Radix\Database;

use PDO;
use PDOException;
use Radix\Configuration\Configurable;
use Radix\Exception\DatabaseException;

class DatabaseConnection
{
    protected ?PDO $pdo = null;
    protected Configurable $config;

    public function __construct(Configurable $config)
    {
        $this->config = $config;

        if ($this->pdo === null) {
            try {

                $dsn = $config->get('db_driv') .
                    ':host='   . $config->get('db_host') .
                    ';port='   . $config->get('db_port') .
                    ';dbname='  . $config->get('db_name') .
                    ';charset=' . $config->get('db_char');

                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_FOUND_ROWS => true,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING,
                ];

                $this->pdo = new PDO($dsn, $this->config->get('db_user'), $this->config->get('db_pass'), $options);

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