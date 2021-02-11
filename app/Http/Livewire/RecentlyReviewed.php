<?php

namespace App\Http\Livewire;

use App\DTO\RecentlyReviewedGame;
use App\Services\Http\IGDB\Client;
use App\Services\Http\IGDB\Exceptions\BuilderException;
use App\Services\Http\IGDB\Exceptions\IGDBException;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class RecentlyReviewed extends Component
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
        return view('livewire.recently-reviewed');
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
        // simulates a recently reviewed game by querying games that
        // were already released (first_release_date <= today), ordering
        // by the total_rating_count
        $response = $client
            ->query()
            ->select('name', 'total_rating', 'summary', 'status', 'platforms.abbreviation', 'platforms.name', 'cover.url')
            ->where('platforms.abbreviation', ['PS4', 'PC', 'XONE', 'Series X', 'PS5', 'Switch'])
            ->where('total_rating_count', '>', 0)
            ->where('first_release_date', '>=', strtotime('-3 months'))
            ->where('first_release_date', '<=', strtotime('today'))
            ->sortBy('total_rating_count', 'desc')
            ->limit(3)
            ->execute('games');

        $this->games = RecentlyReviewedGame::fromApiResponse($response);
    }
}
