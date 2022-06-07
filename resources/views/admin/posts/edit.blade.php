@extends('admin.posts.layouts.dashboard')

@section('content')
    <div class="container">
        <h2 class="text-center mb-4">Modifica il post!</h2>
        <form action="{{ route('admin.posts.update', $post->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="mb-3">
                <label class="col-4" for="title">Titolo:</label>
                <input class="col-4" type="text" name="title" value="{{ old('title', $post->title) }}">
                @error('title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            {{-- immagine --}}
            <div class="mb-3">
                <div>
                    <label for="image">Cambia immagine</label>
                </div>

                <img src="{{ asset('storage/' . $post->cover) }}" alt="{{ $post->title }}">
                <input type="file" name="image" />
            </div>

            {{-- select one to many category --}}
            <div class="mb-3">
                <label class="col-4" for="description">Categoria:</label>
                <select name="category_id">
                    <option value="">--Scegli categoria--</option>
                    @foreach ($categories as $item)
                        <option value="{{ $item->id }}"
                            {{ $item->id == old('category_id', $post->category_id) ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="col-4" for="description">Contenuto:</label>
                <textarea class="col-4 text-area" name="content">{{ old('content', $post->content) }}</textarea>
                @error('title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- checkbox tags --}}
            <div class="mb-3">
                <label class="col-4" for="description">Tags:</label>
                @foreach ($tags as $tag)
                    @if ($errors->any())
                        <div>
                            <label>{{ $tag->name }}</label>
                            <input type="checkbox" value="{{ $tag->id }}" name="tags[]"
                                {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }} />
                        </div>
                    @else
                        <div>
                            <label>{{ $tag->name }}</label>
                            <input type="checkbox" value="{{ $tag->id }}" name="tags[]"
                                {{ $post->tags->contains($tag) ? 'checked' : '' }} />
                        </div>
                    @endif
                @endforeach
                @error('tags')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>



            <input class="mb-4 btn btn-success" type="submit" value="Modifica">
        </form>

        <a class="btn btn-primary mb-4" href="{{ route('admin.posts.index') }}"> <i class="fa-solid fa-arrow-left"></i>
            Torna ai post</a>
    </div>
@endsection
