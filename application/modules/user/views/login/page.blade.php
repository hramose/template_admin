<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ lang('system_name') }}</title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="{{ lang('system_description') }}">
    <meta name="keywords" content="{{ lang('system_keywords') }}">
    <meta name="author" content="{{ lang('system_author') }}">
    <!-- Favicon icon -->
    <link rel="icon" href="{{ base_url() }}assets/global/assets/images/favicon.ico" type="image/x-icon">
    <!-- Google font--><link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="{{ base_url() }}assets/global/bower_components/bootstrap/css/bootstrap.min.css">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href="{{ base_url() }}assets/global/assets/icon/themify-icons/themify-icons.css">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="{{ base_url() }}assets/global/assets/icon/icofont/css/icofont.css">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{ base_url() }}assets/global/assets/css/style.css">
</head>

<body class="fix-menu">
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="ball-scale">
            <div class='contain'>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
            </div>
        </div>
    </div>
    <!-- Pre-loader end -->

    <section class="login-block">
        <!-- Container-fluid starts -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->
                    {{ form_open('auth/login', $form_attributes) }}
                        <div class="text-center">
                            <img src="{{ base_url() }}assets/global/assets/images/logo.png" alt="logo.png">
                        </div>
                        <div class="auth-box card">
                            <div class="card-block">
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <h3 class="text-center">Sign In</h3>
                                    </div>
                                </div>

                                @if (!empty($message))
                                <div class="alert alert-info">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <i class="icofont icofont-close-line-circled"></i>
                                    </button>
                                    {{ $message }}                                    
                                </div>
                                @endif

                                <div class="form-group form-primary">
                                    {{ form_input($username) }}
                                    <span class="form-bar"></span>
                                </div>
                                <div class="form-group form-primary">
                                    {{ form_password($password) }}
                                    <span class="form-bar"></span>
                                </div>
                                <div class="row m-t-25 text-left">
                                    <div class="col-12">
                                        <div class="checkbox-fade fade-in-primary d-">
                                            <label>
                                                <input type="checkbox" name="remember" value="1">
                                                <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                                <span class="text-inverse">Remember me</span>
                                            </label>
                                        </div>
                                        <div class="forgot-phone text-right f-right">
                                            <a href="javascript:;" id="forget-password" class="text-right f-w-600"> Forgot Password?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20">Sign in</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {{ form_close() }}
                    <!-- end of Authentication form -->

                    <!-- BEGIN FORGOT PASSWORD FORM -->
                    <form action="{{ base_url() . 'forgot-password' }}" id="forget-form" class="md-float-material form-material" method="post">
                        <input type="hidden" name="{{ $csrftoken_name }}" value="{{ $csrftoken_value }}" />
                        <div class="text-center">
                            <img src="{{ base_url() }}assets/global/assets/images/logo.png" alt="logo.png">
                        </div>
                        <div class="auth-box card">
                            <div class="card-block">
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <h3 class="text-left">Recover your password</h3>
                                    </div>
                                </div>
                                
                                <div class="form-group form-primary">
                                    <input type="email" name="email" class="form-control" autocomplete="off" required="" placeholder="Your Email Address">
                                    <span class="form-bar"></span>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Reset Password</button>
                                    </div>
                                </div>
                                <p class="f-w-600 text-right">Back to <a href="javascript:;" id="back-login">Login.</a></p>
                                
                            </div>
                        </div>
                    </form>
                    <!-- END FORGOT PASSWORD FORM -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>
    <!-- Warning Section Starts -->
    <!-- Older IE warning message -->
    <!--[if lt IE 10]>
<div class="ie-warning">
    <h1>Warning!!</h1>
    <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
    <div class="iew-container">
        <ul class="iew-download">
            <li>
                <a href="http://www.google.com/chrome/">
                    <img src="../files/assets/images/browser/chrome.png" alt="Chrome">
                    <div>Chrome</div>
                </a>
            </li>
            <li>
                <a href="https://www.mozilla.org/en-US/firefox/new/">
                    <img src="../files/assets/images/browser/firefox.png" alt="Firefox">
                    <div>Firefox</div>
                </a>
            </li>
            <li>
                <a href="http://www.opera.com">
                    <img src="../files/assets/images/browser/opera.png" alt="Opera">
                    <div>Opera</div>
                </a>
            </li>
            <li>
                <a href="https://www.apple.com/safari/">
                    <img src="../files/assets/images/browser/safari.png" alt="Safari">
                    <div>Safari</div>
                </a>
            </li>
            <li>
                <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                    <img src="../files/assets/images/browser/ie.png" alt="">
                    <div>IE (9 & above)</div>
                </a>
            </li>
        </ul>
    </div>
    <p>Sorry for the inconvenience!</p>
</div>
<![endif]-->
    <!-- Warning Section Ends -->
    <!-- Required Jquery -->
    <script type="text/javascript" src="{{ base_url() }}assets/global/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{ base_url() }}assets/global/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{ base_url() }}assets/global/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="{{ base_url() }}assets/global/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="{{ base_url() }}assets/global/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>
    <!-- modernizr js -->
    <script type="text/javascript" src="{{ base_url() }}assets/global/bower_components/modernizr/js/modernizr.js"></script>
    <script type="text/javascript" src="{{ base_url() }}assets/global/bower_components/modernizr/js/css-scrollbars.js"></script>
    <!-- i18next.min.js -->
    <script type="text/javascript" src="{{ base_url() }}assets/global/bower_components/i18next/js/i18next.min.js"></script>
    <script type="text/javascript" src="{{ base_url() }}assets/global/bower_components/i18next-xhr-backend/js/i18nextXHRBackend.min.js"></script>
    <script type="text/javascript" src="{{ base_url() }}assets/global/bower_components/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js"></script>
    <script type="text/javascript" src="{{ base_url() }}assets/global/bower_components/jquery-i18next/js/jquery-i18next.min.js"></script>
    <script type="text/javascript" src="{{ base_url() }}assets/global/assets/js/common-pages.js"></script>
    <script type="text/javascript">    
        $(function() {
            $("#login-form").show();
            $("#forget-form").hide();
        });
        
        $("#forget-password").click(function(){
            $("#login-form").hide();
            $("#forget-form").show();
        });
        $("#back-login").click(function(){
            $("#login-form").show();
            $("#forget-form").hide();
        });
    </script>
</body>

</html>
