@php
$games = [
    [
        'name' => 'Half Life: Alyx',
        'image' => 'images/alyx.jpg',
        'platform' => 'WhoKnows',
    ],
    [
        'name' => 'Animal Crossing: New Horizons',
        'image' => 'images/animalcrossing.jpg',
        'platform' => 'Nintendo Switch',
    ],
    [
        'name' => 'Marvel Avengers',
        'image' => 'images/avengers.jpg',
        'platform' => 'Playstation 4',
    ],
    [
        'name' => 'Cyberpunk 2077',
        'image' => 'images/cyberpunk.jpg',
        'platform' => 'Playstation 4',
    ],
    [
        'name' => 'Doom Eternal',
        'image' => 'images/doom.jpg',
        'platform' => 'PC',
    ],
    [
        'name' => 'Final Fantasy 7 Remake',
        'image' => 'images/ff7.jpg',
        'platform' => 'Playstation 4',
    ],
    [
        'name' => 'Ghost of Tsushima',
        'image' => 'images/ghost.jpg',
        'platform' => 'Playstation 4',
    ],
    [
        'name' => 'Luigi\'s Mansion 3',
        'image' => 'images/luigi.jpg',
        'platform' => 'Nintendo Switch',
    ],
    [
        'name' => 'Resident Evil 3',
        'image' => 'images/resident.jpg',
        'platform' => 'Playstation 4',
    ],
    [
        'name' => 'The Last of Us Part II',
        'image' => 'images/tlou2.jpg',
        'platform' => 'Playstation 4',
    ],
];
@endphp

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <livewire:popular-games />

    <div class="flex flex-col lg:flex-row my-10">
        <livewire:recently-reviewed />

        <div class="lg:w-1/4 mt-12 lg:mt-0 space-y-10">
            <div class="most-antecipated">
                <h2 class="text-blue-500 uppercase tracking-wide font-semibold">
                    Most Antecipated
                </h2>
                <div class="most-antecipated-container space-y-10 mt-8">
                    @foreach (collect($games)->random(4) as $game)
                    <div class="game flex">
                        <a href="#">
                            <img src="{{$game['image']}}"
                                alt="{{$game['name']}}"
                                class="hover:opacity-75 transition ease-in-out duration-150 w-16"
                            >
                        </a>
                        <div class="ml-4">
                            <a href="#" class="hover:text-gray-300">{{$game['name']}}</a>
                            <div class="text-gray-400 text-sm mt-1">
                                {{date('M d, Y', strtotime('-'.random_int(30, 120).' days'))}}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="coming-soon">
                <h2 class="text-blue-500 uppercase tracking-wide font-semibold">
                    Coming Soon
                </h2>
                <div class="coming-soon-container space-y-10 mt-8">
                    @foreach (collect($games)->random(4) as $game)
                    <div class="game flex">
                        <a href="#">
                            <img src="{{$game['image']}}"
                                alt="{{$game['name']}}"
                                class="hover:opacity-75 transition ease-in-out duration-150 w-16"
                            >
                        </a>
                        <div class="ml-4">
                            <a href="#" class="hover:text-gray-300">{{$game['name']}}</a>
                            <div class="text-gray-400 text-sm mt-1">
                                {{date('M d, Y', strtotime('+'.random_int(30, 120).' days'))}}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
