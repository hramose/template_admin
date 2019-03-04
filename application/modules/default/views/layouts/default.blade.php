<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@section('title') @show</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />

        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />

        <link href="{{ base_url() }}assets/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/bootstrap-toastr/toastr.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/confirm/css/jquery-confirm.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/select2/css/select2-bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/easy-autocomplete/easy-autocomplete.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/plugins/tipso/src/tipso.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ base_url() }}assets/css/plugins-md.min.css" rel="stylesheet" type="text/css" />


        <link href="{{ base_url() }}assets/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ base_url() }}assets/css/themes/light.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="{{ base_url() }}assets/css/custom.css" rel="stylesheet" type="text/css" />

        <link rel="icon" href="{{ base_url() }}assets/img/favicon.ico" />
    </head>

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-md">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="index.html">
                        <img src="{{ base_url() }}assets/img/logo-nav.png" alt="logo" class="logo-default" /> </a>
                    <!-- <h4 class="logo-default font-white pull-left">TIK Systems</h4> -->
                    <div class="menu-toggler sidebar-toggler" onclick="toggle()"></div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-left" style="position: fixed; left: 249px;top: 15px; font-weight: bold;color: #FFF;" id="datetime_navbar">
                        <input type="hidden" id="date_param" value="0">
                        <?php 
                        //Array Hari
                        $array_hari = array(1=>'Senin','Selasa','Rabu','Kamis','Jumat', 'Sabtu','Minggu');
                        $hari = $array_hari[date('N')];
                        echo $hari.', ';
                        echo date('d F Y').'  ';?><span id="clock"><?php print date('H:i:s'); ?>
                    </ul>
                    <ul class="nav navbar-nav pull-right">
                    <!-- 
                        <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true">
                                <i class="icon-bell"></i>
                                <span class="badge badge-default"> 7 </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="external">
                                    <h3>
                                        <span class="bold">12 pending</span> notifications</h3>
                                    <a href="">view all</a>
                                </li>
                                <li>
                                    <div class="slimScrollDiv" style="position: relative; overflow: auto; width: auto; height: 250px;"><ul class="dropdown-menu-list scroller" style="height: 250px; overflow: auto; width: auto;" data-handle-color="#637283" data-initialized="1">
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">just now</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-success">
                                                        <i class="fa fa-plus"></i>
                                                    </span> New user registered. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">3 mins</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-danger">
                                                        <i class="fa fa-bolt"></i>
                                                    </span> Server #12 overloaded. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">10 mins</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-warning">
                                                        <i class="fa fa-bell-o"></i>
                                                    </span> Server #2 not responding. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">14 hrs</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-info">
                                                        <i class="fa fa-bullhorn"></i>
                                                    </span> Application error. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">2 days</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-danger">
                                                        <i class="fa fa-bolt"></i>
                                                    </span> Database overloaded 68%. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">3 days</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-danger">
                                                        <i class="fa fa-bolt"></i>
                                                    </span> A user IP blocked. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">4 days</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-warning">
                                                        <i class="fa fa-bell-o"></i>
                                                    </span> Storage Server #4 not responding dfdfdfd. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">5 days</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-info">
                                                        <i class="fa fa-bullhorn"></i>
                                                    </span> System Error. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">9 days</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-danger">
                                                        <i class="fa fa-bolt"></i>
                                                    </span> Storage server failed. </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    -->
                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <img alt="" class="img-circle" src="{{ base_url() }}assets/img/avatar.png" />
                                <span class="username username-hide-on-mobile"> {{ucfirst(auth_username())}} </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="{{ base_url() }}profile">
                                        <i class="icon-user"></i> My Profile </a>
                                </li>
                                <li>
                                    <a href="{{ base_url() }}notification">
                                        <i class="icon-bell"></i> Notif / Event </a>
                                </li>
                                <li>
                                    <a href="{{ base_url() }}logout">
                                        <i class="icon-key"></i> Log Out </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->

                    </ul>
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- BEGIN SIDEBAR -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- BEGIN SIDEBAR MENU -->
                    <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">

                        <li class="sidebar-toggler-wrapper hide">
                            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                            <div class="sidebar-toggler"> </div>
                            <!-- END SIDEBAR TOGGLER BUTTON -->
                        </li>

                        <li class="heading">
                            <h3 class="uppercase">Menu Navigation</h3>
                        </li>
                        @include('default.views.layouts.menu')
                    </ul>
                    <!-- END SIDEBAR MENU -->
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                @yield('body')
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->

            <!-- List Reminder  -->
            <!-- <div id="reminder" style="z-index:99999;background-color:#F1F1F1;">
                <div id="scollReminder">
                    <div class="col-md-12" style="padding-top: 10px;">
                       
                       <a href="{{base_url()}}">
                           <div class="alert alert-danger">
                                <strong> Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit.</strong>
                           </div>
                       </a>
                       <a href="{{base_url()}}">
                           <div class="alert alert-danger">
                                <strong> Lorem ipsum dolor sit amet, ei ius rebum dicit quaeque.</strong>
                           </div>
                       </a>
                    </div>
                </div>
            </div>
             <div id="btn-reminder" style="position:absolute;right:0;top:60px;">
                <button class="btn yellow-crusta"><i class="fa fa-bell"></i>Info <span class="badge badge-danger"> 2 </span></button>
            </div> -->
           
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner"> 2017 &copy; Octavian Panggestu</div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
        <script src="{{ base_url() }}assets/plugins/respond.min.js"></script>
        <script src="{{ base_url() }}assets/plugins/excanvas.min.js"></script>
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{ base_url() }}assets/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{ base_url() }}assets/scripts/datatable.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/datatables/fnPagingInfo.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/datatables/jquery.dataTables.delay.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{ base_url() }}assets/scripts/app.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="{{ base_url() }}assets/scripts/table-datatables-responsive.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="{{ base_url() }}assets/scripts/layout.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/scripts/demo.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/scripts/knockout-3.4.0.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/jquery-validation/js/jquery.validate.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/form/jquery.form.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/confirm/js/jquery-confirm.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/bootstrap-select/js/bootstrap-select.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/moment.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/currency/currency.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/jquery-mask/jquery.mask.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/jquery.chained.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/jquery.chained.remote.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/easy-autocomplete/jquery.easy-autocomplete.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/select2/js/select2.full.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/input-mask/inputmask.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/highcharts/js/highcharts.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/tipso/src/tipso.min.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/scripts/slidereveal.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/noty/packaged/jquery.noty.packaged.min.js" type="text/javascript" ></script>
        <script src="{{ base_url() }}assets/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
        <script src="{{ base_url() }}assets/plugins/ckeditor/adapters/jquery.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        <script type="text/javascript">    
            
            $(function() {
                $("input[type='search']").focus();
            });
            
            var dinterval;
            //buat object date berdasarkan waktu di server

            var serverTime = new Date(<?php print date('Y, m, d, H, i, s, 0'); ?>);
                
            //buat object date berdasarkan waktu di client
            var clientTime = new Date();
            //hitung selisih
            var Diff = serverTime.getTime() - clientTime.getTime();    
            //fungsi displayTime yang dipanggil di bodyOnLoad dieksekusi tiap 1000ms = 1detik
            function displayServerTime(){
                    //buat object date berdasarkan waktu di client
                    var clientTime = new Date();
                    //buat object date dengan menghitung selisih waktu client dan server
                    var time = new Date(clientTime.getTime() + Diff);
                    
                    //ambil nilai jam
                    var sh = time.getHours().toString();
                    //ambil nilai menit
                    var sm = time.getMinutes().toString();
                    //ambil nilai detik
                    var ss = time.getSeconds().toString();
                    //tampilkan jam:menit:detik dengan menambahkan angka 0 jika angkanya cuma satu digit (0-9)
                    document.getElementById("clock").innerHTML = (sh.length==1?"0"+sh:sh) + ":" + (sm.length==1?"0"+sm:sm) + ":" + (ss.length==1?"0"+ss:ss);
                    
            }
            // 1,000 means 1 second.
            dinterval = setInterval('displayServerTime()', 1000);

            function toggle(){
                var date_param = $('#date_param').val();
                if(date_param == '0'){
                    $('#datetime_navbar').attr('style', 'position: fixed;left: 53px;top: 15px;font-weight: bold;color: #FFF;');
                    $('#date_param').val('1');
                }else{
                    $('#datetime_navbar').attr('style', 'position: fixed;left: 249px;top: 15px;font-weight: bold;color: #FFF;');
                    $('#date_param').val('0');
                }
            }
            
        </script>
        <!-- Reminder -->
        <script type="text/javascript">
            // $("#reminder").slideReveal({
            //   trigger: $("#btn-reminder"),
            //   'position': 'right'
            // }); 

            // $('#scollReminder').slimScroll({
            //     position: 'left',
            //     height: 'auto',
            //     railVisible: true,
            //     alwaysVisible: true
            // });
            
        </script>
        @section('scripts') @show
    </body>

</html>