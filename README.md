# Unsplash PHP Client for Laravel

[![Build Status](https://travis-ci.org/unsplash/unsplash-php.svg?branch=master)](https://travis-ci.org/unsplash/unsplash-php)
[![Latest Stable Version](https://poser.pugx.org/unsplash/unsplash/v/stable)](https://packagist.org/packages/unsplash/unsplash)
[![Total Downloads](https://poser.pugx.org/unsplash/unsplash/downloads)](https://packagist.org/packages/unsplash/unsplash)
[![License](https://poser.pugx.org/unsplash/unsplash/license)](https://packagist.org/packages/unsplash/unsplash)

This package provides a simple and eloquent way to interact with the [Unsplash API](https://unsplash.com/documentation) within your Laravel application. It builds upon the official `unsplash/unsplash` PHP client, offering Laravel-specific features like auto-configuration, a Facade, and caching.

- [Unsplash API Official Documentation](https://unsplash.com/documentation)
- [Unsplash API Guidelines](https://help.unsplash.com/en/articles/2511245-unsplash-api-guidelines) (Please read and adhere to these!)
- [Original PHP Client Changelog](https://github.com/unsplash/unsplash-php/blob/master/CHANGELOG.md)

**Important:** Every application using the Unsplash API must abide by the [API Guidelines](https://help.unsplash.com/en/articles/2511245-unsplash-api-guidelines). This includes:
- Correctly [hotlinking images](https://help.unsplash.com/en/articles/2511253-hotlinking-images).
- [Attributing photographers and Unsplash](https://help.unsplash.com/en/articles/2511243-guideline-attribution).
- [Triggering a download event](https://help.unsplash.com/en/articles/2511258-guideline-triggering-a-download) when a user downloads an image. (This package helps with fetching data; download tracking needs to be implemented based on your app's functionality).

## Key Features

- Seamless integration with Laravel (8.x, 9.x, 10.x, 11.x).
- `Unsplash` Facade for easy, static-like API calls.
- Automatic registration of Service Provider and Facade.
- Configuration through `.env` file and a publishable config file.
- Built-in caching for API responses to improve performance and reduce API rate limit consumption.
- Helper methods for common tasks, like fetching a random photo URL.

## Installation

Install the package via Composer:

```bash
composer require unsplash/unsplash
```

The package will automatically register its service provider and facade.

## Configuration

1.  **Publish the configuration file (optional):**
    If you need to customize the default settings, publish the configuration file using:
    ```bash
    php artisan vendor:publish --provider="Unsplash\Laravel\UnsplashServiceProvider" --tag="config"
    ```
    This will create a `config/unsplash.php` file in your application.

2.  **Set up your Environment Variables:**
    Add the following to your `.env` file:

    ```env
    UNSPLASH_APPLICATION_ID="YOUR_UNSPLASH_ACCESS_KEY"
    UNSPLASH_UTM_SOURCE="Your Laravel App Name"
    UNSPLASH_CACHE_DURATION=60 # Cache duration in minutes (default: 60)
    # UNSPLASH_CACHE_STORE=null # Specify a cache store, or null for default (e.g., 'redis', 'memcached')
    ```

    -   `UNSPLASH_APPLICATION_ID`: **Required.** Your Unsplash Application ID (Access Key). You can obtain one by [registering your application](https://unsplash.com/oauth/applications) on Unsplash.
    -   `UNSPLASH_UTM_SOURCE`: **Required.** The name of your application. This is used in API requests and attribution links as per Unsplash guidelines.
    -   `UNSPLASH_CACHE_DURATION`: The number of minutes API responses should be cached.
    -   `UNSPLASH_CACHE_STORE`: (Optional) Specify a specific Laravel cache store to use. If `null` or not set, your default cache store will be used.

## Basic Usage (Laravel Facade)

The `Unsplash` facade provides a convenient way to access the API.

### Getting a Random Photo

-   **Get a random photo object:**
    ```php
    use Unsplash\Laravel\Facades\Unsplash;

    $photo = Unsplash::getRandomPhoto(['query' => 'nature', 'orientation' => 'landscape']);

    if ($photo) {
        // $photo is an instance of Unsplash\Photo
        echo "Photo ID: " . $photo->id;
        echo "Regular URL: " . $photo->urls['regular'];
        // See "Accessing Photo Data" and "Attribution" below
    }
    ```

-   **Get a random photo URL directly:**
    ```php
    use Unsplash\Laravel\Facades\Unsplash;

    // Get a regular-sized photo URL
    $url = Unsplash::getRandomPhotoUrl(['query' => 'minimalist wallpaper']);
    // <img src="{{ $url }}" alt="Random Minimalist Wallpaper">

    // Get a specific size (e.g., 'small', 'thumb', 'raw', 'full')
    $smallUrl = Unsplash::getRandomPhotoUrl(['query' => 'cats'], 'small');
    ```
    Available URL types generally include `raw`, `full`, `regular`, `small`, `thumb`.

### Getting a Specific Photo by ID

-   **Get a photo object by its ID:**
    ```php
    use Unsplash\Laravel\Facades\Unsplash;

    $photoId = 'some_photo_id'; // Replace with an actual photo ID
    $photo = Unsplash::findPhoto($photoId);

    if ($photo) {
        echo "Photo Description: " . $photo->description;
    }
    ```

### Displaying an Image in Blade

Here's an example of how you might display a random background image on your Laravel welcome page (`resources/views/welcome.blade.php`) or any other Blade view:

```blade
{{-- At the top of your Blade file or in a relevant section --}}
@php
    $unsplashUımSource = config('unsplash.utm_source', 'Your Laravel App');
    $photo = Unsplash::getRandomPhoto([
        'query' => 'beautiful landscape', // Customize your theme
        'orientation' => 'landscape'
    ]);
    $bgUrl = $photo ? $photo->urls['regular'] : 'default_background.jpg'; // Fallback image
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel with Unsplash Background</title>
    <style>
        body {
            background-image: url('{{ $bgUrl }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            color: white; /* Adjust text color for visibility */
            font-family: sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: flex-end; /* Position attribution at the bottom */
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }
        .attribution {
            background-color: rgba(0,0,0,0.5);
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.8em;
        }
        .attribution a {
            color: #eee;
            text-decoration: none;
        }
        .attribution a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    {{-- Your page content here --}}
    <h1>Welcome to My Awesome App</h1>

    @if ($photo)
        <div class="attribution">
            Photo by
            <a href="{{ $photo->user['links']['html'] }}?utm_source={{ urlencode($unsplashUımSource) }}&utm_medium=referral" target="_blank">
                {{ $photo->user['name'] }}
            </a>
            on
            <a href="https://unsplash.com/?utm_source={{ urlencode($unsplashUımSource) }}&utm_medium=referral" target="_blank">
                Unsplash
            </a>
        </div>
    @endif
</body>
</html>
```

### Accessing Photo Data

The `getRandomPhoto()` and `findPhoto()` methods return an `Unsplash\Photo` object (which extends `Unsplash\Endpoint`). You can access its properties directly:

```php
$photo = Unsplash::getRandomPhoto();

if ($photo) {
    $id = $photo->id;
    $description = $photo->description ?? 'A beautiful Unsplash image.';
    $regularUrl = $photo->urls['regular'];
    $smallUrl = $photo->urls['small'];
    $rawUrl = $photo->urls['raw']; // For downloads

    // Photographer details
    $photographerName = $photo->user['name'];
    $photographerUsername = $photo->user['username'];
    $photographerProfileLink = $photo->user['links']['html'];

    // Links object
    $photoHtmlLink = $photo->links['html']; // Link to the photo on Unsplash
    $downloadLocation = $photo->links['download_location']; // Important for triggering downloads

    // For more details on available properties, inspect the $photo object
    // or refer to the Unsplash API documentation for the photo endpoint.
}
```

## Using the Service Directly

If you prefer dependency injection, you can type-hint `Unsplash\Laravel\UnsplashService` in your controllers or other classes:

```php
use Unsplash\Laravel\UnsplashService;
use Illuminate\Http\Request;

class MyController
{
    protected UnsplashService $unsplashService;

    public function __construct(UnsplashService $unsplashService)
    {
        $this->unsplashService = $unsplashService;
    }

    public function showRandomImage(Request $request)
    {
        $photoUrl = $this->unsplashService->getRandomPhotoUrl(['query' => 'technology']);
        return view('my_view', ['imageUrl' => $photoUrl]);
    }
}
```

## Caching

API responses for photo lookups are automatically cached to improve performance and stay within API rate limits.
- The cache duration is controlled by the `UNSPLASH_CACHE_DURATION` environment variable (or `cache_duration` in `config/unsplash.php`), specified in minutes.
- You can specify a cache store using `UNSPLASH_CACHE_STORE` (or `cache_store` in the config). If not set, Laravel's default cache store is used.

## API Guidelines & Attribution

**It is crucial to follow the Unsplash API Guidelines.**

-   **Hotlinking:** Always hotlink images directly from Unsplash URLs (e.g., `images.unsplash.com/...`). This package uses the URLs provided by the API.
-   **Attribution:** You **must** credit the photographer and Unsplash.
    A typical attribution looks like:
    > Photo by <a href="[photographer_profile_url]?utm_source=YOUR_APP_NAME&utm_medium=referral">[Photographer Name]</a> on <a href="https://unsplash.com/?utm_source=YOUR_APP_NAME&utm_medium=referral">Unsplash</a>

    Replace `YOUR_APP_NAME` with your `utmSource` (configured via `UNSPLASH_UTM_SOURCE`).
    The `Unsplash\Photo` object contains the necessary user and links information:
    ```php
    $photo = Unsplash::getRandomPhoto();
    $appName = config('unsplash.utm_source'); // Or your actual app name

    if ($photo) {
        $attributionHtml = sprintf(
            'Photo by <a href="%s?utm_source=%s&utm_medium=referral" target="_blank">%s</a> on <a href="https://unsplash.com/?utm_source=%s&utm_medium=referral" target="_blank">Unsplash</a>',
            $photo->user['links']['html'],
            urlencode($appName),
            $photo->user['name'],
            urlencode($appName)
        );
        // echo $attributionHtml;
    }
    ```
    See the Blade example above for a practical implementation.

-   **Triggering Downloads:** If your application allows users to download images (not just view them), you must [trigger a download event via the API](https://unsplash.com/documentation#track-a-photo-download). The `Photo` object contains a `download_location` URL in its `links` property. You need to make a GET request to this URL when a user initiates a download.
    ```php
    // $photo = Unsplash::findPhoto('some_id');
    // if ($photo && isset($photo->links['download_location'])) {
    //     // When user clicks a download button:
    //     // Make a GET request to $photo->links['download_location']
    //     // Then provide $photo->urls['raw'] or $photo->urls['full'] to the user.
    //     // Note: The Unsplash\Photo class has a download() method that does this.
    //     // $downloadableUrl = $photo->download(); // This hits the download_location endpoint
    // }
    ```
    The `Unsplash\Photo->download()` method from the underlying library handles this:
    ```php
    $photoObject = Unsplash::findPhoto('PHOTO_ID');
    if ($photoObject) {
        $actualDownloadUrl = $photoObject->download(); // This triggers the download endpoint AND returns the actual image URL
        // You can then redirect the user to $actualDownloadUrl or offer it for download.
    }
    ```

## Advanced Usage

This Laravel package primarily simplifies fetching public photo data. For more advanced interactions like searching, managing collections, user-specific actions (likes, uploads), etc., you can still leverage the full power of the underlying `unsplash/unsplash` client.

The `Unsplash\Photo`, `Unsplash\Collection`, `Unsplash\Search`, and `Unsplash\User` classes from the core library are available. The `UnsplashService` initializes `Unsplash\HttpClient` with your credentials, so you can use these classes directly if needed.

Example (Search):
```php
use Unsplash\Search;
// Ensure HttpClient is initialized by UnsplashService (which happens automatically if Facade is used or service is injected)

$results = Search::photos('puppies', 1, 10, 'landscape'); // query, page, per_page, orientation
foreach ($results as $photo) {
    // $photo is an Unsplash\Photo instance
    echo $photo->urls['small'] . "\n";
}
```

### User Authorization (OAuth)

For actions requiring user authentication (e.g., liking a photo on behalf of a user, uploading photos), you'll need to implement the [Unsplash OAuth2 flow](https://unsplash.com/documentation#user-authentication-workflow). This package does not provide out-of-the-box OAuth handling, but the underlying `unsplash/unsplash` client has the necessary tools. Refer to its documentation and the `/examples/oauth-flow.php` in the original library for guidance.

## Contributing

Bug reports and pull requests are welcome on GitHub at https://github.com/unsplash/unsplash-php. This project is intended to be a safe, welcoming space for collaboration, and contributors are expected to adhere to the [Contributor Covenant](http://contributor-covenant.org/) code of conduct.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
