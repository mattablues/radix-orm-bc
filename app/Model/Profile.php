<?php

declare(strict_types=1);

namespace App\Model;

use AllowDynamicProperties;
use Radix\Database\ORM\Relation\BelongsToRelation;
use Radix\Model;

#[AllowDynamicProperties]
class Profile extends Model
{
    protected string $table = 'profiles';

    public function user(): BelongsToRelation
    {
        return $this->belongsTo('App\Model\User','id');
    }
}