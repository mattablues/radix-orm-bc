<?php

declare(strict_types=1);

namespace Radix\Database\ORM\Relation;

class BelongsToRelation extends Relation
{
 protected string $type = 'belongs_to';
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

    public function initiateConnection(): BelongsToRelation
    {
        $referenceModel = $this->referenceModel;

        if(!empty($referenceModel) && !$this->connectionInitiated) {
            $foreignKey = $this->foreignKey;
            $this->where($this->relationTable . '.' . $this->localKey, '=', $referenceModel->$foreignKey);
            $this->connectionInitiated = true;
        }

        return $this;
    }

    public function buildRelationDataQuery(array $data): void
    {
        $ids = array_column($data, $this->foreignKey);

        $this->whereIn($this->relationTable . '.' . $this->localKey, $ids);
    }

    public function addRelationData(string $relationName, array $data, array $relationData): array
    {
        $foreignKey = $this->foreignKey;
        $localKey = $this->localKey;

        foreach($data as $key => $referenceModel) {
            $filteredRelationData = array_filter($relationData, function($relationDataObject) use($foreignKey, $localKey, $referenceModel){
                return $referenceModel->$foreignKey === $relationDataObject->$localKey;
            });

            if(count($filteredRelationData)) {
                $referenceModel->$relationName = current($filteredRelationData);
            }
            else{
                $referenceModel->$relationName = null;
            }

            $data[$key] = $referenceModel;
        }

        return $data;
    }
}