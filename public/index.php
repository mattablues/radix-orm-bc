<?php

declare(strict_types=1);

use App\Model\Post;
use App\Model\Profile;
use App\Model\User;
use Dotenv\Dotenv;
use Radix\Database\QueryBuilder;

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$queryBuilder= new QueryBuilder();


try {

/*dd(User::where('id', '=', 1)->orWhereLike('name', 'al')->whereLike('age', 21)->get());*/
/*    $result = $queryBuilder->table('users')
        ->whereLike('name', 'al', 'start')
        ->orWhereLike('name', 'al', 'end')
        ->get();
dd($result, $queryBuilder->getParams());*/

/*dd(User::get());*/
/*$user = User::with(['profile'])->where('id', '=', 1)->first();
dd($user->profile()->get());*/

/*$user = User::find(1);
dd($user);
dd($user->change(['name' => 'Mats']));*/

/*$queryBuilder = new QueryBuilder();
$query = $queryBuilder->table('ratings')->sum('rating');
dd($query);*/

/*$user = User::with(['profile'])->where('id', '=', 2)->first();
dd($user);*/

/*$user = User::find(1);
dd($user);
dd($user->profile()->first(), $user->posts()->get());*/

/*$user = User::findOrFail(10);
$user->name = 'Kollo';
$user->save();
dd(User::get());*/

// Attach
//$user = User::create(['name' => 'Kalte', 'age' => 55]);
//
//
/*$user = User::find(1);
$user->ratedPosts()->attach([
    20 => ['rating' => 2],
    14 => ['rating' => 5]
]);*/

// Belongs to many relation
// Detach
/*$user = User::find(1)->ratedPosts()->detach([14, 20]);
dd($user);*/


/*$users = User::with(['ratedPosts'])->limit(2)->get();
foreach($users as $user) {
    echo $user->name . '<br>';
    foreach($user->ratedPosts as $post) {
        echo $post->title . '<br>';
    }
}*/

/*dd(User::with(['ratedPosts'])->first());*/

/*$user = User::first();
dd($user->ratedPosts());*/

/*User::update(['name' => 'name']);*/


/* ----------------------------------------------------------------------- here */

/*$user = User::find(9);
dd($user->profile()->create(['country' => 'Russia', 'city' => 'Moskva']));*/

//$user = User::create(['name' => 'Malowski', 'age' => 29]);
//dd($user);

/*    $user = User::find(1);
    $user->posts()->create(['title' => 'New malte post']);*/


/*$profile = Profile::get();
dd($profile);*/

// With has one relation

/*$profile = Profile::with(['user'])->first();
echo $profile->user->name;*/

/*$user = User::with(['profile'])->first();
echo $user->profile->country;*/

// With and has many relation
/*$user = User::with(['posts'])->get();
$user = $user[0];
foreach($user->posts as $post) {
    echo $post->title . '<br>';
}

dd($user);*/

/*$user = User::with(['profile', 'posts' => function($posts) {
    $posts->where('created_at', '>', '2020-09-23');
}])->limit(20)->get();
dd($user);*/

// Relationships
/*$profile = Profile::first();
dd($profile->user()->first()->name);*/

/*$user = User::first();
dd($user->profile()->create(['id' => $user->id, 'country' => 'Sweden', 'city' => 'Alingsås']));*/

/*$posts = Post::first();
$user = $posts->user()->get();
dd($user);*/

/*$user = User::find(1);
$user->posts()->create(['title' => 'My Created Post']);*/

/*$user = User::find(1);
dd($user->posts()->delete());*/
/*$user = User::find(2);
$post = $user->posts()->where('title', '=', 'alex post 1')->update(['title' => 'alex first updated post']);
dd($post);*/

/*$user = User::find(2);
$posts = $user->posts()->get();
foreach($posts as $post) {
    echo $post->title . '<br>';
}*/

/*$user = User::find(1);
$posts = $user->posts();
dd($posts->toSql());*/

/*$user = User::find(2);
$posts = $user->posts()->where('id', '=', 11)->first();
dd($posts);*/

// Save method update user
/*$user = User::first();
$user->name = 'Malte';
$user->save();*/

/*$user = User::find(2);
$user->name = 'Alexander';
dd($user->save());*/

// Save method new user
/*$user = new User();
$user->name = 'Helge';
$user->age = 65;
dd($user->save());*/

/*$user =  User::find(5)->change(['age' => 20]);
dd($user);*/

/*$user = User::find(10)->remove();
dd($user);*/

/*User::update(['age' => 30]);*/

/*User::where('name', '=', 'Melina')->orWhere('id', '=', 5)->update(['age' => 27]);*/

//User::where('id', '=', 7)->delete();

/*$user = User::select()->where('name', '=', 'Malte')->first();
dd($user);*/

/*$data = [
    ['name' => 'Mats', 'age' => 55],
    ['name' => 'Hugo', 'age' => 11],
    ['name' => 'Wilma', 'age' => 24],
    ['name' => 'Moa', 'age' => 27],
    ['name' => 'Lou', 'age' => 4],
    ['name' => 'Simon', 'age' => 32],
    ['name' => 'Martin', 'age' => 26],
];

dd($queryBuilder->table('users')->insert($data));*/


//$user = User::select()->where('name', '=', 'Mats')->first();
//dd($user);

//User::create(['name' => 'alexandra', 'age' => 100]);

/*dd(User::where('id', '>', 2)->first());*/

/*dd(User::first());*/

/*dd(User::where('name', '=', 'Mats')->orWhere('name', '=', 'malle')->avg('age'));*/

/*$user = User::select(['name', 'age'])->where('age', '=', 55)->orWhere('name', '=', 'Malte')->get();
dd($user);*/


/*dd($queryBuilder->table('users')->where('name', '=', 'Malte')->update(['name' => 'Mats']));*/

/*dd($queryBuilder->table('users')->where('id', '=', 24)->delete());*/

/*$data = [
    ['name' => 'Helmut', 'age' => 2],
    ['name' => 'Forza', 'age' => 9],
];
dd($queryBuilder->table('users')->insert($data));*/

/*$users = $queryBuilder->table('users')->get(User::class);
$avg = $queryBuilder->table('users')->avg('age');
echo 'AVG: ' . $avg . '<br>';
$min = $queryBuilder->table('users')->min('age');
echo 'MIN: ' . $min . '<br>';
$max = $queryBuilder->table('users')->max('age');
echo 'MAX: ' . $max . '<br>';
$sum = $queryBuilder->table('users')->sum('age');
echo 'SUM: ' . $sum . '<br>';
$count = $queryBuilder->table('users')->count('age');
echo 'COUNT: ' . $count . '<br>';*/

/*$users = $queryBuilder->table('users')->get();
dd($users);*/

/*$query = $queryBuilder->table('users')
    ->join('posts', 'users.id', '=', 'posts.user_id')
    ->whereIn('name', ['alexander', 'mats'])->get();
dd($query);*/


/*$query = $queryBuilder->table('users')->whereIn('name', ['mats',  'matte'])->orWhereIn('age', [20, 30, 40])->orWhere(function($builder) {
    $builder->where('age', '>', 20)->orWhereIn('name', ['hugo', 'malle']);
})->get();
dd($query);*/


/*$query = $queryBuilder->table('users')->where('age', '<', 100)->orWhere('age', '>', 20)->where(function($builder) {
    $builder->where('name', '=', 'alex')->orWhere('name', '=', 'alex')->getQueryString();
})->get();
dd($query);*/

/*$query = $queryBuilder->orderBy('id')->table('users')->get();
dd($query);*/

/*$query = $queryBuilder->table('users')->where('name', '=', 'Mats')->where(function($builder) {
    $builder->where('age', '>', 20)->where('age', '<', 100);
})->get();

dd($query);*/

} catch (Exception $exception) {
    echo $exception->getMessage() . ' <pre>' . $exception->getTraceAsString() . '</pre>';
} catch (Error $error) {
    echo $error->getMessage() . ' <pre>' . $error->getTraceAsString() . '</pre>';
}