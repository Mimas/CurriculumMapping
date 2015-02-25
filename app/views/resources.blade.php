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
<?php
if (isset($stuff))
  echo $stuff;

?>
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
            <th class="small">Title</th>
            <th class="text-centered small">Source</th>
            <th class="small">Subject Area</th>
            <th class="text-centered small">Current</th>
            <th class="text-centered small">Mapped</th>
            <th class="text-centered small">Viewable</th>
            <th class="text-centered small">Action</th>
            <?php if (\Bentleysoft\Helper::userHasAccess(array('resource.manage')) || \Bentleysoft\Helper::superUser()): ?>
            <th class="text-centered small">
            Good to go!
            </th>
            <?php endif; ?>
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
              <?php if (Mapping::getCurrent($row['_source']['admin']['uid'])) { ?>
                <i class="fa fa-check traffic-green"></i>
              <?php } else { ?>
                <i class="fa fa-exclamation-triangle"></i>
              <?php } ?>
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
              <button class="btn btn-smaller <?php if (isset($row['_source']['viewable'])&& $row['_source']['viewable']) echo 'btn-active btn-yellow' ?> ">
                <?php echo(isset($row['_source']['viewable'])&& $row['_source']['viewable']=='yes') ? 'Viewable' : 'Not Viewable' ?>
              </button>
              {{Form::close()}}
            <?php
              } else {
              ?>
              <i class="traffic-amber fa <?php if (isset($row['_source']['viewable'])&& $row['_source']['viewable']) echo('fa-eye');?>"></i>
            <?php
            }
            ?>
            </td>
            <td class="text-centered">
              <?php
              if (isset($row['_source']['edited']) && $row['_source']['edited']) {
                ?>
                <a href="/edit/<?php echo $row['_source']['admin']['uid']; ?>" class="iframe btn btn-small">Map&nbsp;<i class="fa fa-map-marker"></i></a>
              <?php
                } else {
               ?>
                <a href="/edit/<?php echo $row['_source']['admin']['uid']; ?>" class="iframe btn btn-small btn-blue">Map&nbsp;<i class="fa fa-map-marker"></i></a>
              <?php
              }
              ?>
            </td>
            <?php if (\Bentleysoft\Helper::userHasAccess(array('resource.manage')) || \Bentleysoft\Helper::superUser()): ?>
            <td class="text-centered">
              {{Form::open(array('url' => asset('/resource/publish').'/'.$row['_source']['admin']['uid'], 'method' => 'put', 'class'=>'forms') ); }}
              <input type="hidden" name="_id" value="<?php echo $row['_source']['admin']['uid'] ?>"/>
              <input type="hidden" name="return_to" value="<?php  echo Request::fullUrl(); ?>"/>
              <input type="hidden" name="origin" value="resources"/>
              <button class="naked_button" type="submit">
                <?php if (isset($row['_source']['fewindow']) && $row['_source']['fewindow']): ?>
                  <i class="fa fa-check biggs traffic-green"></i>
                  <input type="hidden" name="action" value="unpublish" />
                <?php else: ?>
                  <i class="fa fa-thumbs-up traffic-grey"></i>
                  <input type="hidden" name="action" value="publish" />
                <?php endif; ?>
              </button>
              {{Form::close()}}
            </td>
            <?php endif; ?>
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
