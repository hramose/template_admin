@extends('default.views.layouts.default')

@section('title') {{ lang('system_name') }} - Dashboard @stop

@section('body')

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
   
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{ base_url() }}">Dashboard</a>
            </li>
        </ul>
        
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title">Dashboard</h3>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="fa fa-bars font-dark"></i>
                        <span class="caption-subject"></span>
                    </div>
                    <div class="tools"></div>
                </div>
                <div class="portlet-body">
                  
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">

</script>
@stop