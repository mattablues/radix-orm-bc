<?php
/** @noinspection PhpUnusedPrivateFieldInspection */
/** @noinspection SqlWithoutWhere */

declare(strict_types=1);

namespace Radix\Database;

use PDOStatement;
use Radix\Configuration\Config;
use Radix\Configuration\Configurable;
use stdClass;

class QueryBuilder
{
    private string $table = '';
    public array $params = [];
    private array $select = [];
    private string $where = '';
    private string $whereLike = '';
    private string $orWhereLike = '';
    private string $join = '';
    private string $rightJoin = '';
    private string $leftJoin = '';
    private string $orderBy = '';
    private string $groupBy = '';
    private string $having = '';
    private string $offset = '';
    private string $limit = '';
    private string $queryString = '';
    private bool $appendAndKeyword = false;
    private bool $appendOrKeyword = false;
    private int $counter = 1;
    private ?string $search = null;
    private Configurable $config;
    private array $selectQueryClauses = [
        'join', 'leftJoin', 'rightJoin', 'where', 'whereLike', 'orWhereLike'.
        'groupBy', 'having', 'orderBy', 'limit', 'offset'
    ];

    public function __construct()
    {
        $this->config = new Config('db');
    }

    public function table(string $table): QueryBuilder
    {
        $this->table = $table;

        return $this;
    }

    public function select(string|array $select = []): QueryBuilder
    {
        if (is_string($select)) {
            if (!in_array($select, $this->select)) {
                $this->select[] = $select;
            }
        } elseif (is_array($select)) {
            $this->select = $select;
        }

        return $this;
    }

    public function where(callable|string $col, ?string $operator = null, mixed $value = null): QueryBuilder
    {
        $args = func_get_args();

        if (empty($this->where)) {
            $this->where = ' WHERE ';
        }

        if (count($args) === 3) {
            if ($this->appendAndKeyword === true) {
                $this->where .= ' AND ';
            }

            $this->where .= $col . ' ' . $operator . ' :' . implode(', :', $this->preparePlaceholderAndData($value));

            $this->counter++;
            $this->appendAndKeyword = true;

        } elseif (count($args) === 1) {
            if ($this->where !== ' WHERE ') {
                $this->where .= ' AND ';
            }

            $this->where .= ' ( ';
            $this->appendAndKeyword = false;
            $this->appendOrKeyword = false;
            $col($this);
            $this->where .= ' ) ';
        }

        return $this;
    }

    public function orWhere(callable|string $col, ?string $operator = null, mixed $value = null): QueryBuilder
    {
        $args = func_get_args();

        if (empty($this->where)) {
            $this->where = ' WHERE ';
        }

        if (count($args) === 3) {
            if ($this->appendAndKeyword || $this->appendOrKeyword) {
                $this->where .= ' OR ';
            }

            $this->where .= $col . $operator . ':' . implode(', :', $this->preparePlaceholderAndData($value));

            $this->appendOrKeyword = true;

        } elseif (count($args) === 1) {
            if ($this->where !== ' WHERE ') {
                $this->where .= ' OR ';
            }

            $this->where .= ' ( ';
            $this->appendOrKeyword = false;
            $this->appendAndKeyword = false;
            $col($this);
            $this->where .= ' ) ';
        }

        return $this;
    }

    public function whereLike(callable|string $col, mixed $value = null, ?string $startOrEnd = null): QueryBuilder
    {
        $this->search = $startOrEnd;
        $args = func_get_args();

        unset($args[2]);

        if (empty($this->where)) {
            $this->where = ' WHERE ';
        }

        if (count($args) === 2) {
            if ($this->appendAndKeyword === true) {
                $this->where .= ' AND ';
            }

            $this->where .= $col . ' LIKE :' . implode(', :', $this->prepareLikePlaceholderAndData($value));

            $this->counter++;
            $this->appendAndKeyword = true;

        } elseif (count($args) === 1) {
            if ($this->where !== ' WHERE ') {
                $this->where .= ' AND ';
            }

            $this->where .= ' ( ';
            $this->appendAndKeyword = false;
            $this->appendOrKeyword = false;
            $col($this);
            $this->where .= ' ) ';
        }

        return $this;
    }

    public function orWhereLike(callable|string $col, mixed $value = null, ?string $startOrEnd = null): QueryBuilder
    {
        $this->search = $startOrEnd;
        $args = func_get_args();

        unset($args[2]);

        if (empty($this->where)) {
            $this->where = ' WHERE ';
        }

        if (count($args) === 2) {
            if ($this->appendAndKeyword || $this->appendOrKeyword) {
                $this->where .= ' OR ';
            }

            $this->where .= $col . ' LIKE :' . implode(', :', $this->prepareLikePlaceholderAndData($value));

            $this->appendOrKeyword = true;

        } elseif (count($args) === 1) {
            if ($this->where !== ' WHERE ') {
                $this->where .= ' OR ';
            }

            $this->where .= ' ( ';
            $this->appendOrKeyword = false;
            $this->appendAndKeyword = false;
            $col($this);
            $this->where .= ' ) ';
        }

        return $this;
    }

