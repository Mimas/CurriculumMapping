@extends('layouts.standard')
@section('content')
    <form method="get" action="<?php echo asset('qualifications') ?>" class="forms search">
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
            <div class="unit-70">
            <label for="levels">Qualifications<br/>
              <uL>
            <?php
            foreach ($qualifiers as $i=>$qualifier) {
              ?>
              <li>{{$qualifier->label}} <input class="autosubmit" style="display: inline; "
                  type="checkbox" name="qualifications[]" <?php if ( in_array($i, $selectedQualifications)) echo ' checked ' ?> 
                  value="{{$qualifier->id}}" />
              </li>
            <?php
              }
            ?>
            </label>

            </div>
            <div class="unit-30">
              <div class="text-right" style="float: right;">
              {{ Form::select('pageSize', \Bentleysoft\Helper::pageSizes(), $pageSize, array('class' => 'autoselect')); }}
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
        <td class="width-30">
          <a class="iframe" href="<?php echo asset('qualification') ?>/{{$row->id}}">{{ $row->qualifier }}</a>
        </td>
        <td width="40"> <a class="iframe" href="<?php echo asset('subject') ?>/{{$row->id}}">{{ $row->qualification }}</a></td>
        <td class="text-right">
          {{Form::open(array('url' => asset('/subject').'/'.$row->id, 'method' => 'delete')); }}
          <button class="btn btn-smaller btn-red">Delete&nbsp;<i class="fa fa-trash-o"></i></button>
          {{Form::close();}}
          <a href="/subject/{{$row->id}}" class="breathe-left iframe btn btn-smaller btn-blue">Edit&nbsp;<i class="fa fa-cog"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <td colspan="2">
        <?php echo $data->links(); ?>
      </td>
      <td class="text-right">
        <a href="/subject/0" class="btn btn-small btn-blue iframe">Add new&nbsp;<i class="fa fa-plus"></i></a>
      </td>
    </tr>
  </table>
</div>
@stop
