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
            <div class="unit-50">
              &nbsp;&nbsp;&nbsp;
            </div>
            <div class="unit-40">
              <div class="text-right pager" style="float: right;">
              <span class="total">{{$total}}</span> Resources, page {{$page}} of {{ $resources->getLastPage() }}</span>&nbsp;&nbsp;|&nbsp;&nbsp;
              {{ Form::select('pageSize', \Bentleysoft\Helper::pageSizes(), $pageSize, array('class' => 'autoselect inliner')); }}
              </div>
            </div>
        </div>
    </form>
    <div style="margin-top: 22px;">    
      <?php
      foreach ($data['hits']['hits'] as $row) {
          ?>
          <div class="units-row">
              <div class="unit-40">
                <a href="/view/<?php echo $row['_source']['admin']['uid']; ?>">{{ $row['_source']['summary_title'] }}</a>
              </div>
              <div class="unit-10">
                  {{ $row['_source']['admin']['source'] }}
              </div>
            <div class="unit-10">
              {{ $row['_source']['audience'][0] or '&nbsp;' }}
            </div>
              <div class="unit-20">
                  {{ $row['_type'] }} <?php // echo date('Y-m-d H:i:s', $row['_source']['admin']['processed']/1000); ?>
              </div>
              <div class="unit-20">
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
              </div>
          </div>
       <?php
      }
      ?>
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
