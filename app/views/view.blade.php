@extends('layouts.standard')
@section('content')
            <div class="units-row top44">
                <div class="unit-100">
                    <table class="table-hovered">
                      <th>
                      <h2>{{ $data['_source']['summary_title'] }}</h2>
                      </th>
                      <th class="text-right">
                        <a href="/edit/<?php echo $data['_source']['admin']['uid']; ?>" class="iframe btn btn-small btn-blue">Edit&nbsp;<i class="fa fa-edit"></i></a>
                      </th>
                      <tr>
                        <td class="bold">Source</td><td>{{$data['_source']['admin']['source'] or '' }}</td>
                      </tr>
                      <tr>
                        <td class="bold">Description</td><td>{{$data['_source']['description'][0]['value'] or ''}}</td>
                      </tr>
                      <tr>
                        <td class="bold">Format</td><td>{{ $data['_source']['format'][0] or '' }}</td>
                      </tr>
                      <tr>
                        <td class="bold">Author</td><td>{{$data['_source']['lifecycle']['creation'][0]['author'][0]['name'][0]['value'] or ''}}</td>
                      </tr>
                      <tr>
                        <td class="bold">Publisher</td><td>{{$data['_source']['lifecycle']['publication'][0]['publisher'][0]['name'][0]['value'] or ''}}</td>
                      </tr>
                      <tr>
                        <td class="bold">Licence</td><td>{{$data['_source']['lifecycle']['publication'][0]['rights']['details'] or ''}}</td>
                      </tr>
                      <tr>
                        <td class="bold">Licence Details</td><td><a target="_new" href="{{$data['_source']['lifecycle']['publication'][0]['rights']['uri'] or ''}}">{{$data['_source']['lifecycle']['publication'][0]['rights']['uri'] or ''}}</a></td>
                      </tr>
                      <?php
                      if ( isset($data['_source']['audience']) && $data['_source']['audience'] == 'HE' ) {
                      ?>
                      <tr>
                        <td class="bold">Jacs3 Code/Subject</td>
                        <td>
                          {{$data['_source']['subject'][0]['id'] or ''}}/{{$data['_source']['subject'][0]['value'] or ''}}
                        </td>
                      </tr>
                      <?php
                      } else {
                      ?>
                        <tr>
                          <td class="bold">LD Code/Subject</td>
                          <td>
                            <?php
                            if (isset($data['_source']['subject']['ldcode'])) foreach( $data['_source']['subject']['ldcode'] as $i=>$code ) {
                            ?>
                            {{ $code or '' }}, {{$data['_source']['subject']['ld'][$i] or '' }}<br/>
                            <?php
                            }
                            ?>
                          </td>
                        </tr>

                      <?php
                      }
                      ?>
                      </table>
                      <table class='table-hovered' data-role='bitstreams' id='files'>
                        <th colspan="5">
                          <h3>Resource contents</h3>
                        </th>

                        <tbody>
                        <tr data-role='package'></tr>
                        <th class="width-40">File name</th>
                        <th>File type</th>
                        <th>File size</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <?php
                        if ($bitstreams && count($bitstreams)>0)
                        {
                          foreach($bitstreams as $bitstream)
                          {
                           if ($bitstream->isContent()) {  // hide license files...
                            ?>
                            <tr data-id='{{$bitstream->getId()}}' data-role='bitstream'>
                              <td data-role='file-name'>{{$bitstream->getName()}}</td>
                              <td data-role='format'>
                                <i class='fa fa-file'></i>
                                {{$bitstream->getFormat()}}
                              </td>
                              <td data-role='size'>
                                <?php echo \Bentleysoft\Helper::formatBytes($bitstream->getSizeBytes())?>
                              </td>
                              <td data-role='download'>
                                {{Form::open(array('url' => 'download', 'method' => 'post')); }}
                                <button class="btn btn-smaller">Download&nbsp;<i class="fa fa-download-o"></i></button>
                                <input type="hidden" name="link" value="{{$bitstream->getRetrieveLink()}}" />
                                <input type="hidden" name="mime" value="{{$bitstream->getMimeType()}}" />
                                <input type="hidden" name="filename" value="{{$bitstream->getName()}}" />
                                {{Form::close();}}
                              </td>
                              <td data-role='preview'>
                                  <a href="{{$bitstream->getPreviewUrl()}}" class="iframe btn btn-smaller btn-blue" rel="group">Preview&nbsp;<i class="fa fa-search"></i></a>
                              </td>
                            </tr>

                        <?php
                            }
                          }
                        }
                        ?>
                        <tr data-role='package'></tr>
                        </tbody>
                      </table>
                </div>
            </div>

@stop