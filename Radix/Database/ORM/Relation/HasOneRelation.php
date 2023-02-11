<?php

declare(strict_types=1);

namespace Radix\Database\ORM\Relation;

class HasOneRelation extends Relation
{
 protected string $type = 'has_one';
    protected string $referenceTable;
    protected string $relationTable;
    protected string $foreignKey;
    protected ?string $localKey;

    public function __construct($referenceTable, $relationTable, $foreignKey, $localKey)
    {
        parent::__construct();
        $this->referenceTable = $referenceTable;
        $this->relationTable = $relationTable;
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
    }

    public function initiateConnection(): HasOneRelation
    {
        $referenceModel = $this->referenceModel;

        if(!empty($referenceModel) && !$this->connectionInitiated) {
            $localKey = $this->localKey;
            $this->where($this->relationTable . '.' . $this->foreignKey, '=', $referenceModel->$localKey);
            $this->connectionInitiated = true;
        }

        return $this;
    }

    public function buildRelationDataQuery(array $data): void
    {
        $ids = array_column($data, $this->localKey);
        $this->whereIn($this->relationTable . '.' . $this->foreignKey, $ids);
    }

    public function addRelationData(string $relationName, array $data, array $relationData): array
    {
        $localKey = $this->localKey;
        $foreignKey = $this->foreignKey;

        foreach($data as $key => $referenceModel) {
            $filteredRelationData = array_filter($relationData, function($relationDataObject) use($referenceModel, $localKey, $foreignKey) {
                return $referenceModel->$localKey === $relationDataObject->$foreignKey;
            });

            if(count($filteredRelationData)) {
                $referenceModel->$relationName = current($filteredRelationData);
            }
            else {
                $referenceModel->$relationName = null;
            }

            $data[$key] = $referenceModel;
        }

        return $data;
    }

    public function create(array $data): ?object
    {
        $foreignKey = $this->foreignKey;
        $localKey = $this->localKey;
        $referenceModel = $this->referenceModel;
        $data[$foreignKey] = $referenceModel->$localKey;

        return parent::create($data);
    }
}