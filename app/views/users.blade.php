@extends('layouts.standard')
@section('content')
<style type="text/css">
form {
  display: inline;
}
</style>
<div class="units-row">
    <div class="unit-66 unit-centered">
        @if(Session::has('success'))
        <div class="error row-fluid">
        </div>
        @endif
       <h1>Users</h1>
      <table class="width-100 table-hovered">
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
             <button class="btn {{$user->activated == 1 ? 'btn-active' : '' }}">{{$user->activated == 1 ? 'Active' : 'Inactive' }}</button>
            {{Form::close();}}
          </td>
          <td>{{$user->last_login}}</td>
          <td>
            <a href="/admin/user/{{$user->id}}/edit/" class="btn btn-black"><i class="halflings cog"></i>&nbsp;Edit</a>

            {{Form::open(array('url' => 'admin/user/'.$user->id, 'method' => 'delete')); }}
            <button class="btn btn-red"><i class="halflings remove"></i>&nbsp;Delete</button>
            {{Form::close();}}
          </td>
        </tr>
        <?php endforeach; ?>
        </table>
      <?php echo $users->links(); ?>
      <a href="/admin/user/0/edit/" class="btn">Add user</a>
    </div>
</div>
@stop
