@extends('default.views.layouts.default')

@section('title') PBM - {{lang('area')}} @stop

@section('body')
<style type="text/css">
    .form-group span.error {
        margin-left: 36% !important;
    }
</style>
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
                <span>{{lang('area')}}</span>
            </li>
        </ul>
        
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title"> {{lang('area')}} </h3>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADERs-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div id="table-wrapper" class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="icon-grid font-dark"></i>
                        <span class="caption-subject">{{lang('area')}}</span>
                    </div>
                    <div class="tools">
                        @if($add_access == 1)
                            <button onclick="add_area()" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>{{lang('new_area')}}</button>
                        @endif

                        @if($print_limited_access == 1 || $print_unlimited_access == 1)
                            <button onClick="return window.open('{{base_url()}}master/area/pdf')" class="btn btn-danger btn-sm">
                                <i class="fa fa-file-pdf-o"></i> Print PDF
                            </button>
                            <button onClick="return window.open('{{base_url()}}master/area/excel')" class="btn btn-success btn-sm">
                                <i class="fa fa-file-excel-o"></i> Print Excel
                            </button>
                        @endif
                    </div>
                </div>
                <div class="portlet-body">
                    <table id="table-area" class="table table-striped table-bordered table-hover dt-responsive" width="100%" >
                        <thead>
                            <tr>
                                <th width="13%"><?=lang('options')?></th>
                                <th><?=lang('area_name')?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLES PORTLET-->
        </div>
    </div>
</div>
 <div class="modal fade" id="modal_form" role="dialog">
  <div class="modal-dialog" style="width:50%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title"><?=lang('new_area')?></h3>
      </div>
      <div class="modal-body">
        {{ form_open(null,array('id' => 'form-area', 'class' => 'form-horizontal', 'autocomplete' => 'off')) }}
        <input type="hidden" name="rowID" value="">
        
        <div class="form-group form-md-line-input">
            <label class="col-lg-4 control-label"><?=lang('area_name')?><span class="text-danger">*</span></label>
            <div class="col-lg-7">
                <input type="text" class="form-control input-sm" name="area_name" id="area_name" placeholder="<?=lang('area_name')?>" maxlength="150" />
                <div class="form-control-focus"> </div>
            </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="submit" id="btnSave"  class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      {{ form_close() }}
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop

@section('scripts')
<script type="text/javascript">
    // Pengaturan awal halaman 
    $('#service_type_wip, #service_type_cogs').select2({
        theme: "bootstrap",
        width: "100%"
    });

    function add_area(){
        $('#form-area')[0].reset(); 
        $('#modal_form').modal('show'); 
        $('.modal-title').text('<?=lang('new_area')?>'); 

        $('[name="rowID"]').val('');
    }
    toastr.options = { "positionClass": "toast-top-right", };

    // Pengaturan Datatable 
    var oTable =$('#table-area').dataTable({
        "bProcessing": true,
        "bServerSide": true,
        "bLengthChange": true,
        "sServerMethod": "GET",
        "sAjaxSource": "{{ base_url() }}area/areas/fetch_data",
        "columnDefs": [
            {"className": "dt-center", "targets": [0]}
          ],
    }).fnSetFilteringDelay(1000);

    // Pengaturan Form Validation 
    var form_validator = $("#form-area").validate({
        errorPlacement: function(error, element) {
            $(element).parent().closest('.form-group').append(error);
        },
        errorElement: "span",
        rules: {
            area_name: "required",
        },
        messages: {
            area_name: "{{lang('area_name')}}" + " {{lang('not_empty')}}",
        },
        submitHandler : function(form){
            App.blockUI({
                target: '#form-wrapper'
            });
            $(form).ajaxSubmit({  
                beforeSubmit:  showRequest,  
                success:       showResponse,
                url:       '{{base_url()}}area/areas/save',      
                type:      'POST',       
                clearForm: true ,       
                resetForm: true ,  
            }); 
            function showRequest(formData, jqForm, options) { 
                var queryString = $.param(formData); 
                return true; 
            } 
            function showResponse(responseText, statusText, xhr, $form)  { 
                if(responseText.status == "success"){
                    toastr.success(responseText.message,'Notification!');
                }else if(responseText.status == "error"){
                    toastr.error(responseText.message,'Notification!');
                }else if(responseText.status == "unique"){
                    toastr.error(responseText.message,'Notification!');
                }

                App.unblockUI('#form-wrapper');
                setTimeout(function(){
                    window.location.reload()
                },1000);
            } 
            return false;
        }
    });
   
    // Menampilkan data pada form
    function viewData(value){   
        form_validator.resetForm();
        $("html, body").animate({
            scrollTop: 0
        }, 500);
        App.blockUI({
            target: '#form-wrapper'
        });
        $.getJSON('{{base_url()}}area/areas/view', {id: value}, function(json, textStatus) {
            if(json.status == "success"){
                var row = json.data;
                $('[name="rowID"]').val(row.rowID);
                $('[name="area_name"]').val(row.area_name);

                $('#modal_form').modal('show');
                $('.modal-title').text('<?=lang('edit_area')?>'); 
            }else if(json.status == "error"){
                toastr.error('Data Not Found.','Notification!');
            }
            App.unblockUI('#form-wrapper');
       });
    }

    // Proses hapus data
    function deleteData(value){
        form_validator.resetForm();
        $("html, body").animate({
            scrollTop: 0
        }, 500);
        $.confirm({
            content : "Delete this data!",
            title : "Are you sure?",
            confirm: function() {

                App.blockUI({
                    target: '#table-wrapper'
                });
                $.getJSON('{{base_url()}}area/areas/delete', {id: value}, function(json, textStatus) {
                    if(json.status == "success"){
                        toastr.success('{{lang("deleted_succesfully")}}','Notification!');
                    }else if(json.status == "error"){
                        toastr.error('{{lang("deleted_unsuccesfully")}}','Notification!');
                    }
                    setTimeout(function(){
                        window.location.reload()
                    },1000);
               });
            },
            cancel: function(button) {
                // nothing to do
            },
            confirmButton: "Yes",
            cancelButton: "No",
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-success",
            dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
        });
    }
</script>
@stop