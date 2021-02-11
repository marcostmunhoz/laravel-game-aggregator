<?php

namespace App\DTO;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;

class RecentlyReviewedGame extends PopularGame
{
    /**
     * @var string
     */
    public string $summary;

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
            $instance->summary = self::formatSummary(data_get($game, 'summary'));

            $instances[] = $instance;
        }

        return $instances;
    }

    /**
     * Formats the summary.
     *
     * @param string $summary
     *
     * @return string
     */
    protected static function formatSummary(string $summary)
    {
        return Str::words($summary, 40);
    }
}
