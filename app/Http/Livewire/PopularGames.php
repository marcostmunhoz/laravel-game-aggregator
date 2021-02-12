<?php

namespace App\Http\Livewire;

use App\DTO\PopularGame;
use App\Services\Http\IGDB\Client;
use App\Services\Http\IGDB\Exceptions\BuilderException;
use App\Services\Http\IGDB\Exceptions\IGDBException;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PopularGames extends Component
{
    /**
     * @var array
     */
    public $games = [];

    /**
     * @return View
     *
     * @throws BindingResolutionException
     */
    public function render()
    {
        return view('livewire.popular-games');
    }

    /**
     * @param Client $client
     *
     * @return void
     *
     * @throws IGDBException
     * @throws Exception
     * @throws BuilderException
     */
    public function loadGames(Client $client)
    {
        // simulates a popular game by querying games that were recently
        // released or are to be released soon (3 months past and future),
        // ordering by updated_at
        $response = $client
            ->query()
            ->select('slug', 'name', 'total_rating', 'platforms.abbreviation', 'platforms.name', 'cover.url')
            ->where('platforms.abbreviation', ['PS4', 'PC', 'XONE', 'Series X', 'PS5', 'Switch'])
            ->where('total_rating_count', '>', 0)
            ->where('first_release_date', '>=', strtotime('-3 months'))
            ->where('first_release_date', '<=', strtotime('+3 months'))
            ->sortBy('updated_at', 'desc')
            ->limit(12)
            ->execute('games');

        $this->games = PopularGame::fromApiResponse($response);
    }
}
