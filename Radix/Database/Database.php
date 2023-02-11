<?php

declare(strict_types=1);


namespace Radix\Database;

use PDO;
use PDOStatement;
use Radix\Database\QueryBuilder;
use stdClass;

class Database
{
    private PDO $connection;
    private array $params = [];

    public function __construct(DatabaseConnection $connection)
    {
        $this->connection = $connection->get();
    }

    public function table(string $table): QueryBuilder
    {
        $queryBuilder = new QueryBuilder();

        return $queryBuilder->table($table);
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function fetchData(string $sql, string $class = stdClass::class): array
    {
        $class = new $class();

        $result = $this->run($sql, $this->params);

        $resultArray = $result->fetchAll(PDO::FETCH_CLASS, $class::class);

        $result->closeCursor();

        return $resultArray;
    }

    public function insert(string $sql): ?int
    {
        return $this->run($sql, $this->params)->rowCount() ?? null;
    }

    public function insertGetId(string $sql): int
    {
        $this->run($sql, $this->params);

        return (int) $this->connection->lastInsertId();
    }

    public function update(string $sql): ?PDOStatement
    {
        return $this->run($sql, $this->params) ?? null;
    }

    public function delete(string $sql): ?int
    {
        return $this->run($sql, $this->params)->rowCount() ?? null;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function run(string $sql, array $params = []): ?PDOStatement
    {
        $this->params = $this->params ?? $params;

        if (!$params) {
            return $this->connection->query($sql);
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }
}