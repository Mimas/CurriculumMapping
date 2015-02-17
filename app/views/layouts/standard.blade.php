<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Metadata Mapping Tool</title>
    <style>
        @import url(//fonts.googleapis.com/css?family=Lato:700);
        body {
          margin: 0;
          font-family: 'Lato', sans-serif;
          color: #999;
        }
        .units-row {
          margin-bottom: 7px !important;
        }
        .wrap {
          margin: auto;
          width: 80%;
          max-width: 1200px;
        }
        .welcome {
          padding-top: 32px;
        }
        .dashboard {
          margin-top: 32px;
        }
        .top44 {
          margin-top: 32px;
        }
        ul.horizontal {
          margin-left: 0px;
          list-style: none !important;
          text-align: left !important;
        }
        ul.horizontal li {
          display: inline;
        }
        .menu {
          padding-top: 11px;
        }
        .spacer {
          margin-bottom: 22px;
        }
        form {
          display: inline;
        }
        .breathe-left {
          margin-left: 7px !important;
        }
        .dashboard {
          font-size: 1.2em;
        ;
        }
        .welcome a {
          text-decoration: none;
          color: #ea6000;
          font-weight: 900;
        }
        .dash-1 {
          font-size: 1.2em;
        }
        .dash-1 a {
          text-decoration: none;
          color: #ea6000;
        }
        .dash-2 {
          font-size: 1.2em;
        }
        .dash-2 a {
          text-decoration: none;
          color: #ea6000;
        }

        .navbar {
          font-size: 85% !important;
        }
        .inliner {
          display: inline !important;
        }
        .total {
          font-weight: 900;
          color: #ea6000;
        }
        .normal {
          color: #ea6000 !important;
        }
        .active {
          color: #ea6000 !important;
          border-bottom: 2px #ea6000 solid;
        }
        .badge {
          display: inline !important;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <link rel="stylesheet" href="<?php echo asset('3rdparty/kube/css/kube.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('3rdparty/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('3rdparty/colorbox/colorbox.css'); ?>"/>
    <script src="<?php echo asset('3rdparty/colorbox/jquery.colorbox.js');?>"></script>
    <script src="<?php echo asset('3rdparty/highcharts/highcharts.js');?>"></script>
    <script src="<?php echo asset('3rdparty/highcharts/highcharts-more.js');?>"></script>
    <script>
        $(document).ready(function(){
            $(".iframe").colorbox({iframe:true, width:"80%", height:"100%"});
            $(".colorbox").colorbox({iframe:true, width:"80%", height:"80%"});
            $(".autosubmit").click(function() {
                $('.search').submit();
                $('.autoform').submit();
              }
            );
            $(".autoselect").change(function() {
                $('.search').submit();
              }
            );
        });
    </script>
    <style>
      header .wrap {
        background:url(<?php echo asset('assets/img') ?>/jisc-logo.png) no-repeat bottom left;
      }

      header {
        background-color:#372f2d;
      }
      #logo h2 {
        float:left;
        padding:0;
        padding-top:10px !important;
        padding-left:88px !important;
        color:#ffffff;
        font-size:14pt;
        margin-top:15px !important;
      }

      #logo a {
        text-decoration:none;
      }

      .menu li a, .menu li a:hover {
        color:#ffffff;
      }
      /*Morse code background*/
      section {
        background:url(<?php echo asset('assets/img') ?>/morse-code.png) no-repeat top center;
        background-size:65%;

      }

      .cta {
        background-color:#e02251;
        padding-top:2px;
        padding-bottom:2px;
        padding-left:12px;
        padding-right:12px;
        font-size:9pt;
        color:#ffffff;
      }

      .navbar {
        margin-bottom:0;
        margin-top:10px;
      }
      p, h2, a {
        font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
      }
      @media only screen and (max-width: 767px) {
        header .wrap {
          background:url(<?php echo asset('assets/img') ?>/jisc-logo.png) no-repeat top left;

        }
        #logo h2
        {
          padding-top:0;
          font-size:13pt;
        }
      }


    </style>
</head>
<body>

