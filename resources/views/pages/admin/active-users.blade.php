@extends('layouts.app')

@section('template_title')
    {{ trans('titles.activeUsers') }}
@endsection

@section('content')

    <users-count :registered={{-- $users --}} ></users-count>
    <template>
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            Current Users Online
                        </div>
                        <div class="card-body">
                            <canvas id="myChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

@endsection
