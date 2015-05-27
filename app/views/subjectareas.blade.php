@extends('layouts.standard')
@section('content')
<form method="get" action="<?php echo asset('subjectareas') ?>" class="forms">
  <div class="units-row top44">
    <div class="unit-100">
        <div class="input-groups spacer">
          <input type="text" name="q" placeholder="Search" value="{{Input::get('q','')}}" />
          <span class="btn-append">
            <button class="btn">Go</button>
          </span>
      </div>
    </div>
  </div>
  <div class="units-row">
    <div class="unit-60">
      &nbsp;
    </div>
    <div class="unit-40">
      <div class="text-right" style="float: right;">
        <span class="total"><?php echo number_format($total); ?></span> Subject Areas, page {{$page}} of {{ $paginator->getLastPage() }}</span>&nbsp;&nbsp;|&nbsp;&nbsp;
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
  <table class="width-100 table-hovered table-stripped">
    <thead>
    <tr>
      <th>Subject area</th>
      <th>Visible/Invisible</th>
      <th class="text-centered">Actions</th>
    </tr>
    </thead>

    <?php foreach ($data as $subject): ?>
      <tr>
        <td> <a class="iframe" href="<?php echo asset('subject') ?>/{{$subject->id}}">{{ $subject->subject }}</a></td>
        <td>
          {{Form::open(array('url' => 'subject/toggle/'.$subject->id, 'method' => 'PUT')); }}
          <button class="btn btn-smaller {{$subject->activated == 1 ? 'btn-active' : '' }}">{{$subject->activated == 1 ? 'Visible' : 'Invisible' }}</button>
          {{Form::close();}}
        </td>

        <td class="text-right">
          {{Form::open(array('url' => asset('/subject').'/'.$subject->id, 'method' => 'delete')); }}
          <button class="btn btn-smaller btn-red">Delete&nbsp;<i class="fa fa-trash-o"></i></button>
          {{Form::close();}}
          <a href="/subject/{{$subject->id}}" class="breathe-left iframe btn btn-smaller btn-blue">Edit&nbsp;<i class="fa fa-cog"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <td>
        <?php echo $data->links(); ?>
      </td>
      <td colspan="2" class="text-right">
        <a href="/subject/0" class="btn btn-small btn-blue iframe">Add new&nbsp;<i class="fa fa-plus"></i></a>
      </td>
    </tr>
  </table>
</div>
@stop
