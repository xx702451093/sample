@if (count($feed_items))
<ol class="statuses">
  @foreach ($feed_items as $status)
    @include('statuses._status', ['user' => $status->user])
  @endforeach
  {!! $feed_items->render() !!}
</ol>
@else
  目前还没有微博哦~赶紧发布吧！
@endif
