<?php

namespace App\Http\Livewire;

use App\DTO\MostAnticipatedGame;
use App\Services\Http\IGDB\Client;
use App\Services\Http\IGDB\Exceptions\BuilderException;
use App\Services\Http\IGDB\Exceptions\IGDBException;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class MostAnticipated extends Component
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
        return view('livewire.most-anticipated');
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
        // simulates a most anticipated game by querying games that were recently
        // released, with rating, ordering by first_release_date
        $response = $client
            ->query()
            ->select('name', 'cover.url', 'first_release_date')
            ->where('platforms.abbreviation', ['PS4', 'PC', 'XONE', 'Series X', 'PS5', 'Switch'])
            ->where('total_rating_count', '>', 0)
            ->where('first_release_date', '>=', strtotime('-3 months'))
            ->where('first_release_date', '<=', strtotime('today'))
            ->sortBy('first_release_date', 'desc')
            ->limit(4)
            ->execute('games');

        $this->games = MostAnticipatedGame::fromApiResponse($response);
    }
}
