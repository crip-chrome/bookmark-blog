@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">Latest bookmarks</div>
          <div class="panel-body">

            @foreach($days as $day => $bookmarks)

              <h3>{{$day}}</h3>

              @foreach($bookmarks as $bookmark)

                <p><a href="{{$bookmark->url}}" target="_blank"
                      title="{{$bookmark->url}}">{{ $bookmark->title ? $bookmark->title : $bookmark->url }}</a>
                  by {{$bookmark->user->name}}</p>

              @endforeach

            @endforeach

            <br>
            <div class="text-center">
              {{ $paging->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
