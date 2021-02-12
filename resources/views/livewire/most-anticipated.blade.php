<div wire:init="loadGames" class="most-anticipated">
    <h2 class="text-blue-500 uppercase tracking-wide font-semibold">
        Most Anticipated
    </h2>
    <div class="most-anticipated-container space-y-10 mt-8">
        @forelse ($this->games as $game)
        <div class="game flex">
            <a class="w-16 lg:w-3/12" href="{{route('game.show', $game->id)}}">
                <img src="{{$game->cover}}"
                    alt="{{$game->name}}"
                    class="hover:opacity-75 transition ease-in-out duration-150 w-16"
                >
            </a>
            <div class="ml-4 w-full lg:w-9/12">
                <a href="{{route('game.show', $game->id)}}" class="hover:text-gray-300">{{$game->name}}</a>
                <div class="text-gray-400 text-sm mt-1">
                    {{$game->releasedAt->format('M d, Y')}}
                </div>
            </div>
        </div>
        @empty
        @foreach (range(1, 4) as $placeholder)
        <div class="game flex animate-pulse">
            <div class="bg-gray-800 w-16 lg:w-3/12 h-20"></div>
            <div class="ml-4 w-full lg:w-9/12">
                <div class="bg-gray-700 rounded text-sm w-full">
                    &nbsp;
                </div>
                <div class="bg-gray-700 mt-1 rounded text-xs w-24">
                    &nbsp;
                </div>
            </div>
        </div>
        @endforeach
        @endforelse
    </div>
</div>
