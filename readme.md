# Typhoon Cache

![Salibhdr|typhoon](https://drive.google.com/a/domain.com/thumbnail?id=12yntFCiYIGJzI9FMUaF9cRtXKb0rXh9X)

[![Total Downloads](https://poser.pugx.org/SaliBhdr/typhoon-cache/downloads)](https://packagist.org/packages/SaliBhdr/typhoon-cache)
[![Latest Stable Version](https://poser.pugx.org/SaliBhdr/typhoon-cache/v/stable)](https://packagist.org/packages/SaliBhdr/typhoon-cache)
[![Latest Unstable Version](https://poser.pugx.org/SaliBhdr/typhoon-cache/v/unstable)](https://packagist.org/packages/SaliBhdr/typhoon-cache)
[![License](https://poser.pugx.org/SaliBhdr/typhoon-cache/license)](https://packagist.org/packages/SaliBhdr/typhoon-cache)

## Introduction


Typhoon Cache is a Laravel package that automatically adds and retrieves cache data with just a few settings.
Typhoon Cache uses your default cache storage to store cache data. Redis and Memcache are suggested.

Available cache methods in this package:

  1) **Eloquent Model cache** : Caches model data
  2) **Route cache** : Caches routes with response data

## Installation

#### Install with Composer
```php
 $ composer require salibhdr/typhoon-cache
```

## Getting started

##### Laravel

----

Configuration File (required)

After installing the Typhoon Cache library, register the SaliBhdr\TyphoonCache\ServiceProviders\TyphoonCacheServiceProvider::class in your config/app.php configuration file:
```php
'providers' => [

     // Other service providers...
     
     SaliBhdr\TyphoonCache\ServiceProviders\TyphCacheServiceProvider::class,
],
```

Copy the package config to your local config with the publish command:
```php
php artisan vendor:publish --provider="SaliBhdr\TyphoonCache\ServiceProviders\TyphoonCacheServiceProvider"
```

##### Lumen

----

Register The Service Provider In bootstrap/app.php:
```php
$app->register(SaliBhdr\TyphoonCache\ServiceProviders\TyphCacheServiceProvider::class);
```
Copy the config file `typhoon-cache.php` manually from the directory `/vendor/salibhdr/typhoon-cache/config` to the directory `/config`  (you may need to create this directory).

Register the config file in `bootstrap/app.php`:

```php
$app->configure('typhoon-cache')
```

## Configuration

##### Config file example:

```php
// typhoon-cache.php :

return [
    'cache-method' => \SaliBhdr\TyphoonCache\TyphCache::dispatcherEventMethod, // dispatcher,observer (change it to observer if you have another observer trait like laravel scout)
    'default-cache-ttl' => 60,// Defaults to 1 hour. in minutes || if (null)->default or (-1)->forever
    'is_cache_active' => true,
    'models' => [
        //model namespace
        App\Book::class => [
            'cache_key' => 'book', //if (null)-> sets model class name
            'cache-ttl' => 60, // Defaults to 1 hour. in minutes || if (null)->default or (-1)->forever
            'is_cache_active' => true, // true,false
            'is_based_on_user' => true, //true,false
            'cache-on' => [
                'retrieved' => false,
                'created' => false,
                'updated' => false,
                'saved' => true,
                'restored' => false,
            ],
            'delete-on' => [
                'deleted' => true,
            ],
        ],
    ],

    'routes' => [
        'api/v1/books' => [
            'is_cache_active' => true, // true,false
            'cache-ttl' => 60, // Defaults to 1 hour. in minutes || if (null)->default or (-1)->forever
            'is_based_on_user' => false, //true,false
            'prefix' => ''
        ],
    ]
];
```
----

##### Config file explanation:

Typhoon cache config options:

1) **cache-method** (string): It has 2 options 
   1) dispatcher : Uses model $dispatcherEvents to handel events. If you have another EventDispatcher events in your model use observer mode
   2) observer: Registers an observer to observe model events. If you have another trait that uses model observers like Laravel scout in the model use dispatcher method
2) **default-cache-ttl** (int): cache ttl in minutes. If no cache ttl specified in model on routes it uses this default ttl
3) **is_cache_active** (bool): activate and deactivate all caches
4) **models** (array): an array of models that going to use cache
   1) Use class namespace as array key ('App\Book' or App\Book::class )
   2) cache_key (string): Cache key that is going to add as key in cache storage, if set to null it uses table name of model
   3) cache-ttl (int) : model cache ttl in minutes
   4) is_cache_active (bool) : active and deactive only the specified model cache
   5) is_based_on_user (bool): If model data differs between users, set this to true.
   6) cache-on (array): array of model events that you want to be cached. List of model events are listed below
   7) delete-on (array) : array of model events that you want the cache to be deleted