    public function whereIn(string $col, array $values): QueryBuilder
    {
        if (empty($this->where)) {
            $this->where = ' WHERE ';
        }

        if ($this->appendAndKeyword || $this->appendOrKeyword) {
            $this->where .= ' AND ';
        }

        $this->where .= $col . ' IN (:' . implode(', :', $this->preparePlaceholderAndData($values)) . ')';
        $this->appendAndKeyword = true;

        return $this;
    }

    /**
     * Or Where In
     * @param  string  $col
     * @param  array  $values
     * @return $this
     */
    public function orWhereIn(string $col, array $values): QueryBuilder
    {
        if (empty($this->where)) {
            $this->where = ' WHERE ';
        }

        if ($this->appendAndKeyword || $this->appendOrKeyword) {
            $this->where .= ' OR ';
        }

        $this->where .= $col . ' IN (:' . implode(', :', $this->preparePlaceholderAndData($values)) . ')';
        $this->appendOrKeyword = true;

        return $this;
    }

    /**
     * Having
     * @param  string  $col
     * @param  string  $operator
     * @param  mixed  $value
     * @return $this
     */
    public function having(string $col, string $operator, mixed $value): QueryBuilder
    {
        $this->where .= " HAVING " . $col . $operator . ':' . implode(', :', $this->preparePlaceholderAndData($value));

        return $this;
    }

    /**
     * Join tables
     * @param  string  $table
     * @param  string  $colOne
     * @param  string  $operator
     * @param  string  $colTwo
     * @return $this
     */
    public function join(string $table, string $colOne, string $operator, string $colTwo): QueryBuilder
    {
        $this->join .= ' INNER JOIN ' . $table . ' ON ' . $colOne . ' ' . $operator . ' ' . $colTwo;

        return $this;
    }

    /**
     * Left join
     * @param  string  $table
     * @param  string  $colOne
     * @param  string  $operator
     * @param  string  $colTwo
     * @return $this
     */
    public function leftJoin(string $table, string $colOne, string $operator, string $colTwo): QueryBuilder
    {
        $this->join .= ' LEFT JOIN ' . $table . ' ON ' . $colOne . ' ' . $operator . ' ' . $colTwo;

        return $this;
    }

    /**
     * Right join
     * @param  string  $table
     * @param  string  $colOne
     * @param  string  $operator
     * @param  string  $colTwo
     * @return $this
     */
    public function rightJoin(string $table, string $colOne, string $operator, string $colTwo): QueryBuilder
    {
        $this->join .= ' RIGHT JOIN ' . $table . ' ON ' . $colOne . ' ' . $operator . ' ' . $colTwo;

        return $this;
    }

    /**
     * Group data by field (col)
     * @param  string  $col
     * @return $this
     */
    public function groupBy(string $col): QueryBuilder
    {
        $this->groupBy = 'GROUP BY ' . $col;

        return $this;
    }

    /**
     * Order data by field (col) direction ASC or DESC
     * @param  string  $col
     * @param  string  $direction
     * @return $this
     */
    public function orderBy(string $col, string $direction = 'ASC'): QueryBuilder
    {
        $this->orderBy = 'ORDER BY ' . $col . ' ' . $direction;

        return $this;
    }

    /**
     * LIMIT
     * @param  int  $limit
     * @return $this
     */
    public function limit(int $limit): QueryBuilder
    {
        $this->limit = ' LIMIT :' . implode(', :', $this->preparePlaceholderAndData($limit));

        return $this;
    }

    /**
     * LIMIT
     * @param  int  $offset
     * @return $this
     */
    public function offset(int $offset): QueryBuilder
    {
        $this->offset = ' OFFSET :' . implode(', :', $this->preparePlaceholderAndData($offset));

        return $this;
    }

    public function avg(string $col): float
    {
        $select = ['AVG(' . $col . ')'];
        $this->select($select);
        $data = $this->get();

        $object = current($data);
        $property = current($select);

        return (float) $object->$property;
    }

    public function sum(string $col): float
    {
        $select = ['SUM(' . $col . ')'];
        $this->select($select);
        $data = $this->get();

        $object = current($data);
        $property = current($select);

        return (float) $object->$property;
    }

    public function count(string $col): int
    {
        $select = ['COUNT(' . $col . ')'];
        $this->select($select);
        $data = $this->get();

        $object = current($data);
        $property = current($select);

        return (int) $object->$property;
    }

    public function min(string $col): float
    {
        $select = ['MIN(' . $col . ')'];
        $this->select($select);
        $data = $this->get();

        $object = current($data);
        $property = current($select);

        return (float) $object->$property;
    }

    public function max(string $col): float
    {
        $select = ['MAX(' . $col . ')'];
        $this->select($select);
        $data = $this->get();

        $object = current($data);
        $property = current($select);

        return (float) $object->$property;
    }

    public function get(string $class = stdClass::class): array
    {
        $class = new $class();

        $this->buildSelectQuery();
        $connection = new DatabaseConnection($this->config);
        $db = new Database($connection);

        $db->setParams($this->getParams());

        return $db->fetchData($this->queryString, $class::class);
    }

