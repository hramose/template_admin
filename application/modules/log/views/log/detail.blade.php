@extends('default.views.layouts.default')

@section('title') {{ lang('system_name') }} - {{$title}} History Details @stop

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
                <a href="{{ base_url() }}logs">History</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ base_url() }}logs/lists/{{$type}}">{{$title}} History</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>{{$title}} History Details</span>
            </li>
        </ul>

    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title">{{$title}} History Details</h3>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div id="table-wrapper" class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="fa fa-clock-o font-dark"></i>
                        <span class="caption-subject">{{$title}} History Details</span>
                    </div>
                    <div class="tools">
                        <button onClick="return window.location ='{{ base_url()."logs/lists/".$type }}'" class="btn btn-success btn-sm">
                            <i class="fa fa-chevron-left"></i>{{lang('button_back')}} to {{$title}} History
                        </button>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-responsive" style="width: 100%">
                        <tr>
                            <td style="background-color: #32c5d2;font-size: 16px;font-weight: bold; color: #FFF">User</td>
                            <td style="background-color: #32c5d2;font-size: 16px;font-weight: bold; color: #FFF">Level</td>
                            <td style="background-color: #32c5d2;font-size: 16px;font-weight: bold; color: #FFF">Activity</td>
                            <td style="background-color: #32c5d2;font-size: 16px;font-weight: bold; color: #FFF">Date</td>
                            <td style="background-color: #32c5d2;font-size: 16px;font-weight: bold; color: #FFF">Time</td>
                        </tr>
                        <tr>
                            <td>{{ $log->full_name}}</td>
                            <td>{{ $log->description}}</td>
                            <td>{{ $activity}}</td>
                            <td>{{ indonesian_format($log->created_on)}}</td>
                            <td>{{ date('H:i:s', strtotime($log->created_on))}}</td>
                        </tr>
                    </table>

                    <table class="table table-striped table-bordered table-responsive">
                        <tr>
                            <td colspan="4" style="background-color: #32c5d2;font-size: 16px;font-weight: bold; color: #FFF">Description</td>
                        </tr>
                        <tr>
                            <td colspan="4">{{ $log->message}}</td>
                        </tr>
                    </table>

                    <table class="table table-striped table-bordered table-responsive">

                        @if ($log->activity == "C" || $log->activity == "D")
                        @if ($log->activity == "C")
                        <?php $logs = json_decode($log->data_new, TRUE); ?>
                        @elseif ($log->activity == 'D')
                        <?php $logs = json_decode($log->data_old, TRUE); ?>
                        @endif
                        @if (!empty($logs))
                        <tr>
                            <td colspan="4" style="background-color: #32c5d2;font-size: 16px;font-weight: bold; color: #FFF">Data Details</td>
                        </tr>
                        @foreach ($logs as $key => $value)
                        <tr>
                            <td colspan="2" width="50%">{{ $key}}</td>
                            <td colspan="2" width="50%">
                                @if(is_array($value))
                                <table>
                                    @foreach($value as $row => $row_value)
                                    <tr>
                                        <td style="padding: 5px 10px;">{{ $row }}</td>
                                        <td style="padding: 5px 10px;">:</td>
                                        <td style="padding: 5px 10px;">{{ $row_value }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                                @else
                                {{ $value}}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                        @else
                        <?php
                        $logs_new = json_decode($log->data_new, TRUE);
                        $logs_old = json_decode($log->data_old, TRUE);
                        $logs_change = json_decode($log->data_change, TRUE);
                        ?>
                        @if (!empty($logs_new) and ! empty($logs_old))
                        <tr>
                            <td colspan="4" style="background-color: #32c5d2;font-size: 16px;font-weight: bold; color: #FFF">Data Details</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="background-color: #32c5d2;font-size: 16px;font-weight: bold; color: #FFF" width="50%">Old Data</td>
                            <td colspan="2" style="background-color: #32c5d2;font-size: 16px;font-weight: bold; color: #FFF" width="50%">New Data</td>
                        </tr>
                        @foreach ($logs_new as $key => $value)
                        <tr>
                            <td width="25%">{{ $key}}</td>
                            <td width="25%">
                                @if(is_array($value))
                                <table>
                                    @foreach($logs_old[$key] as $row => $row_value)
                                    <tr>
                                        <td style="padding: 5px 10px;">{{ $row }}</td>
                                        <td style="padding: 5px 10px;">:</td>
                                        <td style="padding: 5px 10px;">{{ $row_value }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                                @else
                                {{ $logs_old[$key]}}
                                @endif
                            </td>
                            <td width="25%">{{ $key}}</td>
                            <td width="25%">
                                @if(is_array($value))
                                <table>
                                    @foreach($value as $row => $row_value)
                                    <tr>
                                        <td style="padding: 5px 10px;">{{ $row }}</td>
                                        <td style="padding: 5px 10px;">:</td>
                                        <td style="padding: 5px 10px;">{{ $row_value }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                                @else
                                {{ $value}}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                        @if (!empty($logs_change))
                        <tr>
                            <td colspan="4" style="background-color: #32c5d2;font-size: 16px;font-weight: bold; color: #FFF">Data Change</td>
                        </tr>
                        @foreach ($logs_change as $key => $value)
                        <tr>
                            <td colspan="2" width="50%">{{ $key}}</td>
                            <td colspan="2" width="50%">
                                @if(is_array($value))
                                <table>
                                    @foreach($value as $row => $row_value)
                                    <tr>
                                        <td style="padding: 5px 10px;">{{ $row }}</td>
                                        <td style="padding: 5px 10px;">:</td>
                                        <td style="padding: 5px 10px;">{{ $row_value }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                                @else
                                {{ $value}}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                        @endif
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
            var oTable = $('#table-log').dataTable({
    "bProcessing": true,
            "bServerSide": true,
            "bLengthChange": true,
            "sServerMethod": "GET",
            "sAjaxSource": "{{ base_url() }}logs/fetch-data/{{$type}}",
            /*"aaSorting": [[5, 'desc']],*/
            "aoColumnDefs": [
            {
            "mRender": function (data) {
            var btn_detail = '<a href="{{ base_url() . 'logs / detail / '}}' + data + '" type="button" class="btn btn-primary btn-icon-only btn-circle"><i class="fa fa-eye"></i></a>';
                    var render_html = btn_detail;
                    return render_html;
            },
                    "aTargets": [6]
            },
            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            var page = this.fnPagingInfo().iPage;
                    var length = this.fnPagingInfo().iLength;
                    var index = (page * length + (iDisplayIndex + 1));
                    $('td:eq(0)', nRow).html(index);
            }
    }).fnSetFilteringDelay(1000);

</script>
@stop