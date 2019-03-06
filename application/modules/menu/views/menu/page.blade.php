@extends('default.views.layouts.default')

@section('title') {{ lang('system_name') }} - {{ lang('menus') }} @stop

@section('body')

<div class="page-wrapper">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <div class="d-inline">
                        <h4>{{ lang('menus') }}</h4>
                        <span>{{ lang('menu_descriptions') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{ base_url() }}"> <i class="feather icon-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">{{ lang('master') }}</a> </li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">{{ lang('menus') }}</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->

    <div class="page-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header table-card-header">
                        @if($add_access == 1)
                            <button onclick="add_menu()" class="btn btn-primary btn-round"><i class="fa fa-plus"></i>{{lang('new_menu')}}</button>
                        @endif
                    </div>
                    <div class="card-block">
                        <div class="dt-responsive table-responsive">
                            <table id="table-menu" class="table table-striped table-bordered table-hover dt-responsive nowrap" width="100%" >
                                <thead>
                                    <tr>
                                        <th width="20%">{{ lang('menu_code') }}</th>
                                        <th>{{ lang('menu_name') }}</th>
                                        <th>{{ lang('menu_parent') }}</th>
                                        <th>{{ lang('menu_link') }}</th>
                                        <th>{{ lang('status') }}</th>
                                        <th width="10%" align="center">{{ lang('options') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_form" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ lang('new_menu') }} </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                {{ form_open(null,array('id' => 'form-menu', 'class' => 'form-horizontal')) }}
                <input type="hidden" name="menu_id" value="" id="menu_id">
        
                <div class="form-group" style="display: none;">
                    <label class="col-lg-4 control-label"><?=lang('menu_code')?> <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control"  name="menu_code" placeholder="Input Menu Code" onkeyup="angka(this)" maxlength="10" value="{{$menu_id}}" autocomplete="off" readonly="" required>
                    </div>
                </div>
    
                 <div class="form-group form-md-line-input">
                    <label class="col-lg-3 control-label">{{lang('menu_name')}}<span class="text-danger bold">*</span></label>
                    <div class="col-lg-8">
                        {{ form_input('menu_name',set_value('menu_name'),'id="menu_name" class="form-control" placeholder="Input Menu Name" autocomplete="off" required')}}
                        <div class="form-control-focus"> </div>
                    </div>
                </div>
    
                <div class="form-group form-md-line-input">
                    <label class="col-lg-3 control-label"><?=lang('menu_link')?><span class="text-danger bold">*</span></label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="menu_link" placeholder="Input Menu Link" value="" autocomplete="off" required>
                        <div class="form-control-focus"> </div>
                    </div>
                </div>

                <div class="form-group form-md-line-input">
                    <label class="col-lg-3 control-label"><?=lang('menu_language')?><span class="text-danger bold">*</span></label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="menu_language" placeholder="Input Menu Language" value="" autocomplete="off" required>
                        <div class="form-control-focus"> </div>
                    </div>
                </div>

               <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('menu_parent')?><span class="text-danger bold">*</span></label>
                    <div class="col-lg-8">
                        <select name="menu_parent" id="parent_menu_id" class="form-control" required>
                            <option value="0">- Parent -</option>
                            @if (!empty($parent_menus)) 
                                @foreach ($parent_menus as $parent) 
                                    <option value="{{$parent->menu_id}}">{{$parent->menu_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('status')?><span class="text-danger bold">*</span></label>
                    <div class="col-lg-8">
                        <input type="checkbox" name="status" class="form-control" id="chk_status" checked>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">{{ lang('close') }}</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">{{ lang('save') }}</button>
            </div>
            {{ form_close() }}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop

@section('scripts')
<script type="text/javascript">
    // Pengaturan awal halaman 
     $("#chk_status").bootstrapSwitch({
        'onText' : 'Active',
        'offText' : 'Not',
    });
    
    $('#parent_menu_id').select2({
        theme: "bootstrap",
        width: "100%"
    });

    function add_menu(){
      $('#form-menu')[0].reset(); 
      $('#modal_form').modal('show'); 
      $('.modal-title').text('<?=lang('new_menu')?>'); 
      $("#parent_menu_id").val('').change();
      $('[name="menu_id"]').val('');
      $("#chk_status").bootstrapSwitch('state',true);
    }

    toastr.options = { "positionClass": "toast-top-right", };

    // Pengaturan Datatable 
    var oTable =$('#table-menu').dataTable({
        "dom": 'Bfrtip',
        "buttons": ['copy', 'csv', 'excel', 'pdf', 'print'],
        "bProcessing": true,
        "bServerSide": true,
        "bLengthChange": true,
        "bSort":true,
        "bFilter": true,
        "saveState":true,
        "sServerMethod": "GET",
        "sAjaxSource": "{{ base_url() }}master/menu/fetch-data",
        "columnDefs": [
            {"className": "text-center", "targets": [4, 5]}
        ],
        // "order": [[0,"asc"],[1,"asc"]],
        // "orderFixed": [ 0, 'asc' ],
        // "aaSorting": [[ 2, "desc" ]],
        
    }).fnSetFilteringDelay(1000);
    // oTable.fnSort([
    //     [2, 'desc']
    // ]);

    // Pengaturan Form Validation 
    var form_validator = $("#form-menu").validate({
        errorPlacement: function(error, element) {
            $(element).parent().closest('.form-group').append(error);
        },
        errorElement: "span",
        rules: {
            menu_name: "required",
            menu_link: "required",
            menu_language: "required",
            menu_parent: "required",
        },
        messages: {
            menu_name: "{{lang('menu_name')}}" + " {{lang('not_empty')}}",
            menu_link: "{{lang('menu_link')}}" + " {{lang('not_empty')}}",
            menu_language: "{{lang('menu_language')}}" + " {{lang('not_empty')}}",
            menu_parent: "{{lang('menu_parent')}}" + " {{lang('not_empty')}}",
        },
        submitHandler : function(form){
            // App.blockUI({
            //     target: '#form-wrapper'
            // });
            $(form).ajaxSubmit({  
                beforeSubmit:  showRequest,  
                success:       showResponse,
                url:       '{{base_url()}}master/menu/save',      
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
                    toastr.success('{{lang("message_save_success")}}','Notification!');
                }else if(responseText.status == "error"){
                    toastr.error('{{lang("message_save_failed")}}','Notification!');
                }else if(responseText.status == "unique"){
                    toastr.error('{{lang("already_exist")}}','Notification!');
                }

                // App.unblockUI('#form-wrapper');
                setTimeout(function(){
                    window.location.reload()
                },1000);
            } 
            return false;
        }
    });

    function saveOrderMenu(id){
        $('#view_'+id).show();
        $('#input_'+id).hide();
        $('#link_'+id).show();
        $('#save_'+id).hide();

        $.confirm({
            content : "Are you want to Save?",
            title : "{{ lang('warning') }}",
            confirm: function() {

                // App.blockUI({
                //     target: '#table-wrapper'
                // });

                $.getJSON('{{base_url()}}master/menu/update', {
                    menu_id: id, menu_code: $('#menu_code_'+id).val()
                }, function(json, textStatus) {
                    if(json.status == "success"){
                        toastr.success('{{lang("updated_succesfully")}}','Notification!');
                        // el.closest('tr').remove();
                    }else if(json.status == "error"){
                        toastr.error('{{lang("updated_unsuccesfully")}}','Notification!');
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
            confirmButtonClass: "btn-success",
            cancelButtonClass: "btn-danger",
            dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
        });
    }

    // Menampilkan data pada form
    function viewData(value){   
        form_validator.resetForm();
        $("html, body").animate({
            scrollTop: 0
        }, 500);
        $('#row-kategori-toko').show();
        // App.blockUI({
        //     target: '#form-wrapper'
        // });
        $.getJSON('{{base_url()}}master/menu/view', {id: value}, function(json, textStatus) {
            if(json.status == "success"){
                var row = json.data;
                $('[name="menu_id"]').val(row.menu_id);
                $('[name="menu_code"]').val(row.menu_code);
                $('[name="menu_name"]').val(row.menu_name);
                $('[name="menu_link"]').val(row.menu_link);
                $('[name="menu_language"]').val(row.lang);
                $("#parent_menu_id").val(row.parent_menu_id).change();
                
                var status_value = true;
                if(row.status == '0'){
                    status_value = false;
                }
                $("#chk_status").bootstrapSwitch('state',status_value);

                $('#modal_form').modal('show');
                $('.modal-title').text('Edit Menu'); 
            }else if(json.status == "error"){
                toastr.error('Data not found.','Notification!');
            }
            // App.unblockUI('#form-wrapper');
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

                // App.blockUI({
                //     target: '#table-wrapper'
                // });

                $.getJSON('{{base_url()}}master/menu/delete', {id: value}, function(json, textStatus) {
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
    
    function changeOrderMenu(id){
        $('#view_'+id).hide();
        $('#input_'+id).show();
        $('#link_'+id).hide();
        $('#save_'+id).show();
    }

</script>
@stop