@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-3 w-100" style="max-width: 100%;">
                <div class="row g-0">
                    <div class="col-md-4">
                        @if($new->img)
                            <img src="{{$new->img}}" class="img-fluid rounded-start" alt="{{$new->title}}">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{$new->title}}</h5>
                            <span class="text-muted h6">{{$new->subtitle}}</span>
                            <p class="card-text">{{ collect($new->texts)->implode(' ') }}</p>
                            <p class="card-text d-flex justify-content-between align-items-end">
                                <small class="text-muted">{{\Carbon\Carbon::createFromTimestamp($new->publish_date)->toDateTimeString()}}</small>
                                <a href="/" class="btn btn-primary btn-sm" target="_blank">к списку новостей</a></p>
                                <a href="{{$new->link}}" class="btn btn-primary btn-sm" target="_blank">смотреть на доноре</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
