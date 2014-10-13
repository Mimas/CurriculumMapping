@extends('layouts.standard')
@section('content')
<style type="text/css">
  .pagination {
    text-align: center !important;
    position: relative !important;
    left: 20px;
  }
</style>
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
            <div class="unit-60">
              <ul class="blocks-3">
                <?php
                 foreach ($subjectAreas as $subjectArea) {
                  ?>
                  <li>{{$subjectArea->subject}} <input class="autosubmit" type="checkbox" name="areas[]" <?php if ( in_array($subjectArea->id, [0])) echo ' checked ' ?> value="{{$subjectArea->id}}" /></li>
                <?php
                }
                ?>
              </ul>
            </div>
            <div class="unit-30">
              <div class="text-right pager" style="float: right;">
              <span class="total">{{$total}}</span> Resources, page {{$page}} of {{ $resources->getLastPage() }}</span>&nbsp;&nbsp;|&nbsp;&nbsp;
              {{ Form::select('pageSize', \Bentleysoft\Helper::pageSizes(), $pageSize, array('class' => 'autoselect inliner')); }}
              </div>
            </div>
        </div>
    </form>
    <div style="margin-top: -26px;" class="units-row">
      <div class="unit-90">
        <table class="table-hovered table-stripped">
        <?php
        foreach ($data['hits']['hits'] as $row) {
        ?>
          <tr>
            <td class="width-50"><a href="/view/<?php echo $row['_source']['admin']['uid']; ?>">{{ $row['_source']['summary_title'] }}</a></td>
            <td>{{ $row['_source']['admin']['source'] }}</td>
            <td>{{ $row['_source']['audience'][0] or '&nbsp'; }}</td>
            <td>{{ $row['_source']['subject']['ldcode'][0] or 'U' }}</td>
            <td>{{ $row['_type'] }} <?php // echo date('Y-m-d H:i:s', $row['_source']['admin']['processed']/1000); ?></td>
            <td>
              <?php
              if (isset($row['_source']['edited'])) {
                ?>
                <a href="/edit/<?php echo $row['_source']['admin']['uid']; ?>" class="iframe btn btn-small">Edit&nbsp;<i class="fa fa-cog"></i></a>
              <?php
              } else {
                ?>
                <a href="/edit/<?php echo $row['_source']['admin']['uid']; ?>" class="iframe btn btn-small btn-blue">Edit&nbsp;<i class="fa fa-cog"></i></a>
              <?php
              }
              ?>
            </td>
          </tr>
        <?php
        }
        ?>
        </table>
      </div>
     </div>
     <div class="units-row">
        <div class="unit-20"><br/></div>
        <div class="unit-60 text-centered">
          <?php
            echo $resources->links();
          ?>
        </div>
        <div class="unit-20"><br/></div>
     </div>
@stop
