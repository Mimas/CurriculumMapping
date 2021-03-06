@extends('layouts.standard')
@section('content')
<div class="units-row top44">
    <div class="unit-100 unit-centered">
        @if(Session::has('success'))
        <div class="error row-fluid">
        </div>
        @endif
       <h3>Users</h3>
      <table class="width-100 table-hovered table-stripped">
          <thead>
            <tr>
              <th>Email address</th>
              <th>First name</th>
              <th>Last name</th>
              <th>Status</th>
              <th>Last login</th>
              <th>Actions</th>
            </tr>
          </thead>
        <?php foreach ($users as $user): ?>
          <tr>
          <td>{{$user->email}}</td>
          <td>{{$user->first_name}}</td>
          <td>{{$user->last_name}}</td>
          <td>
            {{Form::open(array('url' => 'admin/toggle/'.$user->id, 'method' => 'PUT')); }}
             <button class="btn btn-smaller {{$user->activated == 1 ? 'btn-active' : '' }}">{{$user->activated == 1 ? 'Active' : 'Inactive' }}</button>
            {{Form::close();}}
          </td>
          <td>{{$user->last_login}}</td>
          <td>
            {{Form::open(array('url' => 'admin/user/'.$user->id, 'method' => 'delete')); }}
            <button class="btn btn-smaller btn-red">Delete&nbsp;<i class="fa fa-trash-o"></i></button>
            {{Form::close();}}
            <a href="/admin/user/{{$user->id}}/edit/" class="breathe-left btn btn-smaller btn-blue">Edit&nbsp;<i class="fa fa-cog"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="5">
            <?php echo $users->links(); ?>
          </td>
          <td class="text-right">
            <a href="/admin/user/0/edit/" class="btn btn-smaller btn-blue">Add User&nbsp;<i class="fa fa-plus"></i></a>

          </td>
        </tr>

        </table>
    </div>
</div>
@stop
