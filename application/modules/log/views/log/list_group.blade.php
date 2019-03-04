@extends('default.views.layouts.default')

@section('title') {{ lang('system_name') }} - History @stop

@section('body')

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
   
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{ base_url() }}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>History</span>
            </li>
        </ul>
        
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title">History</h3>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div id="table-wrapper" class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="fa fa-clock-o font-dark"></i>
                        <span class="caption-subject">History</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table id="table-log" class="table table-striped table-bordered table-hover dt-responsive" width="100%" >
                        <thead>
                            <tr>
                                <th style="width:50px;">No</th>
                                <th>Menu</th>
                                <th>Last Activity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">

    // Pengaturan Datatable 
    var oTable =$('#table-log').dataTable({
        "bProcessing": true,
        "bServerSide": true,
        "bLengthChange": true,
        "sServerMethod": "GET",
        "sAjaxSource": "{{ base_url() }}logs/fetch-data-group",
        /*"aaSorting": [[5, 'desc']],*/
        "aoColumnDefs": [
           {
                "mRender": function (data) {
                    var btn_detail = '<a href="{{ base_url() . 'logs/lists/'}}' + data +'" type="button" class="btn btn-primary btn-icon-only btn-circle"><i class="fa fa-list"></i></a>';
                    var render_html = btn_detail;
                    return render_html;
                },
                "aTargets": [3]
            },
        ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
             var page = this.fnPagingInfo().iPage;
             var length = this.fnPagingInfo().iLength;
             var index = (page * length + (iDisplayIndex +1));
             $('td:eq(0)', nRow).html(index);
        }
    }).fnSetFilteringDelay(1000);   
    
</script>
@stop