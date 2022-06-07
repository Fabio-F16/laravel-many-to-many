@extends('admin.posts.layouts.dashboard')

@section('content')
    <div class="container">
        <h2 class="text-center mb-4">Il tuo post nel dettaglio</h2>
        <a class="btn btn-primary mb-4" href="{{ route('admin.posts.edit', $post->id) }}">Modifica il post</a>
        <a class="btn btn-success mb-4" href="{{ route('admin.posts.index') }}"> <i class="fa-solid fa-arrow-left"></i>
            Torna ai post</a>

        <div class="ff-card">

            <h1>{{ $post->title }}</h1>
            <p> {{ $post->category->name }}</p>
            <img src="{{ asset('storage/' . $post->cover) }}" alt="{{ $post->title }}">
            <p> {{ $post->content }}</p>
            <p>Tag:</p>
            @foreach ($post->tags as $tag)
                <span>#{{ $tag->name }}</span>
            @endforeach


            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="post">
                @csrf
                @method('delete')
                {{-- <input type="submit" value="cancella record"> --}}
                <button class="btn btn-danger" type="submit" onclick="return confirm('Sei sicuro?')">Cancella
                    record</button>
            </form>
        </div>


    </div>
@endsection
