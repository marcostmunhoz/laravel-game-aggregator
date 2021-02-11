<div wire:init="loadGames" class="recently-reviewed w-full lg:w-3/4 mr-0 lg:mr-32">
    <h2 class="text-blue-500 uppercase tracking-wide font-semibold">
        Recently Reviewed
    </h2>
    <div class="recently-reviewed-container space-y-12 mt-8">
        @forelse ($this->games as $game)
        <div class="game bg-gray-800 rounded-lg shadow-md flex px-6 py-6">
            <div class="inline-block">
                <div class="relative">
                    <a href="{{route('game.show', $game->id)}}">
                        <img src="{{$game->cover}}"
                            alt="{{$game->name}}"
                            class="hover:opacity-75 transition ease-in-out duration-150 w-48"
                        >
                    </a>
                    <div class="absolute bottom-0 right-0 w-16 h-16 bg-gray-900 rounded-full"
                        style="right:-20px; bottom:-20px;">
                        <div class="font-semibold text-xs flex justify-center items-center h-full">
                            {{$game->totalRating}}%
                        </div>
                    </div>
                </div>
            </div>
            <div class="ml-12 w-full">
                <a href="{{route('game.show', $game->id)}}"
                    class="block text-lg font-semibold leading-tight hover:text-gray-400">
                    {{$game->name}}
                </a>
                <div class="text-gray-400 mt-1">{{implode(', ', $game->platforms)}}</div>
                <p class="mt-6 text-gray-400 block">
                    {{$game->summary}}
                </p>
            </div>
        </div>
        @empty
        @foreach (range(1, 3) as $game)
        <div class="game bg-gray-800 rounded-lg shadow-md flex px-6 py-6 animate-pulse">
            <div class="inline-block">
                <div class="relative">
                    <div class="bg-gray-700 h-48 w-36 xl:h-52 xl:w-40"></div>
                </div>
            </div>
            <div class="ml-12 w-full">
                <div class="bg-gray-700 rounded w-48">
                    &nbsp;
                </div>
                <div>
                    <div class="bg-gray-700 rounded mt-3 w-36">
                        &nbsp;
                    </div>
                </div>
                <p class="mt-6 bg-gray-700 rounded w-full">
                    &nbsp;
                </p>
                <p class="mt-2 bg-gray-700 rounded w-full">
                    &nbsp;
                </p>
                <p class="mt-2 bg-gray-700 rounded w-full">
                    &nbsp;
                </p>
            </div>
        </div>
        @endforeach
        @endforelse
    </div>
</div>
