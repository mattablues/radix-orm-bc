<?php

declare(strict_types=1);

namespace Radix\Database\ORM\Relation;

class HasManyRelation extends Relation
{
protected string $type = 'has_many';
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

    public function initiateConnection(): HasManyRelation
    {
        $referenceModel = $this->referenceModel;

        if(!$this->connectionInitiated && !empty($referenceModel)){
            $localKey = $this->localKey;
            $this->where($this->relationTable . "." . $this->foreignKey, '=', $referenceModel->$localKey);
            $this->connectionInitiated = true;
        }

        return $this;
    }

    public function buildRelationDataQuery(array $data): HasManyRelation
    {
        $ids = array_column($data, $this->localKey);
        $this->whereIn($this->relationTable . '.' . $this->foreignKey, $ids);

        return $this;
    }

    public function addRelationData(string $relationName, array $data, array $relationData): array
    {
        $localKey = $this->localKey;
        $foreignKey = $this->foreignKey;

        foreach($data as $key => $referenceModel) {
            $filteredRelationData = array_filter($relationData, function($relationDataObject) use($foreignKey, $localKey, $referenceModel) {
                return $referenceModel->$localKey === $relationDataObject->$foreignKey;
            });

            $referenceModel->$relationName = $filteredRelationData;
            $data[$key] = $referenceModel;
        }

        return $data;
    }

    public function create(array $data): ?object
    {
        $foreignKey = $this->foreignKey;
        $referenceModel = $this->referenceModel;
        $localKey = $this->localKey;
        $data[$foreignKey] = $referenceModel->$localKey;

        return parent::create($data);
    }
}