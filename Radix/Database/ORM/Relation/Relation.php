<?php

declare(strict_types=1);

namespace Radix\Database\ORM\Relation;

use Radix\Database\ORM\Builder;

abstract class Relation extends Builder
{
    protected string $type = '';
    protected bool $connectionInitiated = false;
    protected ?object $referenceModel = null;

    public function getReferenceModel(): ?object
    {
        return $this->referenceModel;
    }

    public function referenceModel(object $referenceModel): Relation
    {
        $this->referenceModel = $referenceModel;

        return $this;
    }

    abstract public function initiateConnection();
    abstract public function buildRelationDataQuery(array $data);
    abstract public function addRelationData(string $relationName, array $data, array $relationData);
}