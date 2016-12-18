@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="panel panel-default">
          @if($related_to)
            <div class="panel-heading">Bookmarks related to '{{$related_to}}'</div>
          @else
            <div class="panel-heading">Latest bookmarks</div>
          @endif
          <div class="panel-body">

            @foreach($days as $day => $bookmarks)

              <h3>{{$day}}</h3>

              @foreach($bookmarks as $bookmark)

                <p><a href="{{$bookmark->url}}" target="_blank"
                      title="{{$bookmark->url}}">{{ $bookmark->title ? $bookmark->title : $bookmark->url }}</a>
                  by <a href="{{route('author', ['author_id' => $bookmark->user->id])}}"
                        title="{{$bookmark->user->name}}">{{$bookmark->user->name}}</a><br>
                  @include('bookmarks.shared.tags', ['tags' => $bookmark->tags, 'active' => $tag_id])
                </p>

              @endforeach

            @endforeach

            <br>
            <div class="text-center">
              {{ $paging->links() }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">Tags</div>
          <div class="panel-body">
            @include('bookmarks.shared.tags', ['tags' => $tags, 'active' => $tag_id])
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
