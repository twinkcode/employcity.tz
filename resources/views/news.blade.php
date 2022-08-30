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
                            <p class="card-text mt-4">{{ collect($new->texts)->implode(' ') }}</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card-footer mt-3">
                            <div class="card-text d-flex justify-content-between align-items-center">
                                <small class="text-muted">{{\Carbon\Carbon::createFromTimestamp($new->publish_date)->toDateTimeString()}}</small>
                                <a href="/" class="btn btn-primary btn-sm">к списку новостей</a>
                                <a href="{{$new->link}}" class="btn btn-secondary btn-sm" target="_blank">смотреть на доноре</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
