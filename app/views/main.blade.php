@extends('layouts.standard')
@section('content')
    <form method="get" action="/" class="forms">
        <div class="units-row">
            <div class="unit-90">
                <div class="input-groups">
                    <input type="text" name="q" placeholder="Search" value="{{Input::get('q','')}}" />
                    <span class="btn-append">
                        <button class="btn">Go</button>
                    </span>
                </div>

            </div>
        </div>
        <?php
        foreach ($data['hits']['hits'] as $row) {
            ?>
            <div class="units-row">
                <div class="unit-50">
                    <a class="iframe" href="/edit/{{$row['_source']['admin']['uuid']}}">{{ $row['_source']['summary_title'] }}</a>
                </div>
                <div class="unit-10">
                    {{ $row['_source']['admin']['source'] }}
                </div>
                <div class="unit-20">
                    {{ $row['_type'] }}
                </div>
                <div class="unit-20">
                    <a href="/edit/{{$row['_source']['admin']['uuid']}}" class="iframe btn btn-small btn-blue">Edit&nbsp;<i class="fa fa-cog"></i></a>
                </div>
            </div>
        <?php
        }
        ?>

    </form>
@stop
