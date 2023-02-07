<?php

declare(strict_types=1);

namespace Radix\Database\ORM\Relation;

use Radix\Configuration\Env;
use Radix\Database\Database;
use Radix\Database\DatabaseConnection;
use stdClass;

class BelongsToManyRelation extends Relation
{
protected string $type = 'belongs_to_many';
    protected string $referenceTable;
    protected string $relationTable;
    protected string $pivotTable;
    protected string $referenceTableForeignKey;
    protected string $relationTableForeignKey;
    protected string $referenceTableLocalKey;
    protected ?string $relationTableLocalKey;
    protected array $pivotColumns = [];
    protected Database $db;

    public function __construct(string $referenceTable, string $pivotTable, string $relationTable, string $referenceTableForeignKey,
        string $relationTableForeignKey, string $referenceTableLocalKey, ?string $relationTableLocalKey)
    {
        parent::__construct();
        $config = new Env();
        $connection =  new DatabaseConnection($config);
        $this->db = new Database($connection);
        $this->referenceTable = $referenceTable;
        $this->relationTable = $relationTable;
        $this->pivotTable = $pivotTable;
        $this->referenceTableForeignKey = $referenceTableForeignKey;
        $this->relationTableForeignKey = $relationTableForeignKey;
        $this->referenceTableLocalKey = $referenceTableLocalKey;
        $this->relationTableLocalKey = $relationTableLocalKey;
    }

    public function addPivotData(array $data): array
    {
        if(count($this->pivotColumns)){
            foreach($data as $key => $dataObject){
                $pivot = new stdClass;

                foreach($this->pivotColumns as $col){
                    $pivot->$col = $dataObject->$col;
                    unset($dataObject->$col);
                }

                $dataObject->pivot = clone $pivot;
                $data[$key] = $dataObject;
            }
        }

        return $data;
    }

    public function get(): array
    {
        $data = parent::get();

        return $this->addPivotData($data);
    }

    public function first(): object|null
    {
        $model = parent::first();
        $data = $this->addPivotData([$model]);
        return current($data);
    }

    public function withPivot(string|array $cols): BelongsToManyRelation
    {
        $cols = is_array($cols) ? $cols : [$cols];

        foreach($cols as $col){
            $this->pivotColumns[] = $col;
        }

        if(count($this->pivotColumns)){
            $this->select($this->model->getTable() . '.*');

            foreach($this->pivotColumns as $col){
                $this->select($this->pivotTable . '.' . $col);
            }
        }

        return $this;
    }

    public function initiateConnection(): BelongsToManyRelation
    {
        if(!$this->connectionInitiated){
            $this->join($this->pivotTable, $this->relationTable . '.' . $this->relationTableLocalKey,
                '=',$this->pivotTable . '.' . $this->relationTableForeignKey);

            $this->join($this->referenceTable, $this->referenceTable . '.' . $this->referenceTableLocalKey, '=',
                $this->pivotTable . '.' . $this->referenceTableForeignKey);
            $this->connectionInitiated = true;
        }

        $referenceModel = $this->referenceModel;

        if(!empty($referenceModel)){
            $referenceTableLocalKey = $this->referenceTableLocalKey;
            $this->where($this->pivotTable . '.' . $this->referenceTableForeignKey, '=',$referenceModel->$referenceTableLocalKey);
        }

        $this->withPivot($this->referenceTableForeignKey);

        return $this;
    }

    public function buildRelationDataQuery(array $data): void
    {
        $ids = array_column($data, $this->referenceTableLocalKey);
        $this->whereIn($this->pivotTable.'.'.$this->referenceTableForeignKey, $ids);
    }

    public function addRelationData(string $relationName, array $data, array $relationData): array
    {
        $referenceTableLocalKey = $this->referenceTableLocalKey;
        $referenceTableForeignKey = $this->referenceTableForeignKey;

        foreach($data as $key => $referenceModel){
            $filteredRelationData = array_filter($relationData, function($relationObj) use($referenceTableForeignKey, $referenceTableLocalKey,
                $referenceModel) {
                return $referenceModel->$referenceTableLocalKey === $relationObj->pivot->$referenceTableForeignKey;
            });

            $referenceModel->$relationName = $filteredRelationData;
            $data[$key] = $referenceModel;
        }

        return $data;
    }

    public function attach(array $data): int
    {
        $insertableData = [];
        $referenceModel = $this->referenceModel;
        $referenceTableLocalKey = $this->referenceTableLocalKey;

        foreach($data as $key => $value) {
            $insertableRow = [];

            $insertableRow[$this->referenceTableForeignKey] = $referenceModel->$referenceTableLocalKey;

            if(is_array($value)) {
                $insertableRow[$this->relationTableForeignKey] = $key;

                foreach($value as $vk => $vv){
                    $insertableRow[$vk] = $vv;
                }
            }
            else {
                $insertableRow[$this->relationTableForeignKey] = $value;
            }

            $insertableData[] = $insertableRow;
        }

        $affectedRows = 0;

        foreach($insertableData as $row) {
            $this->db->table($this->pivotTable)->insert($row);
            $affectedRows++;
        }

        return $affectedRows;
    }

    public function detach(array $data): int
    {
        $referenceModel = $this->referenceModel;
        $referenceTableLocalKey = $this->referenceTableLocalKey;

        $queryBuilder = $this->db->table($this->pivotTable);
        $queryBuilder->where($this->referenceTableForeignKey, '=', $referenceModel->$referenceTableLocalKey);
        $queryBuilder->whereIn($this->relationTableForeignKey, $data);

        return $queryBuilder->delete();
    }
}