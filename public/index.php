<?php

declare(strict_types=1);

use App\Model\User;
use Dotenv\Dotenv;
use Radix\Database\QueryBuilder;

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$queryBuilder= new QueryBuilder();


try {

dd(User::where('id', '=', 1)->orWhereLike('name', 'al')->whereLike('age', 21)->get());
/*    $result = $queryBuilder->table('users')
        ->whereLike('name', 'al', 'start')
        ->orWhereLike('name', 'al', 'end')
        ->get();
dd($result, $queryBuilder->getParams());*/

//dd(User::get());
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

/*    $user = User::find(20);
    $user->profile()->create(['country' => 'Russia', 'city' => 'Moskva']);*/

//$user = User::create(['name' => 'Malowski', 'age' => 29]);

/*    $user = User::find(1);
    $user->posts()->create(['title' => 'New malte post']);*/


/*    $profile = Profile::get();
    var_dump($profile);*/

// With has one relation

/*    $profile = Profile::with(['user'])->first();
    echo $profile->user->name;*/

/*    $user = User::with(['profile'])->first();
    echo $user->profile->country;*/

// With and has many relation
/*    $user = User::with(['posts'])->get();
    $user = $user[0];
    foreach($user->posts as $post) {
        echo $post->title . '<br>';
    }*/

//var_dump($user);

/*    $user = User::with(['profile', 'posts' => function($posts) {
        $posts->where('created_at', '>', '2020-09-23');
    }])->limit(20)->get();
    var_dump($user);*/

// Relationships

/*    $profile = Profile::first();
    var_dump($profile->user()->first()->name);*/

/*    $user = User::first();
    var_dump($user->profile()->create(['id' => $user->id, 'country' => 'Sweden', 'city' => 'Alingsås']));*/

/*    $posts = Post::first();
    $user = $posts->user()->get();
    var_dump($user);*/

/*    $user = User::find(1);
    $user->posts()->create(['title' => 'My Created Post']);*/

//$user->posts()->delete();

/*    $post = $user->posts()->where('title', '=', 'malte first post')->update(['title' => 'malte first updated post']);
    var_dump($post);*/

/*    $posts = $user->posts()->get();
    foreach($posts as $post) {
        echo $post->title . '<br>';
    }*/

/*    $user = User::find(1);
    $posts = $user->posts();
    var_dump($posts->toSql());*/


/*    $user = User::find(1);
    $posts = $user->posts()->where('id', '=', 3)->first();
    var_dump($posts);*/

// Save method update user
/*    $user = User::first();
    $user->name = 'Malte';
    $user->save();*/

/*    $user = User::find(17);
    $user->name = 'alexander';
    $user->save();*/

// Save method new user
/*    $user = new User();
    $user->name = 'Helge';
    $user->age = 65;
    $user->test = 'hello';
    $user->save();*/

/*    $user =  User::find(5)->updateSingle(['age' => 2]);
    var_dump($user);*/

//User::find(5)->remove();

//User::update(['age' => 30]);

//User::where('name', '=', 'Mats')->orWhere('id', '=', 2)->update(['age' => 30]);

//User::where('id', '=', 7)->delete();

/*    $user = User::select()->where('name', '=', 'Mats')->first();
    echo '<pre>';
    var_dump($user);
    echo '</pre>';
    exit();*/

/*        $data = [
        ['name' => 'Mats', 'age' => 55],
        ['name' => 'Hugo', 'age' => 9],
        ['name' => 'Wilma', 'age' => 22],
        ['name' => 'Moa', 'age' => 25],
        ['name' => 'Lou', 'age' => 2],
        ['name' => 'Simon', 'age' => 30],
        ['name' => 'Martin', 'age' => 24],
    ];
    $queryBuilder= new \Core\Database\CommandBuilder();
    echo $builder->table('users')->insert($data);*/


//$user = User::select()->where('name', '=', 'Mats')->first();

//var_dump($user);

//var_dump(User::create(['name' => 'alexandra', 'age' => 100]));

//var_dump(User::where('id', '>', 2)->first());

//var_dump(User::first());

//echo User::where('name', '=', 'Mats')->orWhere('name', '=', 'malle')->avg('age');

//    $user = User::select(['name', 'age'])->where('age', '=', 55)->orWhere('name', '=', 'kalle')->get();
//    var_dump($user);


//var_dump($builder->table('users')->where('name', '=', 'Malte')->update(['name' => 'Mats']));

//echo $builder->table('users')->where('id', '=', 29)->delete();

//    $data = [
//        ['name' => 'Helmut', 'age' => 2],
//        ['name' => 'Forza', 'age' => 9],
//    ];
//    echo $builder->table('users')->insert($data);

//    $users = $builder->table('users')->get(User::class);
//    var_dump($users);
//    $avg = $builder->table('users')->avg('age');
//    echo 'AVG: ' . $avg . '<br>';
//    $min = $builder->table('users')->min('age');
//    echo 'MIN: ' . $min . '<br>';
//    $max = $builder->table('users')->max('age');
//    echo 'MAX: ' . $max . '<br>';
//    $sum = $builder->table('users')->sum('age');
//    echo 'SUM: ' . $sum . '<br>';
//    $count = $builder->table('users')->count('age');
//    echo 'COUNT: ' . $count . '<br>';

//$users = $builder->table('users')->get();
//
//var_dump($users);

//    $query = $builder->table('users')
//        ->join('posts', 'users.id', '=', 'posts.user_id')
//        ->whereIn('name', ['mats', 'hugo'])->get();
//    var_dump($query, 'shit');


//    $query = $builder->table('users')->whereIn('name', ['mats',  'matte'])->orWhereIn('age', [20, 30, 40])->orWhere(function($builder) {
//        $builder->where('age', '>', 20)->orWhereIn('name', ['hugo', 'malle']);
//    })->get();
//    var_dump($query);


//    $query = $builder->table('users')->where('age', '<', 100)->orWhere('age', '>', 20)->where(function($builder) {
//        $builder->where('name', '=', 'alex')->orWhere('name', '=', 'alex')->getQueryString();
//    })->get();
//    var_dump($query);

//$query = $builder->orderBy('id')->table('users')->get();

//    $query = $builder->table('users')->where('name', '=', 'Mats')->where(function($builder) {
//        $builder->where('age', '>', 20)->where('age', '<', 100);
//    })->get();

} catch (Exception $exception) {
    echo $exception->getMessage() . ' <pre>' . $exception->getTraceAsString() . '</pre>';
} catch (Error $error) {
    echo $error->getMessage() . ' <pre>' . $error->getTraceAsString() . '</pre>';
}