@extends('layouts.standard')
@section('content')

            <div class="units-row top44">
                <div class="unit-100">
                    <table class="table-hovered">
                      <th colspan="2">
                      <h2>{{ $data['_source']['summary_title'] }}</h2>
                      </th>
                      <tr>
                        <td class="bold">Source</td><td>{{$data['_source']['admin']['source']}}</td>
                      </tr>
                      <tr>
                        <td class="bold">Description</td><td>{{$data['_source']['description'][0]['value']}}</td>
                      </tr>
                      <tr>
                        <td class="bold">Format</td><td>{{$data['_source']['format'][0]}}</td>
                      </tr>
                      <tr>
                        <td class="bold">Author</td><td>{{$data['_source']['lifecycle']['creation'][0]['author'][0]['name'][0]['value']}}</td>
                      </tr>
                      <tr>
                        <td class="bold">Publisher</td><td>{{$data['_source']['lifecycle']['publication'][0]['publisher'][0]['name'][0]['value']}}</td>
                      </tr>
                      <tr>
                        <td class="bold">Licence</td><td>{{$data['_source']['lifecycle']['publication'][0]['rights']['details']}}</td>
                      </tr>
                      <tr>
                        <td class="bold">Licence</td><td>{{$data['_source']['lifecycle']['publication'][0]['rights']['uri']}}</td>
                      </tr>
                      <tr>
                        <td class="bold">Jacs2 Code/Subject</td>
                        <td>
                          {{$data['_source']['subject'][0]['id']}}/{{$data['_source']['subject'][0]['value']}}
                        </td>
                      </tr>
                      </table>
                      <table class='table-hovered' data-role='bitstreams' id='files'>
                        <th colspan="5">
                          <h3>Resource contents</h3>
                        </th>

                        <tbody>
                        <tr data-role='package'></tr>
                        <th>File name</th>
                        <th>File type</th>
                        <th>File size</th>
                        <th></th>
                        <th></th>
                        <?php
                        if ($bitstreams && count($bitstreams)>0)
                        {
                          foreach($bitstreams as $bitstream)
                          {
                           if ($bitstream->getFormat() <> 'Unknown') {
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
                              <td data-role='preview'><a href="http://dspace.jorum.ac.uk/rest{{$bitstream->getRetrieveLink()}}" class="iframe btn btn-smaller btn-blue" rel="group">Preview</a></td>
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