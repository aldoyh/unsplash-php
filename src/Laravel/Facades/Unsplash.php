<?php

namespace Unsplash\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Unsplash\Photo|null getRandomPhoto(array $options = [])
 * @method static string|null getRandomPhotoUrl(array $options = [], string $urlType = 'regular')
 * @method static \Unsplash\Photo|null findPhoto(string $id)
 *
 * @see \Unsplash\Laravel\UnsplashService
 */
class Unsplash extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'unsplash'; // This matches the alias in the ServiceProvider
    }
}
