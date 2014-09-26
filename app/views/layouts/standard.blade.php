<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Metadata Mapping Tool</title>
    <style>
        @import url(//fonts.googleapis.com/css?family=Lato:700);
        body {
            margin:0;
            font-family:'Lato', sans-serif;
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
          margin-top: 32px;
        }

        .dashboard {
          margin-top: 32px;
        }

        .top44 {
          margin-top: 32px;
        }

        ul.horizontal
        {
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
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <link rel="stylesheet" href="<?php echo asset('3rdparty/kube/css/kube.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('3rdparty/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('3rdparty/colorbox/colorbox.css'); ?>"/>
    <script src="<?php echo asset('3rdparty/colorbox/jquery.colorbox.js');?>"></script>

    <script>
        $(document).ready(function(){
            $(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
            $(".colorbox").colorbox({iframe:true, width:"80%", height:"80%"});
        });
    </script>
</head>
<body>
    <div class="wrap">
        <div class="units-row">
            <div class="unit-40">
                <img alt="Jisc" title="Jisc logo" src="<?php echo asset('assets/img') ?>/jisc-logo.png" />
            </div>
            <div class="unit-60 text-right">
              <?php
              if (!isset($error)) {
              ?>
              <nav class="navbar navbar-right">
                <ul class="menu">
                  <?php if(! Sentry::check() && $_SERVER['REQUEST_URI']!=='/login'): ?>
                    <li><a href="/login">Sign In</a></li>
                  <?php endif; ?>

                  <?php if (Bentleysoft\Helper::userHasAccess(array('resource.manage'))): ?>
                    <li><a href="/">Resources</a></li>
                  <?php endif; ?>

                  <?php if (Bentleysoft\Helper::userHasAccess(array('subjectareas.manage'))): ?>
                    <li><a href="/subjectareas">Subject Areas</a></li>
                  <?php endif; ?>

                  <?php if (Bentleysoft\Helper::userHasAccess(array('user.create', 'user.delete', 'user.update', 'user.view', 'application.admin' ))): ?>
                    <li><a href="/admin/users">Users</a></li>
                  <?php endif; ?>

                  <?php if (Bentleysoft\Helper::userHasAccess(array('redis.admin' ))): ?>
                    <li><a href="/redis">Cache</a></li>
                  <?php endif; ?>

                  <?php if (Sentry::check()): ?>
                    <li><a href="/logout">Sign Out</a></li>
                  <?php endif; ?>

                </ul>
              </nav>
              <?php
              }
              ?>
            </div>
        </div>
    </div>
    <div class="wrap">
        @yield('content')
    </div>
</body>
</html>
