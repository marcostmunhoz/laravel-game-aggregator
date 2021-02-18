<?php

namespace App\Http\Controllers;

use App\DTO\GameDetail;
use App\Services\Http\IGDB\Client;
use App\Services\Http\IGDB\Exceptions\BuilderException;
use App\Services\Http\IGDB\Exceptions\IGDBException;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\View;

class GameController extends Controller
{
    /**
     * @param string $slug
     * @param Client $client
     *
     * @return View
     *
     * @throws IGDBException
     * @throws Exception
     * @throws BuilderException
     * @throws BindingResolutionException
     */
    public function __invoke(string $slug, Client $client)
    {
        $response = $client
            ->query()
            ->select(
                'name',
                'cover.url',
                'genres.name',
                'rating',
                'aggregated_rating',
                'summary',
                'involved_companies.publisher',
                'involved_companies.company.name',
                'platforms.abbreviation',
                'websites.category',
                'websites.url',
                'videos.video_id',
                'screenshots.url',
                'similar_games.slug',
                'similar_games.name',
                'similar_games.total_rating',
                'similar_games.cover.url',
                'similar_games.platforms.abbreviation'
            )
            ->where('slug', '=', $slug)
            ->limit(1)
            ->execute('games');

        $game = GameDetail::fromApiResponse($response);

        return view(
            'show',
            compact('game')
        );
    }
}
