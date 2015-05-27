@extends('layouts.standard')
@section('content')

<div class="units-row top44">
    <div class="unit-100 unit-centered">
        @if(Session::has('success'))
        <div class="error row-fluid">
        </div>
        @endif
       <h3>Redis</h3>
        <div class="summary">
        <h4><?php echo (count($data)) ?> key/values</h4>
        </div>
          <table class="width-100 table-hovered">
          <tr>
           <th>Key</th>
           <th class="text-centered">TTL</th>
           <th class="text-centered">Type</th>
           <th class="text-centered">LEN</th>
           <th>&nbsp;</th>
          </tr>
          <?php
          if (is_array($data) && count($data) > 0):
            foreach ($data as $key=>$meta ) {
          ?>
          <tr>
            <td>
              <a class="iframe" href="<?php echo (URL::to('redis/edit')); ?>/<?php echo($key);?>" ><?php echo($key); ?></a>
             </td>
            <td class="text-right">
              <?php echo($meta['ttl']); ?>
            </td>
            <td class="text-right">
              <?php echo($meta['type']); ?>
            </td>
            <td class="r" style="text-align: right;"><?php echo($meta['len']); ?></td>
            <td class="text-right">
              <a class="btn btn-smaller btn-red" href="<?php echo URL::to('redis/delete'); ?>/<?php echo($key);?>">Delete&nbsp;<i class="fa fa-trash-o"></i></a>
              <a class="btn btn-smaller btn-blue iframe" href="<?php echo URL::to('redis/edit'); ?>/<?php echo($key);?>" >&nbsp;&nbsp;Edit&nbsp;<i class="fa fa-edit"></i></a>
            </td>
          </tr>
          <?php
          }
          endif;
          ?>
          <tr>
            <td colspan="5">
              <a href="<?php echo URL::to('redis/edit') ?>/" class="btn btn-small btn-blue iframe">Add new&nbsp;<i class="fa fa-plus"></i></a>

            </td>
          </tr>
          </table>
    </div>
</div>
@stop