5) **routes** (array) : array of routes are going to use cache
   1) Use full url after application base url, and 
   don't put slash before url (right way : api/v1/books), (wrong way : example.com/api/v1/books ), (wrong way : /api/v1/books )
   2) is_cache_active (bool):  active and deactive only the specified route cache
   3) cache-ttl (bool) : route cache ttl in minutes
   4) is_based_on_user (bool) : If route data differs between users, set this to true.
   5) prefix (string) : if you want to add key prefix to route cache key
   
**Tip** : If you set cache ttl to null in model or route it uses default ttl.

**Tip 2** : If you set default cache ttl to null it sets the ttl to default 60.

**Tip 3** : If You set any cache ttl to -1 the cached data is forever until it refresh again
   
## Usage

### 1) Eloquent Model cache:

----

Typhoon cache uses eloquent model events to add, update, delete and retrieve data. 
If you want to get more info about laravel model events click [here][df1].

Available laravel model events :

| Event         | Trigger                                                           |
|---------------| ------------------------------------------------------------------|
| retrieved     | after record was retrieved                                        |
| creating      | when record is creating                                           |
| created       | after record was created                                          |
| updating      | when record is updating                                           |
| updated       | after record was updated                                          |
| saving        | when record is saving                                             |
| saved         | after record was saved -> will store relation as well             |
| deleting      | when record is deleting                                           |
| deleted       | after the record was deleted                                      |
| forceDeleted  | after the record was forceDeleted                                 |
| restoring     | when restoring soft deleted record (if model has soft deletes)    |
| restored      | after soft deleted record is restored (if model has soft deletes) |

From laravel documentation :

> The retrieved event will fire when an existing model is retrieved from the database.
> When a new model is saved for the first time, the creating and created events will fire.
> If a model already existed in the database and the save method is called, the updating / updated events will fire.
> However, in both cases, the saving / saved events will fire.

By using these events typhoon cache is going to cache model data.

First use `CacheableModel` trait in the model that you want to be cached:

```php

    namespace App;

    use Illuminate\Database\Eloquent\Model
    use SaliBhdr\TyphoonCache\CacheableModel;

    class Book extends Model
    {
        use CacheableModel;
    }
 ```

According to config file, add all the model that you want to cache in models array as array key:

 ```php
  // typhoon-cache.php

'models' => [
        //model namespace
        App\Book::class => [...],
        App\Category::class => [...],
        App\User::class => [...],
    ],
```

In the array of every class we have cached-on and delete-on methods. You must add any event that you want to cache happening in cached-on array and if you want to delete cache in any event just add the event in delete-on array. I suggested that you add deleted and forceDeleted in delete-on array only. 

 ```php
  // typhoon-cache.php

 App\Book::class => [
            'cache_key' => 'book',
            'cache-ttl' => 60, 
            'is_cache_active' => true, 
            'is_based_on_user' => true, 
            'cache-on' => [
                'retrieved' => false,
                'created' => false,
                'updated' => false,
                'saved' => true,
                'restored' => false,
            ],
            'delete-on' => [
                'deleted' => true,
            ],
        ],
 ```
 
**Notice :** Try not to use created or updated events with saved event together, 
because when creating or updating a record the saved and saving events is triggered as well and this way 
the data will cached 2 times. Benefits of caching data with saved method is it saves Model relations as well in 
cache storage.

**Notice 2:** There are 2 ways of caching. 
 1) On data creation or updating
    
    Pros of this method : 
    
    - Retrieving data is fast, because no time is wasting on caching while data is retrieved
    - Data will always fresh, because every time you update data the cache will be refresh
    
    Cons of this method :
    
    - Maybe data that will never use is going to cache and occupy cache storage
    
 2) when data retrieved

