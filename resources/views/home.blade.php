@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-start">
        @foreach($news as $new)
        <div class="col-sm-12 col-lg-8">
            <div class="card mb-3 w-100" style="max-width: 100%;">
                <div class="row g-0">
                    <div class="col-md-4 d-none">
                        @if($new->img)
                        <img src="{{$new->img}}" class="img-fluid rounded-start" alt="{{$new->title}}">
                        @endif
                    </div>
                    <div class="col-md-12">
                        <div class="card-body">
                            <h5 class="card-title">{{$new->title}}</h5>
                            <span class="text-muted h6">{{$new->subtitle}}</span>
                            <p class="card-text pt-3">{{\Illuminate\Support\Str::limit(collect($new->texts)->implode(' '),200)}}</p>
                            <p class="card-text d-flex justify-content-between align-items-end">
                                <small class="text-muted">{{\Carbon\Carbon::createFromTimestamp($new->publish_date)->toDateTimeString()}}</small>
                                <a href="/news/{{$new->publish_date}}" class="btn btn-primary btn-sm" target="_self">подробнее</a></p>
{{--                            <p class="card-text"><small class="text-muted">{{$new->toJson(JSON_UNESCAPED_UNICODE)}}</small></p>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
