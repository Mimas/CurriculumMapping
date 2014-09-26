@extends('layouts.standard')
@section('content')
    <form method="get" action="<?php echo asset('subjectareas') ?>" class="forms">
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
    </form>

<div class="unit-100 unit-centered">
  @if(Session::has('success'))
  <div class="error row-fluid">
  </div>
  @endif
  <table class="width-90 table-hovered">
    <?php foreach ($data as $row): ?>
      <tr>
        <td> <a class="iframe" href="<?php echo asset('subject') ?>/{{$row->id}}">{{ $row->area }}</a></td>
        <td class="text-right">
          {{Form::open(array('url' => asset('/subject').'/'.$row->id, 'method' => 'delete')); }}
          <button class="btn btn-smaller btn-red">Delete&nbsp;<i class="fa fa-trash-o"></i></button>
          {{Form::close();}}
          <a href="/subject/{{$row->id}}" class="breathe-left iframe btn btn-smaller btn-blue">Edit&nbsp;<i class="fa fa-cog"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <td>
        <?php echo $data->links(); ?>
      </td>
      <td class="text-right">
        <a href="/subject/0" class="btn btn-small btn-blue iframe">Add new&nbsp;<i class="fa fa-plus"></i></a>
      </td>
    </tr>
  </table>
</div>
@stop
