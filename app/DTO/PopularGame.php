<?php

namespace App\DTO;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;

class PopularGame
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public ?string $name;

    /**
     * @var string|null
     */
    public ?string $cover;

    /**
     * @var int|null
     */
    public ?int $totalRating;

    /**
     * @var string[]|null
     */
    public ?array $platforms;

    /**
     * Creates a new instance from API response.
     *
     * @param Response $response
     *
     * @return static[]
     */
    public static function fromApiResponse(Response $response)
    {
        $instances = [];

        foreach ($response->json() as $game) {
            $instance = new static();

            $instance->id = data_get($game, 'id');
            $instance->name = data_get($game, 'name');
            $instance->cover = self::convertCover(data_get($game, 'cover.url'));
            $instance->totalRating = self::formatRating(data_get($game, 'total_rating'));
            $instance->platforms = self::formatPlatforms(data_get($game, 'platforms'));

            $instances[] = $instance;
        }

        return $instances;
    }

    /**
     * Converts the thumbnail to big cover.
     *
     * @param string|null $cover
     *
     * @return string|null
     */
    protected static function convertCover(?string $cover)
    {
        if (!$cover) {
            return null;
        }

        return Str::replaceFirst('thumb', 'cover_big', $cover);
    }

    /**
     * Formats the rating.
     *
     * @param float|null $rating
     *
     * @return float|null
     */
    protected static function formatRating(?float $rating)
    {
        if (null === $rating) {
            return null;
        }

        return round($rating);
    }

    /**
     * Formats the platform list.
     *
     * @param array|null $platforms
     *
     * @return array
     */
    protected static function formatPlatforms(?array $platforms)
    {
        if (!$platforms) {
            return [];
        }

        return array_map(
            function ($platform) {
                return data_get($platform, 'abbreviation') ?? data_get($platform, 'name');
            },
            $platforms
        );
    }
}
