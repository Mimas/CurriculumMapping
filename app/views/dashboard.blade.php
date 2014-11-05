@extends('layouts.standard')
@section('content')
<script type="text/javascript">
  $(function () {
    $('#container').highcharts({
        chart: {
          type: 'gauge',
          plotBorderWidth: 1,
          plotBackgroundColor: {
            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
            stops: [
              [0, '#FFF4C6'],
              [0.3, '#FFFFFF'],
              [1, '#FFF4C6']
            ]
          },
          plotBackgroundImage: null,
          height: 160
        },

        title: {
          text: 'Title',
          style: {
            'color': 'ffffff',
            'display': 'none'
          }
        },
        credits: {
          enabled: false
        },
        pane: [{
          startAngle: -45,
          endAngle: 45,
          background: null,
          center: ['50%', '145%'],
          size: 300
        }],

        yAxis: [{
          min: 0,
          max: <?php echo  $total ?>,
          minorTickPosition: 'outside',
          tickPosition: 'outside',
          labels: {
            rotation: 'auto',
            distance: 20
          },
          plotBands: [{
            from: <?php echo $total > 0 ? $total-$total/4 : 0; ?>,
            to: <?php echo $total ?>,
            color: '#ea6000',
            innerRadius: '100%',
            outerRadius: '105%'
          }],
          pane: 0,
          title: {
            text: 'VU<br/><span style="font-size:8px">Channel A</span>',
            y: -40
          }
        }, {

        }],

        plotOptions: {
          gauge: {
            dataLabels: {
              enabled: false
            },
            dial: {
              radius: '100%'
            }
          }
        },

        series: [{
          data: [<?php echo $mapped ?>],
          yAxis: 0
        }]

      },

      // Let the music play
      function (chart) {
        setInterval(function () {
          var left = chart.series[0].points[0],
            right = chart.series[1].points[0],
            leftVal,
            rightVal,
            inc = (Math.random() - 0.5) * 3;

          leftVal =  left.y + inc;
          rightVal = leftVal + inc / 3;
          if (leftVal < -20 || leftVal > 6) {
            leftVal = left.y - inc;
          }
          if (rightVal < -20 || rightVal > 6) {
            rightVal = leftVal;
          }

          left.update(leftVal, false);
          right.update(rightVal, false);
          chart.redraw();

        }, 500);

      });
  });
</script>
<div class="units-row">
    <div class="unit-100 unit-centered">
        @if(Session::has('success'))
        <div class="error row-fluid">
        </div>
        @endif
        <div class="welcome">
        <h2>Welcome to the pilot trial</h2>
         <p>
          Thank you for agreeing to participate in our pilot trail and for sharing your subject expertise and insights.<br/>
          The trial runs from <span class="bold">Monday 3 November until Friday 5 December.</span>
         </p>
          <p>
          The <span class="bold">purpose of the trial</span> is to map content to relevant subject area and levels. You will also have the opportunity to identify content that is out of date, has quality issues or has been tagged with an inappropriate subject area. We are also keen to find out where the resource gaps are in terms of your particular subject area and what other resources out there, that you find useful.
          </p>
          <p>
          We recommend that you use <span class="bold">Safari, Chrome or Firefox.</span>
          </p>
          <p>
          Once you log, in you will be able to <span class="bold">get in touch with the team</span> by clicking on the <a href="{{asset('contact')}}">‘Help / Feedback’ link</a>, which is on the top right of your screen.
          </p>
          <p>
          Thanks again and happy <a href="{{asset('resources')}}">mapping!</a>
          </p>


        </div>
        <div class="dashboard">
          <p>There are <span class="dash-1"><a href="<?php echo asset('resources'); ?>">{{$total}}</a></span> resources in the index in total,
            <span class="dash-2"><a href="<?php echo asset('mapped'); ?>">{{$mapped}}</a></span> of which are mapped.
          </p>
          <div id="container" style="margin-top: -30px; width: 300px; height: 300px; "></div>
        </div>
    </div>
</div>
@stop
