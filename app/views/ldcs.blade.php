@extends('layouts.standard')
@section('content')
    <form method="get" action="<?php echo asset(Request::path()); ?>" class="forms search">
        <div class="units-row top44">
            <div class="unit-90">
                <div class="input-groups spacer">
                    <input type="text" name="q" placeholder="Search" value="{{Input::get('q','')}}" />
                    <span class="btn-append">
                        <button class="btn">Go</button>
                    </span>
                </div>

            </div> 
        </div>
        <div class="units-row">
            <div class="unit-50">
            <label for="levels">Levels
            <?php
            for ($i=1; $i<$maxDepth; $i++) {
            ?>
              {{$i}} <input class="autosubmit" type="checkbox" name="levels[]" <?php if ( in_array($i, $selectedLevels)) echo ' checked ' ?> value="{{$i}}" />
            <?php
              }
            ?>
            </label>
            </div>
            <div class="unit-40">
              <div class="text-right" style="float: right;">
              <span class="total">{{$total}}</span> Subject Areas, page {{$page}} of {{ $paginator->getLastPage() }}</span>&nbsp;&nbsp;|&nbsp;&nbsp;                
              {{ Form::select('pageSize', \Bentleysoft\Helper::pageSizes(), $pageSize, array('class' => 'autoselect inliner')); }}
              </div>
            </div>
        </div>
    </form>
<div class="unit-100 unit-centered">
  @if(Session::has('success'))
  <div class="error row-fluid">
  </div>
  @endif
  <table class="width-90 table-hovered">
    <?php foreach ($data as $row): ?>
      <tr>
        <td class="width-10"> <a class="iframe" href="<?php echo asset(str_singular(Request::path())); ?>/{{$row->id}}">{{ $row->ldsc_code }}</a></td>
        <td> <a class="iframe" href="<?php echo asset(str_singular(Request::path())); ?>/{{$row->id}}">{{ $row->ldsc_desc }}</a></td>
        <td class="text-right width-30">
          {{Form::open(array('url' => asset('/ldc').'/'.$row->id, 'method' => 'delete')); }}
          <button class="btn btn-smaller btn-red">Delete&nbsp;<i class="fa fa-trash-o"></i></button>
          {{Form::close();}}
          <a href="/ldc/{{$row->id}}" class="breathe-left iframe btn btn-smaller btn-blue">Edit&nbsp;<i class="fa fa-cog"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <td colspan="2">
        <?php echo $paginator->links(); ?>
      </td>
      <td class="text-right">
        <a href="<?php echo asset(str_singular(Request::path())); ?>/0" class="btn btn-small btn-blue iframe">Add new&nbsp;<i class="fa fa-plus"></i></a>
      </td>
    </tr>
  </table>
</div>
@stop
