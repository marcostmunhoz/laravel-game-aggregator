<?php

namespace App\DTO;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;

class GameDetail
{
    // Website category enum
    const WEBSITE_CATEGORY_OFFICIAL = 1;
    const WEBSITE_CATEGORY_FACEBOOK = 4;
    const WEBSITE_CATEGORY_TWITTER = 5;
    const WEBSITE_CATEGORY_TWITCH = 6;
    const WEBSITE_CATEGORY_INSTAGRAM = 8;
    const WEBSITE_CATEGORY_YOUTUBE = 9;

    /**
     * @var string|null
     */
    public ?string $name;

    /**
     * @var string|null
     */
    public ?string $cover;

    /**
     * @var string[]|null
     */
    public ?array $genres;

    /**
     * @var string|null
     */
    public ?string $publisher;

    /**
     * @var string[]|null
     */
    public ?array $platforms;

    /**
     * @var int|null
     */
    public ?int $memberRating;

    /**
     * @var int|null
     */
    public ?int $criticRating;

    /**
     * @var string|null
     */
    public ?string $websiteUrl;

    /**
     * @var string|null
     */
    public ?string $facebookUrl;

    /**
     * @var string|null
     */
    public ?string $instagramUrl;

    /**
     * @var string|null
     */
    public ?string $twitterUrl;

    /**
     * @var string|null
     */
    public ?string $summary;

    /**
     * @var string|null
     */
    public ?string $videoUrl;

    /**
     * @var GameScreenshot[]|null
     */
    public ?array $screenshots;

    /**
     * @var PopularGame[]|null
     */
    public ?array $similarGames;

    /**
     * Creates a new instance from API response.
     *
     * @param Response $response
     *
     * @return static[]
     */
    public static function fromApiResponse(Response $response)
    {
        $instance = new static();

        $instance->name = $response->json('0.name');
        $instance->cover = self::convertCover($response->json('0.cover.url'));
        $instance->genres = self::formatGenres($response->json('0.genres'));
        $instance->publisher = self::formatPublisher($response->json('0.involved_companies'));
        $instance->platforms = self::formatPlatforms($response->json('0.platforms'));
        $instance->memberRating = self::formatRating($response->json('0.rating'));
        $instance->criticRating = self::formatRating($response->json('0.aggregated_rating'));
        $instance->websiteUrl = self::formatUrl($response->json('0.websites'), self::WEBSITE_CATEGORY_OFFICIAL);
        $instance->facebookUrl = self::formatUrl($response->json('0.websites'), self::WEBSITE_CATEGORY_FACEBOOK);
        $instance->instagramUrl = self::formatUrl($response->json('0.websites'), self::WEBSITE_CATEGORY_INSTAGRAM);
        $instance->twitterUrl = self::formatUrl($response->json('0.websites'), self::WEBSITE_CATEGORY_TWITTER);
        $instance->summary = $response->json('0.summary');
        $instance->videoUrl = self::formatVideo($response->json('0.videos'));
        $instance->screenshots = self::convertScreenshots($response->json('0.screenshots'));
        $instance->similarGames = self::convertSimilarGames($response->json('0.similar_games'));

        return $instance;
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
     * Formats the genre list.
     *
     * @param array|null $genres
     *
     * @return array
     */
    protected static function formatGenres(?array $genres)
    {
        return array_filter(
            array_map(
                function ($genre) {
                    return data_get($genre, 'name');
                },
                $genres ?? []
            )
        );
    }

    /**
     * Formats the game publisher.
     *
     * @param array|null $involvedCompanies
     *
     * @return string|null
     */
    protected static function formatPublisher(?array $involvedCompanies)
    {
        return head(
            array_map(
                function ($company) {
                    return data_get($company, 'company.name');
                },
                array_filter(
                    $involvedCompanies ?? [],
                    // filter only publisher companies
                    function ($company) {
                        return data_get($company, 'publisher');
                    }
                )
            ),
        ) ?: null;
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
     * Formats the website URL.
     *
     * @param array|null $websites
     * @param int        $category
     *
     * @return string|null
     */
    protected static function formatUrl(?array $websites, int $category)
    {
        $websites = array_filter(
            $websites ?? [],
            function ($website) use ($category) {
                return data_get($website, 'category') === $category;
            }
        );

        return data_get(head($websites), 'url');
    }

    /**
     * Formats the video into a YouTube URL.
     *
     * @param array|null $videos
     *
     * @return string|null
     */
    protected static function formatVideo(?array $videos)
    {
        $id = data_get(head($videos ?? []), 'video_id');

        return $id
            ? 'https://youtu.br/'.$id
            : null;
    }

    /**
     * Converts the screenshots into a array of GameScreenshot.
     *
     * @param array|null $screenshots
     *
     * @return GameScreenshot[]
     */
    protected static function convertScreenshots(?array $screenshots)
    {
        return array_filter(
            array_map(
                function ($screenshot) {
                    if ($url = data_get($screenshot, 'url')) {
                        return new GameScreenshot(
                            Str::replaceFirst('thumb', 'screenshot_med', $url),
                            Str::replaceFirst('thumb', 'screenshot_huge', $url)
                        );
                    }

                    return null;
                },
                array_filter($screenshots ?? [])
            )
        );
    }

    /**
     * Converts the similar games into a array of PopularGame.
     *
     * @param array|null $similarGames
     *
     * @return PopularGame[]
     */
    protected static function convertSimilarGames(?array $similarGames)
    {
        return array_map(
            function ($game) {
                $instance = new PopularGame();

                $instance->slug = data_get($game, 'slug');
                $instance->name = data_get($game, 'name');
                $instance->cover = self::convertCover(data_get($game, 'cover.url'));
                $instance->totalRating = self::formatRating(data_get($game, 'total_rating'));
                $instance->platforms = self::formatPlatforms(data_get($game, 'platforms'));

                return $instance;
            },
            array_filter($similarGames ?? [])
        );
    }
}
