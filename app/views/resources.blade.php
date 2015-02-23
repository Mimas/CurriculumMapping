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
  .breathe {
    margin-top: 11px;;
  }
  form {
    display: inline;
  }
</style>
    <form method="get" action="<?php echo asset(Request::path()); ?>" class="forms search">
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
            <ul class="blocks-3 small">
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
          <div class="unit-40">
            <div class="text-right pager" style="float: right;">
              <span class="total"><?php echo number_format($total); ?></span> Resources, page {{$page}} of {{ $resources->getLastPage() }}</span>&nbsp;&nbsp;|&nbsp;&nbsp;
              {{ Form::select('pageSize', \Bentleysoft\Helper::pageSizes(), $pageSize, array('class' => 'autoselect inliner')); }}
              <ul class="forms-inline-list small">
                <li>  
                  <label class="breathe" for="show_mapped">Mapped
                    <input name="show_mapped" type="checkbox" class="autosubmit" <?php if (isset($showMapped) && $showMapped) echo 'checked="yes";' ?> />
                  </label>
                </li>                              
                <li>  
                  <label class="breathe" for="hide_mapped">Unmapped
                    <input name="show_unmapped" type="checkbox" class="autosubmit" <?php if (isset($showUnmapped) && $showUnmapped) echo 'checked="yes";' ?> />
                  </label>
                </li>

                <li>
                  <label class="breathe" for="show_viewable">Viewable
                    <input name="show_viewable" type="checkbox" class="autosubmit" <?php if (isset($showViewable) && $showViewable)  echo 'checked="yes";' ?> />
                  </label>
                </li>
                <li>
                  <label class="breathe" for="show_unviewable">Non Viewable
                    <input name="show_unviewable" type="checkbox" class="autosubmit" <?php if (isset($showUnviewable) && $showUnviewable) echo 'checked="yes";' ?> />
                  </label>
                </li>
            </ul>
            </div>
          </div>
        </div>
    </form>
    <div class="units-row">
      <div class="unit-100">
        <table class="table-hovered table-stripped">
          <thead>
          <tr>
            <th>Title</th>
            <th class="text-centered">Source</th>
            <th>Subject Area</th>
            <th class="text-centered">Current</th>
            <th class="text-centered">Status</th>
            <th class="text-centered">Viewable</th>
            <th class="text-centered">Action</th>
          </tr>
          </thead>
        <?php
        foreach ($data['hits']['hits'] as $row) {
        ?>
          <tr>
            <td class="small width-20"><a href="/view/<?php echo $row['_source']['admin']['uid']; ?>">{{ $row['_source']['summary_title'] }}</a></td>
            <td class="text-centered small">
              {{ $row['_source']['admin']['source'] }}
            </td>

            <td class="width-20 small">
              <?php
              if (isset($row['_source']['subject']['ld'][0])) {
                echo str_limit($row['_source']['subject']['ld'][0], 41, '...' );
              } elseif(isset($row['_source']['subject'][0]['ld'])) {
                echo str_limit($row['_source']['subject'][0]['ld'][0], 41, '...' );
              } else {
                echo 'Undefined';
              }
              ?>
              </td>
            <td class="text-centered">
              <?php echo (Mapping::getCurrent($row['_source']['admin']['uid'])) ? 'yes' : 'no' ?>
            </td>
            <td class="text-centered">
            <?php
            if (\Bentleysoft\Helper::superUser()) {    // edited flag, only show if superuser
            ?>
              {{Form::open(array('url' => asset('/resource/toggle').'/'.$row['_source']['admin']['uid'], 'method' => 'put')); }}
              <input type="hidden" name="_id" value="<?php echo $row['_source']['admin']['uid'] ?>"/>
              <input type="hidden" name="return_to" value="<?php echo(Request::fullUrl()) ?>" />
              <button class="btn btn-smaller  <?php if (isset($row['_source']['edited'])&& $row['_source']['edited']) echo 'btn-active' ?> ">
                <?php echo(isset($row['_source']['edited'])&& $row['_source']['edited']=='yes') ? 'Mapped' : 'Unmapped' ?>
              </button>
              {{Form::close()}}
            <?php
            } else {
            ?>
            <i class="fa <?php if (isset($row['_source']['edited'])&& $row['_source']['edited']=='yes') echo('fa-map-marker');?>"></i>
            <?php
            }
            ?>
            </td>
            <td class="text-centered">
            <?php
            if (\Bentleysoft\Helper::superUser()) {    // edited flag, only show if superuser
            ?>
              {{Form::open(array('url' => asset('/resource/setviewable').'/'.$row['_source']['admin']['uid'], 'method' => 'put')); }}
              <input type="hidden" name="_id" value="<?php echo $row['_source']['admin']['uid'] ?>"/>
              <input type="hidden" name="return_to" value="<?php echo(Request::fullUrl()) ?>" />
              <button class="btn btn-smaller  <?php if (isset($row['_source']['viewable'])&& $row['_source']['viewable']) ?> echo 'btn-active' ?> ">
                <?php echo(isset($row['_source']['viewable'])&& $row['_source']['viewable']=='yes') ? 'Viewable' : 'Not Viewable' ?>
              </button>
              {{Form::close()}}
            <?php
              } else {
              ?>
              <i class="fa <?php if (Mapping::getViewable($row['_source']['admin']['uid'])) echo('fa-certificate');?>"></i>
            <?php
            }
            ?>
            </td>
            <td class="text-centered">
              <?php
              if (isset($row['_source']['edited']) && $row['_source']['edited']=='yes') {
                ?>
                <a href="/edit/<?php echo $row['_source']['admin']['uid']; ?>" class="iframe btn btn-small">Map&nbsp;<i class="fa fa-cog"></i></a>
              <?php
                } else {
               ?>
                <a href="/edit/<?php echo $row['_source']['admin']['uid']; ?>" class="iframe btn btn-small btn-blue">Map&nbsp;<i class="fa fa-cog"></i></a>
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
            echo $resources->appends(Input::except('page'))->links();
          ?>
        </div>
        <div class="unit-20"><br/></div>
     </div>
@stop
