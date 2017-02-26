@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">

      <div class="col-sm-8">
        <div class="panel panel-default">
          <div class="panel-heading">People shared bookmarks</div>
          <div class="panel-body bookmark-panel">

            @foreach($days as $day => $bookmarks)

              <h3>{{$day}}</h3>

              @foreach($bookmarks as $bookmark)

                <p><a href="{{$bookmark->url}}" target="_blank"
                      title="{{$bookmark->url}}">{{ $bookmark->title ? $bookmark->title : $bookmark->url }}</a>

                  {!! Form::filter($bookmark->user, 'name', $filters, 'a', 'label label-success') !!}
                  {!! Form::filter($bookmark->category, 'title', $filters, 'c', 'label label-default') !!}

                  @foreach($bookmark->tags as $tag)
                    {!! Form::filter($tag, 'tag', $filters, 't') !!}
                  @endforeach
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

      <div class="col-sm-4">

        <div class="panel panel-default">
          <div class="panel-heading">Authors</div>
          <div class="panel-body">
            @foreach($authors as $author)
              {!! Form::filter($author, 'name', $filters, 'a', 'label label-success') !!}
            @endforeach
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">Categories</div>
          <div class="panel-body">
            @foreach($categories as $category)
              {!! Form::filter($category, 'title', $filters, 'c', 'label label-default') !!}
            @endforeach
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">Tags</div>
          <div class="panel-body">
            @foreach($tags as $tag)
              {!! Form::filter($tag, 'tag', $filters, 't') !!}
            @endforeach
          </div>
        </div>

      </div>

    </div>
  </div>
@endsection
