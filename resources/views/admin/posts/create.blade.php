@extends('admin.posts.layouts.dashboard')

@section('content')
    <div class="container">
        <h2 class="text-center mb-4">Crea il tuo nuovo post!</h2>
        <form action="{{ route('admin.posts.store') }}" method="post">
            @csrf
            <div class="mb-3">
                <label class="col-4" for="title">Titolo:</label>
                <input class="col-4" type="text" name="title" value="{{ old('title') }}">
                @error('title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            {{-- select one to many category --}}
            <div class="mb-3">
                <label class="col-4" for="description">Categoria:</label>
                <select name="category_id">
                    <option value="">--Scegli categoria--</option>
                    @foreach ($categories as $item)
                        <option value="{{ $item->id }}" {{ $item->id == old('category_id') ? 'selected' : '' }}>
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
                <textarea class="col-4 text-area" name="content">{{ old('content') }}</textarea>
                @error('content')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <input class="mb-4 btn btn-success" type="submit" value="Crea">
        </form>

        <a class="btn btn-primary mb-4" href="{{ route('admin.posts.index') }}"> <i class="fa-solid fa-arrow-left"></i>
            Torna ai post</a>
        {{-- errori --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
