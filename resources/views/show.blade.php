@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="game-details border-b border-gray-800 pb-12 flex flex-col lg:flex-row">
        <div class="flex-none">
            <img src="images/ff7.jpg" alt="cover">
        </div>
        <div class="lg:ml-12 lg:mr-64">
            <h2 class="font-semibold text-4xl leading-tight mt-1">
                Final Fantasy 7 Remake
            </h2>
            <div class="text-gray-400">
                <span>Adventure, RPG</span>
                &middot;
                <span>Square Enix</span>
                &middot;
                <span>Playstation 4</span>
            </div>

            <div class="flex flex-wrap items-center mt-8">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-gray-800 rounded-full">
                        <div class="font-semibold text-xs flex justify-center items-center h-full">90%</div>
                    </div>
                    <div class="ml-4 text-xs">Member <br> Score</div>
                </div>
                <div class="flex items-center ml-12">
                    <div class="w-16 h-16 bg-gray-800 rounded-full">
                        <div class="font-semibold text-xs flex justify-center items-center h-full">90%</div>
                    </div>
                    <div class="ml-4 text-xs">Critic <br> Score</div>
                </div>
                <div class="flex items-center space-x-4 mt-4 lg:mt-0 lg:ml-12">
                    <div class="w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center">
                        <a href="#" class="hover:text-gray-400 p-0 mb-1">
                            <svg class="icon icon-earth"><use xlink:href="#icon-earth"></use></svg>
                        </a>
                    </div>
                    <div class="w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center">
                        <a href="#" class="hover:text-gray-400 p-0 mb-1">
                            <svg class="icon icon-facebook"><use xlink:href="#icon-facebook"></use></svg>
                        </a>
                    </div>
                    <div class="w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center">
                        <a href="#" class="hover:text-gray-400 p-0 mb-1">
                            <svg class="icon icon-twitter"><use xlink:href="#icon-twitter"></use></svg>
                        </a>
                    </div>
                    <div class="w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center">
                        <a href="#" class="hover:text-gray-400 p-0 mb-1">
                            <svg class="icon icon-instagram"><use xlink:href="#icon-instagram"></use></svg>
                        </a>
                    </div>
                </div>
            </div>

            <p class="mt-12">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste ipsum doloremque quibusdam corporis atque delectus repellat culpa, ab assumenda ullam, et nam, laudantium dicta id veniam ducimus. Earum enim, voluptate tempore debitis perferendis accusantium aliquid nesciunt et consequatur deleniti officiis totam est similique eum voluptatibus, nisi sit temporibus cum, dolores distinctio fugiat possimus quae in impedit? Placeat libero impedit minima?
            </p>

            <div class="mt-12">
                <button class="flex bg-blue-500 text-white font-semibold px-4 py-4 hover:bg-blue-600 rounded transition ease-in-out duration-150">
                    <svg class="icon icon-play w-6 fill-current mt-1"><use xlink:href="#icon-play"></use></svg>
                    <span class="ml-2">Play Trailer</span>
                </button>
            </div>
        </div>
    </div>

    <div class="images-container border-b border-gray-800 pb-12 mt-8">
        <h2 class="text-blue-500 uppercase tracking-wide font-semibold">
            Images
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mt-8">
            <div>
                <a href="#">
                    <img src="images/screenshot1.jpg" alt="screenshot" class="hover:opacity-75 transition ease-in-out duration-150">
                </a>
            </div>
            <div>
                <a href="#">
                    <img src="images/screenshot1.jpg" alt="screenshot" class="hover:opacity-75 transition ease-in-out duration-150">
                </a>
            </div>
            <div>
                <a href="#">
                    <img src="images/screenshot1.jpg" alt="screenshot" class="hover:opacity-75 transition ease-in-out duration-150">
                </a>
            </div>
            <div>
                <a href="#">
                    <img src="images/screenshot1.jpg" alt="screenshot" class="hover:opacity-75 transition ease-in-out duration-150">
                </a>
            </div>
            <div>
                <a href="#">
                    <img src="images/screenshot1.jpg" alt="screenshot" class="hover:opacity-75 transition ease-in-out duration-150">
                </a>
            </div>
            <div>
                <a href="#">
                    <img src="images/screenshot1.jpg" alt="screenshot" class="hover:opacity-75 transition ease-in-out duration-150">
                </a>
            </div>
        </div>
    </div>

    <div class="similar-games-container pb-12 mt-8">
        <h2 class="text-blue-500 uppercase tracking-wide font-semibold">
            Similar Games
        </h2>
        <div class="similar-games text-sm grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 xl:grid-cols-6 gap-12 pb-16">
            <div class="game mt-8">
                <div class="relative inline-block">
                    <a href="#">
                        <img src="images/ff7.jpg"
                            alt="ff7"
                            class="hover:opacity-75 transition ease-in-out duration-150"
                        >
                    </a>
                    <div class="absolute bottom-0 right-0 w-16 h-16 bg-gray-800 rounded-full"
                        style="right:-20px; bottom:-20px;">
                        <div class="font-semibold text-xs flex justify-center items-center h-full">
                            90%
                        </div>
                    </div>
                </div>
                <a href="#"
                    class="block text-base font-semibold leading-tight hover:text-gray-400 mt-8">
                    Final Fantasy 7 Remake
                </a>
                <div class="text-gray-400 mt-1">Playstation 4</div>
            </div><div class="game mt-8">
                <div class="relative inline-block">
                    <a href="#">
                        <img src="images/ff7.jpg"
                            alt="ff7"
                            class="hover:opacity-75 transition ease-in-out duration-150"
                        >
                    </a>
                    <div class="absolute bottom-0 right-0 w-16 h-16 bg-gray-800 rounded-full"
                        style="right:-20px; bottom:-20px;">
                        <div class="font-semibold text-xs flex justify-center items-center h-full">
                            90%
                        </div>
                    </div>
                </div>
                <a href="#"
                    class="block text-base font-semibold leading-tight hover:text-gray-400 mt-8">
                    Final Fantasy 7 Remake
                </a>
                <div class="text-gray-400 mt-1">Playstation 4</div>
            </div><div class="game mt-8">
                <div class="relative inline-block">
                    <a href="#">
                        <img src="images/ff7.jpg"
                            alt="ff7"
                            class="hover:opacity-75 transition ease-in-out duration-150"
                        >
                    </a>
                    <div class="absolute bottom-0 right-0 w-16 h-16 bg-gray-800 rounded-full"
                        style="right:-20px; bottom:-20px;">
                        <div class="font-semibold text-xs flex justify-center items-center h-full">
                            90%
                        </div>
                    </div>
                </div>
                <a href="#"
                    class="block text-base font-semibold leading-tight hover:text-gray-400 mt-8">
                    Final Fantasy 7 Remake
                </a>
                <div class="text-gray-400 mt-1">Playstation 4</div>
            </div><div class="game mt-8">
                <div class="relative inline-block">
                    <a href="#">
                        <img src="images/ff7.jpg"
                            alt="ff7"
                            class="hover:opacity-75 transition ease-in-out duration-150"
                        >
                    </a>
                    <div class="absolute bottom-0 right-0 w-16 h-16 bg-gray-800 rounded-full"
                        style="right:-20px; bottom:-20px;">
                        <div class="font-semibold text-xs flex justify-center items-center h-full">
                            90%
                        </div>
                    </div>
                </div>
                <a href="#"
                    class="block text-base font-semibold leading-tight hover:text-gray-400 mt-8">
                    Final Fantasy 7 Remake
                </a>
                <div class="text-gray-400 mt-1">Playstation 4</div>
            </div><div class="game mt-8">
                <div class="relative inline-block">
                    <a href="#">
                        <img src="images/ff7.jpg"
                            alt="ff7"
                            class="hover:opacity-75 transition ease-in-out duration-150"
                        >
                    </a>
                    <div class="absolute bottom-0 right-0 w-16 h-16 bg-gray-800 rounded-full"
                        style="right:-20px; bottom:-20px;">
                        <div class="font-semibold text-xs flex justify-center items-center h-full">
                            90%
                        </div>
                    </div>
                </div>
                <a href="#"
                    class="block text-base font-semibold leading-tight hover:text-gray-400 mt-8">
                    Final Fantasy 7 Remake
                </a>
                <div class="text-gray-400 mt-1">Playstation 4</div>
            </div><div class="game mt-8">
                <div class="relative inline-block">
                    <a href="#">
                        <img src="images/ff7.jpg"
                            alt="ff7"
                            class="hover:opacity-75 transition ease-in-out duration-150"
                        >
                    </a>
                    <div class="absolute bottom-0 right-0 w-16 h-16 bg-gray-800 rounded-full"
                        style="right:-20px; bottom:-20px;">
                        <div class="font-semibold text-xs flex justify-center items-center h-full">
                            90%
                        </div>
                    </div>
                </div>
                <a href="#"
                    class="block text-base font-semibold leading-tight hover:text-gray-400 mt-8">
                    Final Fantasy 7 Remake
                </a>
                <div class="text-gray-400 mt-1">Playstation 4</div>
            </div>
        </div>
    </div>
</div>
@endsection
