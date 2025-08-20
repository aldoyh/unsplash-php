<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Unsplash Application ID (Access Key)
    |--------------------------------------------------------------------------
    |
    | Your Unsplash Application ID (formerly Access Key). This is required
    | to interact with the Unsplash API. You can get one from:
    | https://unsplash.com/oauth/applications
    |
    */
    'application_id' => env('UNSPLASH_APPLICATION_ID', null),

    /*
    |--------------------------------------------------------------------------
    | UTM Source
    |--------------------------------------------------------------------------
    |
    | As per Unsplash API guidelines, you should identify your application
    | as the source of API requests.
    | https://help.unsplash.com/en/articles/2511245-unsplash-api-guidelines
    |
    */
    'utm_source' => env('UNSPLASH_UTM_SOURCE', 'Laravel Application'),

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how API responses from Unsplash are cached.
    |
    */
    'cache_store' => env('UNSPLASH_CACHE_STORE', null), // null will use default cache store
    'cache_duration' => env('UNSPLASH_CACHE_DURATION', 60), // Cache duration in minutes

    /*
    |--------------------------------------------------------------------------
    | Default Random Photo Parameters
    |--------------------------------------------------------------------------
    |
    | These parameters will be used by default when fetching a random photo
    | if no specific parameters are provided. Refer to Unsplash API docs
    | for available options (e.g., query, orientation, collections, etc.).
    | Example: ['query' => 'nature', 'orientation' => 'landscape']
    |
    */
    'default_random_photo_options' => [
        // 'query' => 'wallpapers',
        // 'orientation' => 'landscape',
    ],

    /*
    |--------------------------------------------------------------------------
    | Local Image Storage (Experimental)
    |--------------------------------------------------------------------------
    |
    | Configuration for saving images to local disk. This is an optional
    | feature and requires filesystem setup.
    |
    */
    // 'enable_local_storage' => env('UNSPLASH_ENABLE_LOCAL_STORAGE', false),
    // 'local_storage_disk' => env('UNSPLASH_LOCAL_STORAGE_DISK', 'public'),
    // 'local_storage_path_prefix' => env('UNSPLASH_LOCAL_STORAGE_PATH_PREFIX', 'unsplash_images'),
    // 'local_image_ttl_days' => env('UNSPLASH_LOCAL_IMAGE_TTL_DAYS', 7), // How long to keep images before they could be cleaned up
];
