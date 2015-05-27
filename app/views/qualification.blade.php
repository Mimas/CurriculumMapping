<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
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
    .container {
      padding: 7px;
    }
    .units-row {
      margin-bottom: 7px !important;
    }
    label {
      display: block;
      font-weight: 900;
    }
    .jisc {
      color: #ea6000;
      display: inline !important;
    }
    .norman {
      display: inline !important;
    }
    .breathe {
      margin-top: 12px;
    }

	</style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link rel="stylesheet" href="<?php echo asset('3rdparty/kube/css/kube.css'); ?>">
	<link rel="stylesheet" href="<?php echo asset('3rdparty/font-awesome/css/font-awesome.min.css'); ?>">
    <?php
    if (isset($status['close'])) {
    ?>
    <script>
        parent.location.reload();
    </script>
    <?php
    }
    ?>
</head>
<body>
   <form method="post" action="<?php echo asset('subject'); ?>" class="forms">
        <div class="container">
            <div class="units-row">
              <div class="unit-100">
                <h2 class="norman">Qualification - </h2><h2 class="jisc">{{$data->qualification or '' }}</h2>
              </div>
                <div class="unit-100 breathe">
                    <label for="qualification">
                        Qualification<br/>
                        <textarea class="width-100" rows="5" id="qualification" name="qualification">{{$data->qualification}}</textarea>
                        <input type="hidden" name="id" value="<?php echo $data->id; ?>"
                    </label>
                </div>
                <div class="unit-100">
                    <label for="level">
                        Level<br/>
                        <input class="width-100" id="level" name="level" value="{{$data->level}}" />
                    </label>
                </div>
                <div class="unit-100">
                    <label for="qualifier">
                        Framework<br/>
                        <select name="qualifiers">
                          <option value="default" disabled="disabled">Select one--</option>
                          <?php
                          foreach ($qualifiers as $qualifier) {
                          ?>
                          <option <?php if($data->qualifier_id <> 0 && $qualifier->id == $data->qualifier_id) echo "selected" ?> value="$qualifier->id">{{$qualifier->label}}</option>
                          <?php
                          }
                          ?>
                        </select>
                    </label>
                </div>
                <div class="unit-100">
                  <label for="activated">
                    On/Off<br/>
                    <input type="checkbox"
                       id="activated"
                       name="activated"
                       value="{{$data->activated}}" <?php echo($data->activated) ? 'checked="checked"':'' ?> />
                  </label>
                </div>
                <div class="unit-100 text-right">
                    <button class="btn btn-small btn-yellow">Save <i class="fa fa-check"></i> </button>
                </div>
            </div>
        </div>

   </form>
</body>
</html>
