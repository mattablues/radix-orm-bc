<?php

declare(strict_types=1);

namespace Radix\Database\ORM;

use PDOStatement;
use Radix\Database\QueryBuilder;
use Radix\Exception\NotFoundException;
use Radix\Model;

class Builder
{
   protected QueryBuilder $queryBuilder;
    protected Model $model;
    protected array $relations = [];

    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder();
    }

    public function with(array $relations = []): Builder
    {
        foreach($relations as $relationKeyOrName => $relationNameOrClosure){
            if(is_string($relationNameOrClosure)) {
                $relation = call_user_func_array([$this->model, $relationNameOrClosure], []);
                $this->relations[$relationNameOrClosure] = $relation;
            }
            else {
                $relation = call_user_func_array([$this->model, $relationKeyOrName], []);
                $relationNameOrClosure($relation);
                $this->relations[$relationKeyOrName] = $relation;
            }
        }

        return $this;
    }


    public function model(Model $model): Builder
    {
        $this->model = $model;

        return $this;
    }

    public function get(): array
    {
        $data = $this->queryBuilder->table($this->model->getTable())->get(get_class($this->model));

        if(count($data) && count($this->relations)){
            foreach($this->relations as $relationName => $relation){
                $relation->buildRelationDataQuery($data);
                $relationData = $relation->get();
                $data = $relation->addRelationData($relationName, $data, $relationData);
            }
        }

        return $data;
    }

    public function where(): Builder
    {
        call_user_func_array([$this->queryBuilder, 'where'], func_get_args());

        return $this;
    }

    public function orWhere(): Builder
    {
        call_user_func_array([$this->queryBuilder, 'orWhere'], func_get_args());

        return $this;
    }

    public function whereIn(): Builder
    {
        call_user_func_array([$this->queryBuilder, 'whereIn'], func_get_args());

        return $this;
    }

    public function orWhereIn(): Builder
    {
        call_user_func_array([$this->queryBuilder, 'orWhereIn'], func_get_args());

        return $this;
    }

    public function select(): Builder
    {
        call_user_func_array([$this->queryBuilder,'select'], func_get_args());

        return $this;
    }

    public function offset(): Builder
    {
        call_user_func_array([$this->queryBuilder, 'offset'], func_get_args());

        return $this;
    }

    public function limit(): Builder
    {
        call_user_func_array([$this->queryBuilder, 'limit'], func_get_args());

        return $this;
    }

    public function orderBy(): Builder
    {
        call_user_func_array([$this->queryBuilder, 'orderBy'], func_get_args());

        return $this;
    }

    public function groupBy(): Builder
    {
        call_user_func_array([$this->queryBuilder, 'groupBy'], func_get_args());

        return $this;
    }

    public function having(): Builder
    {
        call_user_func_array([$this->queryBuilder, 'having'], func_get_args());

        return $this;
    }

    public function join(): Builder
    {
        call_user_func_array([$this->queryBuilder, 'join'], func_get_args());

        return $this;
    }

    public function leftJoin(): Builder
    {
        call_user_func_array([$this->queryBuilder, 'leftJoin'], func_get_args());

        return $this;
    }

    public function rightJoin(): Builder
    {
        call_user_func_array([$this->queryBuilder, 'rightJoin'], func_get_args());

        return $this;
    }

    public function toSql(): string
    {
        return $this->queryBuilder->table($this->model->getTable())->getQueryString();
    }

    public function avg(): float
    {
        $this->queryBuilder->table($this->model->getTable());

        return call_user_func_array([$this->queryBuilder, 'avg'], func_get_args());
    }

    public function sum(): float
    {
        $this->queryBuilder->table($this->model->getTable());

        return  call_user_func_array([$this->queryBuilder, 'sum'], func_get_args());
    }

    public function count(): int
    {
        $this->queryBuilder->table($this->model->getTable());

        return call_user_func_array([$this->queryBuilder, 'count'], func_get_args());
    }

    public function min(): float
    {
        $this->queryBuilder->table($this->model->getTable());

        return call_user_func_array([$this->queryBuilder, 'min'], func_get_args());
    }

    public function max(): float
    {
        $this->queryBuilder->table($this->model->getTable());

        return call_user_func_array([$this->queryBuilder, 'max'], func_get_args());
    }

    public function first(): ?object
    {
        $this->limit(1);
        $data = $this->get();

        if(count($data) > 0) {
            return current($data);
        }

        return null;
    }

    public function find(mixed $id): ?object
    {
        return $this->where($this->model->getPrimaryKey(), '=', $id)->first();
    }

    /**
     * @throws NotFoundException
     */
    public function findOrFail(mixed $id): ?object
    {
        $result =  $this->where($this->model->getPrimaryKey(), '=', $id)->first();

        if($result === null) {
            throw new NotFoundException('No data found.');
        }

        return $result;
    }

    public function create(array $data): ?object
    {
        $newId = $this->queryBuilder->table($this->model->getTable())->insertGetId($data);

        return $this->model::find($newId);
    }

    public function delete(): int
    {
        return $this->queryBuilder->table($this->model->getTable())->delete();
    }

    /**
     * Update record
     * @param  array  $data
     * @return PDOStatement
     */
    public function update(array $data): PDOStatement
    {
        return $this->queryBuilder->table($this->model->getTable())->update($data);
    }
}