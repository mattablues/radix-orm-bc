<?php

declare(strict_types=1);

namespace App\Model;

use AllowDynamicProperties;
use Radix\Database\ORM\Relation\BelongsToRelation;
use Radix\Model\Model;

/**
 * @method static first()
 */
#[AllowDynamicProperties]
class Post extends Model
{
    protected string $table = 'posts';

    public function user(): BelongsToRelation
    {
        return $this->belongsTo('App\Model\User', 'user_id');
    }
}