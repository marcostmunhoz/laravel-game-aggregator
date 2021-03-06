@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <livewire:popular-games />

    <div class="flex flex-col lg:flex-row my-10">
        <livewire:recently-reviewed />

        <div class="lg:w-1/4 mt-12 lg:mt-0 space-y-10">
            <livewire:most-anticipated />

            <livewire:coming-soon />
        </div>
    </div>
</div>
@endsection
