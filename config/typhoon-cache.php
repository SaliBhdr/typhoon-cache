<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 4/13/2019
 * Time: 1:40 PM
 */

return [
    'cache-method' => \SaliBhdr\TyphoonCache\TyphCache::dispatcherEventMethod, // trait,observer (change it to observer if you have another observer trait like laravel scout)
    'default-cache-ttl' => 45,// Defaults to 1 hour. in minutes || if (null)->default or (-1)->forever
    'is_cache_active' => true,
    'models' => [
        //example :model namespace
        //        \App\Model::class => [
        //            'cache_key' => 'model_name', //if (null)-> sets model class name
        //            'cache-ttl' => 60, // Defaults to 1 hour. in minutes || if (null)->default or (-1)->forever
        //            'is_cache_active' => true, // true,false
        //            'is_based_on_user' => true, //true,false
        //
        //            /*
        //            |--------------------------------------------------------------------------
        //            | Available model events
        //            |--------------------------------------------------------------------------
        //            |
        //            | Uncomment any event that you want to dispatch
        //            |
        //            | Note: `cache-on` is for events that trigger cache
        //            |       `delete-on` is for events that trigger cache delete
        //            | Note 2: You can deactive event with true or false too
        //            |
        //            | From the laravel documentation:
        //            |    The retrieved event will fire when an existing model is retrieved from the database.
        //            |    When a new model is saved for the first time, the creating and created events will fire.
        //            |    If a model already existed in the database and the save method is called, the updating / updated events will fire.
        //            |    However, in both cases, the saving / saved events will fire.
        //            |
        //            | Events :
        //            |   retrieved    => true,    //after record retrieved
        //            |   creating     => true,    //when record creating
        //            |   created      => true,    //after record created
        //            |   updating     => true,    //when record creating
        //            |   updated      => true,    //when deleting record
        //            |   saving       => true,    //when record saving
        //            |   saved        => true,    //when record saved -> will store relation as well
        //            |   deleting     => true,    //when deleting record
        //            |   deleted      => true,    //after the record deleted
        //            |   forceDeleted => true,    //after the record forceDeleted
        //            |   restoring    => true,    //when restoring soft deleted record (if model has soft deletes)
        //            |   restored     => true,    //after soft deleted record is restored (if model has soft deletes)
        //            |
        //            */
        //            'cache-on' => [
        //                'retrieved' => false,
        //                'created' => false,
        //                'updated' => false,
        //                'saved' => true,
        //                'restored' => false,
        //            ],
        //            'delete-on' => [
        //                'deleted' => true,
        //            ],
        //        ],
    ],

    'routes' => [
        // example :
        //        'api/v1/home' => [
        //            'is_cache_active' => true, // true,false
        //            'cache-ttl' => -1, // Defaults to 1 hour. in minutes || if (null)->default or (-1)->forever
        //            'is_based_on_user' => true, //true,false
        //            'prefix' => ""
        //        ]   ,

    ]
];