<?php

declare(strict_types=1);

namespace App\Model;

use AllowDynamicProperties;
use Radix\Database\ORM\Relation\BelongsToManyRelation;
use Radix\Database\ORM\Relation\HasManyRelation;
use Radix\Database\ORM\Relation\HasOneRelation;
use Radix\Model\Model;

/**
 * @method static find(int $int)
 * @method static get()
 * @method static with(string[] $array)
 * @method static findOrFail(int $int)
 * @method static whereLike(string $string, string $string1)
 */
#[AllowDynamicProperties]
class User extends Model
{
    protected string $table = 'users';

        public function posts(): HasManyRelation
    {
        return $this->hasMany('App\Model\Post','user_id');
    }

    public function profile(): HasOneRelation
    {
        return $this->hasOne('App\Model\Profile');
    }

    public function ratedPosts(): BelongsToManyRelation
    {
        return $this->belongsToMany('App\Model\Post', 'ratings', 'user_id', 'post_id')->withPivot('rating');
    }
}