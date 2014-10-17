@extends('layouts.standard')
@section('content')
<style type="text/css">
  .pagination {
    text-align: center !important;
    position: relative !important;
    left: 20px;
  }
  .tight {
    margin-bottom: -5px !important;
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
                 foreach ($subjectAreas as $area) {
                  ?>
                   <li class="tight"><label>{{$area->subject}}
                       <input class="autosubmit"
                          type="checkbox" name="areas[]" id="area_{{$area->id}}" value="{{$area->id}}"
                         <?php if( in_array($area->id, $selectedAreas)) echo 'checked="yes";'  ?>
                       />
                       </label>
                   </li>
                <?php
                }
                ?>
              </ul>
            </div>
            <div class="unit-30">
              <div class="text-right pager" style="float: right;">
              <span class="total"><?php echo number_format($total); ?></span> Resources, page {{$page}} of {{ $resources->getLastPage() }}</span>&nbsp;&nbsp;|&nbsp;&nbsp;
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
            <td class="width-30"><a href="/view/<?php echo $row['_source']['admin']['uid']; ?>">{{ $row['_source']['summary_title'] }}</a></td>
            <td>{{ $row['_source']['admin']['source'] }}</td>
            <td>{{ $row['_source']['audience'][0] or '&nbsp'; }}</td>
            <td>
              <?php
              if (isset($row['_source']['subject']['ld'][0])) {
                echo str_limit($row['_source']['subject']['ld'][0], 10, '...' );
              } else {
                echo 'Undefined';
              }
              ?>
              </td>
            <?php
            if (\Bentleysoft\Helper::superUser()) {    // edited flag, only show if superuser
            ?>
            <td>
              {{Form::open(array('url' => asset('/resource/toggle').'/'.$row['_source']['admin']['uid'], 'method' => 'put')); }}
              <input type="hidden" name="_id" value="<?php echo $row['_source']['admin']['uid'] ?>"/>
              <input type="hidden" name="return_to" value="<?php echo(Request::fullUrl()) ?>" />
              <button class="btn btn-smaller  <?php if (isset($row['_source']['edited'])&& $row['_source']['edited']=='yes') echo 'btn-active' ?> ">
                <?php echo(isset($row['_source']['edited'])&& $row['_source']['edited']=='yes') ? 'Mapped' : 'Unmapped' ?>
              </button>
              {{Form::close();}}
            </td>

            <?php
            }
            ?>
            <td>{{ $row['_type'] }} <?php // echo date('Y-m-d H:i:s', $row['_source']['admin']['processed']/1000); ?></td>
            <td>
              <?php
              if (isset($row['_source']['edited']) && $row['_source']['edited']=='yes') {
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