    public function getQueryString(): string {
        $this->buildSelectQuery();

        return $this->queryString;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function insert(array $data): int
    {
        $this->buildInsertQuery($data);

        $connection = new DatabaseConnection($this->config);
        $db = new Database($connection);

        $db->setParams($this->getParams());

        return $db->insert($this->queryString);
    }

    /**
     * Insert  row and get id
     * @param  array  $data
     * @return int
     */
    public function insertGetId(array $data): int
    {
        $this->buildInsertQuery($data);

        $connection = new DatabaseConnection($this->config);
        $db = new Database($connection);

        $db->setParams($this->getParams());

        return $db->insertGetId($this->queryString);
    }

    /**
     * Delete from table
     * @return int
     */
    public function delete(): int {
        $this->buildDeleteQuery();

        $connection = new DatabaseConnection($this->config);
        $db = new Database($connection);

        $db->setParams($this->getParams());

        return $db->delete($this->queryString);
    }

    /**
     * Update row in database
     * @param  array  $data
     * @return PDOStatement|null
     */
    public function update(array $data): ?PDOStatement
    {
        $this->buildUpdateQuery($data);

        $connection = new DatabaseConnection($this->config);
        $db = new Database($connection);

        $db->setParams($this->getParams());

        return $db->update($this->queryString);
    }

    /**
     * Build Insert Query
     * @param  array  $data
     * @return void
     */
    private function buildInsertQuery(array $data): void
    {
        $this->counter = 1;

        if (is_assoc($data)) {
            $cols = array_keys($data);

        } else {
            $cols = array_keys($data[0]);
        }

        $query = 'INSERT INTO ' . $this->table . '(' . implode(', ', $cols) . ') VALUES ';

        if (is_assoc($data)) {
            $query .= '(:' . implode(', :', $this->preparePlaceholderAndData( $data)) .')';

        } else {
            foreach ($data as $row) {
                $query .= '(:' . implode(', :', $this->preparePlaceholderAndData($row)) .'), ';
            }

            $query = substr($query,0, strrpos($query, ', '));
        }

        $this->queryString = $query;
    }

    /**
     * Build Delete Query
     * @return void
     */
    private function buildDeleteQuery(): void
    {
        $query = 'DELETE FROM ' . $this->table;

        if(!empty($this->where)) {
            $query .= $this->where;
        }

        $this->queryString = $query;
    }

    /**
     * @param  array  $data
     * @return void
     */
    private function buildUpdateQuery(array $data): void
    {
        $query = 'UPDATE ' . $this->table . ' SET ';


        foreach($data as $col => $value) {
            $query .=  $col . '=:' . implode(', :', $this->preparePlaceholderAndData($value)) . ', ';
        }

        $query = substr($query,0, strrpos($query, ', '));

        if(!empty($this->where)) {
            $query .= $this->where;
        }

        $this->queryString = $query;
    }

    private function buildSelectQuery(): void
    {
        if (empty($this->limit) && !empty($this->offset)) {
            $this->limit(PHP_INT_MAX);
        }

        if (count($this->select) === 0) {
            $query = $this->table . '.*';
        } else {
            $query = implode(', ', $this->select);
        }

        $query = 'SELECT ' . $query . ' FROM ' . $this->table . ' ';

        foreach ($this->selectQueryClauses as $clause) {
            if (!empty($this->$clause)) {
                $query .= $this->$clause . ' ';
            }
        }

        $query = substr($query, 0, strrpos($query, ' '));

        $this->queryString = $query;
    }

    private function preparePlaceholderAndData(mixed $value): array
    {
        $placeholders = [];

        if (!is_array($value)) {
            $arrayKey = $this->renameArrayKey();
            $this->params[$arrayKey] = $value;
            $placeholders[] = $arrayKey;
        } else {
            foreach ($value as $param) {
                $arrayKey = $this->renameArrayKey();
                $this->params[$arrayKey] = $param;
                $placeholders[] = $arrayKey;
            }
        }

        return $placeholders;
    }

    private function prepareLikePlaceholderAndData(mixed $value): array
    {
        $placeholders = [];

        if (!is_array($value)) {
            $arrayKey = $this->renameArrayKey();

            if ($this->search === 'start') {
                $this->params[$arrayKey] = $value . '%';
            } elseif ($this->search === 'end') {
                $this->params[$arrayKey] = '%' . $value;
            } else {
                $this->params[$arrayKey] = '%' . $value . '%';
            }

            $placeholders[] = $arrayKey;
        } else {
            foreach ($value as $param) {
                $arrayKey = $this->renameArrayKey();

                if ($this->search === 'start') {
                    $this->params[$arrayKey] = $param . '%';
                } elseif ($this->search === 'end') {
                    $this->params[$arrayKey] = '%' . $param;
                } else {
                    $this->params[$arrayKey] = '%' . $param . '%';
                }
                $placeholders[] = $arrayKey;
            }
        }

        return $placeholders;
    }

    private function renameArrayKey(): string
    {
        $arrayKey = 'ph' . $this->counter;
        $this->counter++;

        return  $arrayKey;
    }
}