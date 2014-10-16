@extends('layouts.standard')
@section('content')
    <form method="get" action="<?php echo asset('qualifications') ?>" class="forms search">
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
              <ul class="blocks-3">
              <?php
              foreach ($qualifiers as $i=>$qualifier) {
                ?>
                <li>{{$qualifier->short_name}} <input class="autosubmit" style="display: inline; "
                    type="checkbox" name="selectedqualifications[]" <?php if ( in_array($qualifier->id, $selectedQualifications)) echo ' checked ' ?>
                    value="{{$qualifier->id}}" />
                </li>
              <?php
                }
              ?>
              </ul>
            </div>
            <div class="unit-40">
              <div class="text-right" style="float: right;">
              <span class="total">{{$total}}</span> Qualifications, page {{$page}} of {{ $paginator->getLastPage() }}</span>&nbsp;&nbsp;|&nbsp;&nbsp;                                
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
  <table class="width-90 table-hovered table-stripped">
    <?php foreach ($data as $row): ?>
      <tr>
        <td class="width-10">
          <a class="iframe" href="<?php echo asset('qualification') ?>/{{$row->id}}">{{ $row->qualifier_short }}</a>
        </td>
        <td>
          {{ $row->level }}
        </td>
        <td class="width-40"> <a class="iframe" href="<?php echo asset('subject') ?>/{{$row->id}}">{{ $row->qualification }}</a></td>
        <td>
          {{Form::open(array('url' => asset('/qualification/toggle').'/'.$row->id, 'method' => 'put')); }}
          <input type="hidden" name="id" value="<?php echo $row->id ?>"/>
          <input type="hidden" name="return_to" value="<?php echo(Request::fullUrl()) ?>" />
          <button class="btn btn-smaller  <?php if ($row->activated) echo 'btn-active' ?> ">
            <?php echo  ($row->activated) ? 'On' : 'Off' ?>
          </button>
          {{Form::close();}}
        </td>

        <td class="text-right">
          {{Form::open(array('url' => asset('/qualification').'/'.$row->id, 'method' => 'delete')); }}
          <button class="btn btn-smaller btn-red">Delete&nbsp;<i class="fa fa-trash-o"></i></button>
          {{Form::close();}}
          <a href="/qualification/{{$row->id}}" class="breathe-left iframe btn btn-smaller btn-blue">Edit&nbsp;<i class="fa fa-cog"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <td colspan="4">
        <?php echo $paginator->links(); ?>
      </td>
      <td class="text-right">
        <a href="/subject/0" class="btn btn-small btn-blue iframe">Add new&nbsp;<i class="fa fa-plus"></i></a>
      </td>
    </tr>
  </table>
</div>
@stop
