<?php

namespace Unsplash\Laravel;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Unsplash\HttpClient;
use Unsplash\Photo;
use Unsplash\Exception as UnsplashException;

class UnsplashService
{
    protected ?string $applicationId;
    protected string $utmSource;
    protected CacheRepository $cache;
    protected int $cacheDuration; // in seconds
    protected array $defaultRandomPhotoOptions;

    public function __construct(
        ?string $applicationId,
        string $utmSource,
        CacheRepository $cache,
        int $cacheDurationMinutes = 60,
        array $defaultRandomPhotoOptions = []
    ) {
        $this->applicationId = $applicationId;
        $this->utmSource = $utmSource;
        $this->cache = $cache;
        $this->cacheDuration = $cacheDurationMinutes * 60; // Convert minutes to seconds
        $this->defaultRandomPhotoOptions = $defaultRandomPhotoOptions;

        $this->initializeUnsplashClient();
    }

    protected function initializeUnsplashClient()
    {
        if (empty($this->applicationId)) {
            // Optionally throw an exception or log a warning
            // For now, we allow it to proceed, Unsplash SDK might handle public actions
            // but authenticated actions or higher rate limits will fail.
            // Consider throwing \InvalidArgumentException if app ID is strictly required.
        }

        // This is the tricky part due to the static nature of HttpClient::init
        // We are calling it here, hoping it's safe for the lifecycle of the service.
        // If multiple instances of UnsplashService were created with different app IDs
        // in the same request lifecycle, this static call would cause issues.
        // However, it's registered as a singleton, so this should be fine for one app config.
        HttpClient::init([
            'applicationId' => $this->applicationId,
            'utmSource' => $this->utmSource,
            // 'secret' and 'callbackUrl' are not needed for basic random photo fetching
        ]);
    }

    /**
     * Get a random photo from Unsplash.
     *
     * @param array $options Parameters for the random photo API call.
     *                       Example: ['query' => 'nature', 'orientation' => 'landscape']
     * @return \Unsplash\Photo|null The Photo object or null on error.
     */
    public function getRandomPhoto(array $options = []): ?Photo
    {
        if (empty($this->applicationId)) {
            // Log or handle missing application ID case
            // For now, returning null or could throw specific exception.
            // Consider: throw new \RuntimeException("Unsplash Application ID is not configured.");
            return null;
        }

        $options = array_merge($this->defaultRandomPhotoOptions, $options);
        ksort($options); // Ensure consistent cache key for same options regardless of order
        $cacheKey = 'unsplash.random_photo.' . md5(json_encode($options));

        try {
            $photoData = $this->cache->remember($cacheKey, $this->cacheDuration, function () use ($options) {
                // The Photo::random() method from the core library returns a Photo object
                // which is an ArrayObject. We need to convert it to an array for caching,
                // as complex objects might not serialize well with all cache drivers.
                $photoObject = Photo::random($options);
                return $photoObject->toArray(); // Assuming Photo class has a toArray() method
            });

            // Re-hydrate the Photo object from cached array data
            return new Photo($photoData);

        } catch (UnsplashException $e) {
            // Log the exception: app('log')->error('Unsplash API error: ' . $e->getMessage());
            // Depending on desired behavior, could re-throw, return null, or a default image.
            return null;
        } catch (\Exception $e) {
            // Log generic exception
            // app('log')->error('Generic error fetching Unsplash photo: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get a direct URL for a random photo.
     *
     * @param array $options Parameters for the random photo API call.
     * @param string $urlType The type of URL to return (e.g., 'regular', 'small', 'thumb', 'raw', 'full').
     * @return string|null The photo URL or null on error.
     */
    public function getRandomPhotoUrl(array $options = [], string $urlType = 'regular'): ?string
    {
        $photo = $this->getRandomPhoto($options);

        if ($photo && isset($photo->urls[$urlType])) {
            return $photo->urls[$urlType];
        }

        return null;
    }

    /**
     * Find a specific photo by its ID.
     *
     * @param string $id The ID of the photo.
     * @return \Unsplash\Photo|null The Photo object or null if not found or on error.
     */
    public function findPhoto(string $id): ?Photo
    {
        if (empty($this->applicationId)) {
            return null;
        }

        $cacheKey = 'unsplash.photo.' . $id;

        try {
            $photoData = $this->cache->remember($cacheKey, $this->cacheDuration, function () use ($id) {
                $photoObject = Photo::find($id);
                return $photoObject->toArray();
            });

            return new Photo($photoData);

        } catch (UnsplashException $e) {
            // Log error
            return null;
        } catch (\Exception $e) {
            // Log error
            return null;
        }
    }
}
