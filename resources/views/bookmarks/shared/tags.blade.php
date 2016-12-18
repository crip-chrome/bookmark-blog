@foreach($tags as $tag)
  @if($active != $tag->id)
    <a href="{{route('tag', ['tag_id' => $tag->id])}}" class="label label-info">{{$tag->tag}}</a>
  @else
    <span class="label label-primary">{{$tag->tag}}</span>
  @endif
@endforeach