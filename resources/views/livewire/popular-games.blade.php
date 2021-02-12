<div wire:init="loadGames">
    <h2 class="text-blue-500 uppercase tracking-wide font-semibold">
        Popular Games
    </h2>
    <div class="popular-games text-sm grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 xl:grid-cols-6 gap-12 border-b border-gray-800 pb-16">
        @forelse ($this->games as $game)
        <div class="game mt-8">
            <div class="relative inline-block">
                <a href="{{route('games.show', $game->slug)}}">
                    <img src="{{$game->cover}}"
                        alt="{{$game->name}}"
                        class="hover:opacity-75 transition ease-in-out duration-150"
                    >
                </a>
                <div class="absolute bottom-0 right-0 w-16 h-16 bg-gray-800 rounded-full"
                    style="right:-20px; bottom:-20px;">
                    <div class="font-semibold text-xs flex justify-center items-center h-full">
                        {{$game->totalRating}}%
                    </div>
                </div>
            </div>
            <a href="{{route('games.show', $game->slug)}}"
                class="block text-base font-semibold leading-tight hover:text-gray-400 mt-8">
                {{$game->name}}
            </a>
            <div class="text-gray-400 mt-1">{{implode(', ', $game->platforms)}}</div>
        </div>
        @empty
        @foreach (range(1, 12) as $placeholder)
        <div class="game mt-8 animate-pulse">
            <div class="relative inline-block">
               <div class="bg-gray-800 w-64 lg:w-44 h-80 lg:h-56"></div>
            </div>
            <div>
                <div class="bg-gray-700 rounded mt-8 w-36">
                    &nbsp;
                </div>
            </div>
            <div>
                <div class="bg-gray-700 rounded mt-3 w-24">
                    &nbsp;
                </div>
            </div>
        </div>
        @endforeach
        @endforelse
    </div>
</div>
