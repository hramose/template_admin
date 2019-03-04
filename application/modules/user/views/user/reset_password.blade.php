
<html lang="en">
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>Reset Password Sekolah Tinggi Penerbangan Indonesia</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <link rel="icon" href="{{ base_url() }}assets/images/logo.png" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="{{ base_url() }}assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{ base_url() }}assets/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ base_url() }}assets/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="{{ base_url() }}assets/css/login-3.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
    </head>
    <!-- END HEAD -->

    <body class=" login" style="background-color: #ef940e !important">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <h1 style="color: #eee"><b>Semut Merah<br/>Warehouse Management System</b></h1>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            {{ form_open('reset-password/'.$code, $form_attributes) }}
            <input type="hidden" name="user_id" value="{{ $user_id }}" />

            @if (!empty($message_error))

            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                {{ $message_error }}
            </div>

            @endif

            @if (!empty($message_success))

            <div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                {{ $message_success }}
            </div>

            @endif

            <h3 class="form-title">Reset account password</h3>
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">New Password</label>
                <div class="input-icon">
                    <i class="fa fa-user"></i>
                    {{ form_password($new_password) }}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Confirm Password</label>
                <div class="input-icon">
                    <i class="fa fa-lock"></i>
                    {{ form_password($confirm_password) }}
                </div>
            </div>
            <div class="form-actions text-right">
                <button type="submit" class="btn yellow-gold"> Login </button>
            </div>

            {{ form_close() }}
            <!-- END LOGIN FORM -->
        </div>


        <!-- BEGIN CORE PLUGINS -->
        <script src="{{ base_url() }}assets/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{ base_url() }}assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src=".{{ base_url() }}assets/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="{{ base_url() }}assets/scripts/login-5.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>

</html>