Method 1 will look like this:

 ```php
 // typhoon-cache.php
 
 App\Book::class => [
            'cache-on' => [
                'retrieved' => true,
            ],
            'delete-on' => [
                'deleted' => true,
            ],
        ],
 ```
 
 Method 2 will look like this:
 
  ```php
 // typhoon-cache.php

  App\Book::class => [
             'cache-on' => [
                 'saved' => true,
             ],
             'delete-on' => [
                 'deleted' => true,
             ],
         ],
  ```

Pros of this method : 
    
   - Updating and creating record will be fast
   - Chance of caching data that never used is low
   
   Cons of this method :
    
   - Data may become old
   - Retrieving data may become slower because of caching process
    
    
But feel free to cache model data any way you like.

If model uses softdeletes don't forget to add forceDeleted event in delete-on array and restored in cache-on array:

  ```php
  // typhoon-cache.php
  
  // If model uses softdelete
  App\Book::class => [
             'cache-on' => [
                 'saved' => true,
                 'resotred' => true
             ],
             'delete-on' => [
                 'forceDeleted' => true,
                 'deleted' => true,
             ],
         ],
  ```
  
  You can specify model config in the model too, this way there is no need to add it in config file :
  
  ```php
  // App\Book.php

    /**
     * gets related cache config for model
     *
     * @return array
     */
    protected function getCacheModelConfig()
    {
        return [
                'cache_key' => 'book', 
                'cache-ttl' => 60, 
                'is_cache_active' => true, 
                'is_based_on_user' => true,
                     'cache-on' => [
                         'saved' => true,
                     ],
                     'delete-on' => [
                         'deleted' => true,
                     ],
                 ];
    }
  ```
  
Typhoon cache caches the whole model object into cache storage but if you want to just
cache some of attributes feel free to specify the cacheable data in toCacheable() method,
But this is optional. Typhoon cache will do all the hard work, all by it self :
```php

// App\Book.php
      /**
       * if you want to customize what you want to cache just override this method
       *
       * @return Model
       */
      public function toCacheable()
      {
      
      // method 1
        return $this;
        
      // method 2
        return $this->toArray(); 
        
      // method 3
          return [
            'title' => $this->title
            'author' => $this->author
          ]; // returns data only with these two attributes
      }
```

You can check if the data you retrieved is cached data or the db data with `isCachedData()` method :

```php

// In controller


      // if you want 1 record
      public function getBook(Request $request) 
      {
       $book = Book::findOrFail($request->get('book_id'));
       
       dd($book->isCachedData()); // returns true if its cached data
      }
      
      //if you want multipul records 
      public function getBooks(Request $request) 
      {
       $books = Book::get();
       
       foreach($books as $book)
          dd($book->isCachedData()); // returns true if its cached data
      }
```

You can retrieve data directly from cache storage with `retrieveModel()` :

 ```php
 
 // In controller
 
 use SaliBhdr\TyphoonCache\Facades\TyphCache
 use App\Book
 
       public function getBook(Request $request) 
       {
       
        // first parametr is the model that you the data is related to.
        // second argument is the records id 
        // third argument (optional) : if is_based_on_user option in config file is set to true
        
          $book = TyphCache::retrieveModel(Book::class,$request->get('book_id'),auth()->id());
        
        
        dd($book->isCachedData()); // returns true if its cached data
       }
        
 ```

### 2) Route cache:

----

This method is only caches data if data is once requested. It must only used in routes that is going to get 
some data and not for updating data or creating. Because its a cache method on http level and the requests will never get into controller.

Specify routes that you want to be cached in routes in config file:

 ```php
 // typhoon-cache.php :
 
 return [
     'routes' => [
         'api/v1/books' => [
             'is_cache_active' => true, 
             'cache-ttl' => 60, 
             'is_based_on_user' => true,
             'prefix' => ''
         ],
     ]
 ];
```

And that's it. The data will be cached and retrieve automatically based on 
setting that you specify in config file.

For more explanations about route config refer to config explanation section above.

## Todos

 - Write Tests
 - Add More efficient model data retrieve
 - Add retrieve cache methods in readme
 
License
----
Typhoon-Cache is released under the MIT License.

Built with ❤ for you.

**Free Software, Hell Yeah!**

Contributing
----
Contributions, useful comments, and feedback are most welcome!

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen. Thanks SO - http://stackoverflow.com/questions/4823468/store-comments-in-markdown-syntax)


   [df1]: <https://laravel.com/docs/5.8/eloquent#events>
