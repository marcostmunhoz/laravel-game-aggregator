<?php

namespace App\DTO;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class MostAnticipatedGame
{
    /**
     * @var string
     */
    public string $slug;

    /**
     * @var string
     */
    public ?string $name;

    /**
     * @var string|null
     */
    public ?string $cover;

    /**
     * @var Carbon
     */
    public Carbon $releasedAt;

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

            $instance->slug = data_get($game, 'slug');
            $instance->name = data_get($game, 'name');
            $instance->cover = self::convertCover(data_get($game, 'cover.url'));
            $instance->releasedAt = self::convertReleaseDate(data_get($game, 'first_release_date'));

            $instances[] = $instance;
        }

        return $instances;
    }

    /**
     * Converts the release date.
     *
     * @param int $releasedAt
     *
     * @return Carbon
     */
    protected static function convertReleaseDate(int $releasedAt)
    {
        return Carbon::createFromTimestamp($releasedAt);
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
}
