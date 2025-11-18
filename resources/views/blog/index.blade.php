@extends('layouts.app')
@section('content')
    <h1>Blog Posts</h1>
    <div>
        @foreach($posts as $post)
            <div>
                <h2>{{ $post->title }}</h2>
                <p>By {{ $post->author->name }} | {{ $post->published_at->format('M d, Y') }}</p>
                <p>{{ $post->excerpt }}</p>
            </div>
        @endforeach
    </div>
    {{ $posts->links() }}
@endsection