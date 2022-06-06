@extends('admin.posts.layouts.dashboard')

@section('content')
    <div class="container">
        <h2 class="text-center mb-4">La tua lista dei post</h2>
        <a class="btn btn-primary mb-4" href="{{ route('admin.posts.create') }}">Crea un nuovo post</a>

        @foreach ($posts as $post)
            <div class="ff-card">
                <h1>{{ $post->title }}</h1>
                <p> Categoria: {{ $post->category->name }}</p>
                <p> {{ $post->content }}</p>

                {{-- <a href="{{ route('admin.posts.edit', $post->id) }}">Modifica</a> --}}
                <a class="btn btn-warning mb-4" href="{{ route('admin.posts.edit', $post->id) }}">Modifica</a>
                <a class="btn btn-info mb-4" href="{{ route('admin.posts.show', $post->id) }}">Vedi nel dettaglio</a>

                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="post">
                    @csrf
                    @method('delete')
                    {{-- <input type="submit" value="cancella record"> --}}
                    <button class="btn btn-danger" type="submit" onclick="return confirm('Sei sicuro?')">Cancella
                        record</button>
                </form>
            </div>
        @endforeach

    </div>
@endsection
