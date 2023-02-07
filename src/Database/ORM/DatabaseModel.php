<?php

declare(strict_types=1);

namespace Radix\Database\ORM;

use PDOStatement;
use Radix\Configuration\Configurable;
use Radix\Configuration\Env;
use Radix\Database\Database;
use Radix\Database\DatabaseConnection;
use Radix\Database\ORM\Relation\BelongsToManyRelation;
use Radix\Database\ORM\Relation\BelongsToRelation;
use Radix\Database\ORM\Relation\HasManyRelation;
use Radix\Database\ORM\Relation\HasOneRelation;

abstract class DatabaseModel
{
    protected Database $db;
    protected string $table = '';
    protected Configurable $config;
    protected string $primaryKey = 'id';
    protected static array $builderCallableMethods = [
        'get', 'where', 'orWhere', 'orWhereIn', 'whereIn', 'select',
        'offset', 'limit', 'having', 'groupBy', 'orderBy', 'count',
        'max', 'min', 'join', 'joinLeft', 'joinRight', 'avg', 'sum', 'toSql',
        'first', 'find', 'create', 'update', 'delete', 'with', 'findOrFail'
    ];

    public function __construct()
    {
        $this->config = new Env();
        $connection = new DatabaseConnection($this->config);
        $this->db = new Database($connection);
    }

    public function hasMany(string $RelationClass, string $foreignKey, ?string $localKey = null): HasManyRelation
    {
        $localKey = $localKey ?? $this->primaryKey;
        $relationModel = new $RelationClass;
        $relation = new HasManyRelation($this->table, $relationModel->getTable(), $foreignKey, $localKey);
        $primaryKey = $this->primaryKey;
        $relation->model($relationModel);

        if(isset($this->$primaryKey)) {
            $relation->referenceModel($this);
        }

        $relation->initiateConnection();

        return $relation;
    }

    public function belongsToMany(string $RelationClass, string $pivotTable, string $referenceTableForeignKey, string $relationTableForeignKey, ?string $localKey = null): BelongsToManyRelation
    {
        $primaryKey = $this->primaryKey;
        $localKey = $localKey ?? $primaryKey;
        $relationModel = new $RelationClass;
        $relation = new BelongsToManyRelation($this->table, $pivotTable, $relationModel->getTable(), $referenceTableForeignKey,
            $relationTableForeignKey, $localKey, $relationModel->getPrimaryKey());
        $relation->model($relationModel);

        if(isset($this->$primaryKey)) {
            $relation->referenceModel($this);
        }

        $relation->initiateConnection();

        return $relation;
    }

    public function belongsTo(string $RelationClass, string $foreignKey, ?string $localKey = null): BelongsToRelation
    {
        $primaryKey = $this->primaryKey;
        $localKey = $localKey ?? $primaryKey;

        $relationModel = new $RelationClass;
        $relation = new BelongsToRelation($this->table, $relationModel->getTable(), $foreignKey, $localKey);
        $relation->model($relationModel);

        if(isset($this->$primaryKey)) {
            $relation->referenceModel($this);
        }

        $relation->initiateConnection();

        return $relation;
    }

    public function hasOne(string $RelationClass): HasOneRelation
    {
        $primaryKey = $this->primaryKey;
        $relationModel = new $RelationClass;
        $relation = new HasOneRelation($this->table, $relationModel->getTable(), $relationModel->getPrimaryKey(), $primaryKey);
        $relation->model($relationModel);

        if(isset($this->$primaryKey)){
            $relation->referenceModel($this);
        }

        $relation->initiateConnection();

        return $relation;
    }

    public static function __callStatic(string $method, array $args)
    {
        $class = get_called_class();

        if(in_array($method, static::$builderCallableMethods)){
            $builder = new Builder;
            $modelInstance = new $class;
            $builder->model($modelInstance);
            return call_user_func_array([$builder, $method], $args);
        }

        return call_user_func_array([$class, $method], $args);
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    public function remove()
    {
        $primaryKey = $this->primaryKey;

        if (!isset($this->$primaryKey)) {
            return null;
        }

        $this->db->table($this->table)->where($this->primaryKey, '=', $this->$primaryKey)->delete();
    }

    public function change(array $data): bool|PDOStatement|null
    {
        $primaryKey = $this->primaryKey;

        if(!isset($this->$primaryKey)){
            return false;
        }

        return $this->db->table($this->table)->where($this->primaryKey, '=', $this->$primaryKey)->update($data);
    }

    public function save(): int|PDOStatement|null
    {
        $data = get_object_public_fields($this);
        $cols = $this->db->fetchData('SHOW COLUMNS FROM ' . $this->table);

        foreach($data as $name => $value) {
            $filteredCols = array_filter($cols, function($col) use($name){
                return $name === $col->Field;
            });

            if(!count($filteredCols)) {
                unset($data[$name]);
            }
        }

        $primaryKey = $this->primaryKey;

        if(isset($this->$primaryKey)) {
            return $this->db->table($this->table)->where($this->primaryKey, '=', $this->$primaryKey)->update($data);
        }
        else {
            $id = $this->db->table($this->table)->insertGetId($data);
            $this->$primaryKey = $id;

            return $id;
        }
    }
}