<header>
  <div class="wrap">
    <div class="units-row"> <a href="<?php echo asset(''); ?>">
        <div class="unit-35" id="logo">
          <h2>Curriculum Mapping Tool</h2>
        </div> </a>

      <div class="unit-65 text-right">
        <?php
        if (!isset($error)) {
          ?>
          <nav class="navbar navbar-right">
            <ul class="menu">
              <?php if(\Sentry::getUser() == null && $_SERVER['REQUEST_URI']!=='/login'): ?>
                <li><a class="<?php echo \Bentleysoft\Helper::isMenuSlected('login') ?>" href="/login">Sign In</a></li>
              <?php endif; ?>

              <?php if (\Bentleysoft\Helper::userHasAccess(array('resource.manage')) || \Bentleysoft\Helper::superUser()): ?>
                <li><a class="<?php echo \Bentleysoft\Helper::isMenuSlected('resources') ?>" href="/resources">Resources</a></li>
              <?php endif; ?>
              <?php if (\Bentleysoft\Helper::userHasAccess(array('subjectareas.manage')) || \Bentleysoft\Helper::userHasAccess(array('subjectareas.admin'))  || \Bentleysoft\Helper::superUser() ): ?>
                <li><a class="<?php echo \Bentleysoft\Helper::isMenuSlected('subjectareas') ?>" href="/subjectareas">Subjects</a></li>
              <?php endif; ?>

              <?php if (\Bentleysoft\Helper::userHasAccess(array('subjectareas.admin')) || \Bentleysoft\Helper::superUser()): ?>
                <li><a class="<?php echo \Bentleysoft\Helper::isMenuSlected('ldcs') ?>" href="/ldcs">Classification</a></li>
              <?php endif; ?>

              <?php if (\Bentleysoft\Helper::userHasAccess(array('qualifications.manage')) || \Bentleysoft\Helper::superUser()): ?>
                <li><a class="<?php echo \Bentleysoft\Helper::isMenuSlected('qualifications') ?>" href="/qualifications">Qualifications</a></li>
              <?php endif; ?>

              <?php if (\Bentleysoft\Helper::userHasAccess(array('user.create', 'user.delete', 'user.update', 'user.view')) || Bentleysoft\Helper::superUser()): ?>
                <li><a class="<?php echo \Bentleysoft\Helper::isMenuSlected('users') ?>" href="/admin/users">Users</a></li>
              <?php endif; ?>

              <?php if (\Bentleysoft\Helper::userHasAccess(array('redis.admin' )) || \Bentleysoft\Helper::superUser() ): ?>
                <li><a style="display: inline;" class="<?php echo \Bentleysoft\Helper::isMenuSlected('redis') ?>" href="/redis">Cache</a> <span  class="badge badge-small">{{\Bentleysoft\Helper::getRedisCount()}}</span></li>
              <?php endif; ?>

              <li><a class="<?php echo \Bentleysoft\Helper::isMenuSlected('contact') ?>" href="/contact">Help/Feedback</a></li>
              <?php if (\Sentry::getUser()!=null): ?>
                <li><a class="<?php echo \Bentleysoft\Helper::isMenuSlected('logout') ?>" href="/logout">Sign Out</a></li>
              <?php endif; ?>

            </ul>
          </nav>
        <?php
        }
        ?>
      </div>
    </div>
  </div>
</header>
<section>
&nbsp;
</section>

    <div class="wrap">
        @yield('content')
    </div>
    <footer id="footer">
      <div class="wrap">
      <nav class="navbar navbar-right">
        <?php
        if (!isset($error)) {
          ?>
          <nav class="navbar navbar-right">
            <ul class="menu">
              <?php if(\Sentry::getUser() == null && $_SERVER['REQUEST_URI']!=='/login'): ?>
                <li><a href="/login">Sign In</a></li>
              <?php endif; ?>

              <?php if (Bentleysoft\Helper::userHasAccess(array('resource.manage')) || Bentleysoft\Helper::superUser()): ?>
                <li><a href="/resources">Resources</a></li>
              <?php endif; ?>
              <?php if (Bentleysoft\Helper::userHasAccess(array('subjectareas.manage')) || Bentleysoft\Helper::userHasAccess(array('subjectareas.admin'))  || Bentleysoft\Helper::superUser() ): ?>
                <li><a href="/subjectareas">Subjects</a></li>
              <?php endif; ?>

              <?php if (Bentleysoft\Helper::userHasAccess(array('subjectareas.admin')) || Bentleysoft\Helper::superUser()): ?>
                <li><a href="/ldcs">Classification</a></li>
              <?php endif; ?>

              <?php if (Bentleysoft\Helper::userHasAccess(array('qualifications.manage')) || Bentleysoft\Helper::superUser()): ?>
                <li><a href="/qualifications">Qualifications</a></li>
              <?php endif; ?>

              <?php if (Bentleysoft\Helper::userHasAccess(array('user.create', 'user.delete', 'user.update', 'user.view', 'application.admin' )) || Bentleysoft\Helper::superUser()): ?>
                <li><a href="/admin/users">Users</a></li>
              <?php endif; ?>

              <?php if (Bentleysoft\Helper::userHasAccess(array('redis.admin' )) || Bentleysoft\Helper::superUser() ): ?>
                <li><a href="/redis">Cache</a></li>
              <?php endif; ?>

                <li><a href="/contact">Help/Feedback</a></li>
              <?php if (!\Sentry::getUser() == null): ?>
                <li><a href="/logout">Sign Out</a></li>
              <?php endif; ?>
            </ul>
          </nav>
        <?php
        }
        ?>
      </nav>
      <p>
        © JISC 2014-2015
      </p>
    </footer>
    </div>
  </body>
</html>
