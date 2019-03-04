@extends('default.views.layouts.default')

@section('title') {{ lang('system_name') }} - {{lang('usermenus')}} @stop

@section('body')
<style type="text/css">
    .form-group span.error {
        margin-left: 39% !important;
    }
    #uniform-chk_status{
        display: none !important;
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
                <span>{{lang('usermenus')}}</span>
            </li>
        </ul>
        
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title"> {{lang('usermenus')}} </h3>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div id="table-wrapper" class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="icon-grid font-dark"></i>
                        <span class="caption-subject">{{lang('usermenus')}}</span>
                    </div>
                    <div class="tools"> 
                        <button onClick="return window.location ='{{base_url()}}master/users/account'" class="btn btn-success btn-sm">
                            <i class="fa fa-arrow-left"></i>  <?=ucwords(lang('back'))?>
                        </button>
                        <button id="add-new" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i>{{lang('new_usermenu')}}
                        </button>
                    </div>
                </div>
                <div class="portlet-body">
                    <table id="table-user-menu" class="table table-striped table-bordered table-hover dt-responsive" width="100%" >
                        <thead>
                            <tr>
                                <th width="10%"><?=lang('options')?></th>
                                <th><?=lang('menu_name')?> </th>
                                <th><?=lang('menu_parent')?> </th>
                                <th><?=lang('status')?> </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
</div>

 <!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog" style="width:600px;height:200px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h3 class="modal-title"><?=lang('new_usermenu')?></h3>
            </div>

            {{ form_open(null,array('id' => 'form-user-menu', 'class' => 'form-horizontal', 'autocomplete' => 'off')) }}
            <div class="modal-body">
                <input type="hidden" name="rowID" value="">
                <input type="hidden" name="user_id" value="<?=$id_user?>">
                <input type="hidden" name="menu_code_tmp" value="">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?=lang('menu')?><span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="menu_code" id="cmb_menu_code" class="form-control" required>
                                    <?php
                                        if (!empty($menus)) {
                                            foreach ($menus as $menu) {
                                    ?>
                                        <option value="<?=$menu->menu_id?>"><?=$menu->menu_name?></option>
                                    <?php 
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('availabled')?><span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="availabled" id="availabled" class="form-control" required>
                                    <option value="1"><?=lang('yes')?></option>
                                    <option value="0"><?=lang('no')?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('created')?><span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="created" id="created" class="form-control" required>
                                    <option value="1"><?=lang('yes')?></option>
                                    <option value="0"><?=lang('no')?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('viewed')?><span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="viewed" id="viewed" class="form-control" required>
                                    <option value="1"><?=lang('yes')?></option>
                                    <option value="0"><?=lang('no')?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('updated')?><span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="updated" id="updated" class="form-control" required>
                                    <option value="1"><?=lang('yes')?></option>
                                    <option value="0"><?=lang('no')?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('deleted')?><span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="deleted" id="deleted" class="form-control" required>
                                    <option value="1"><?=lang('yes')?></option>
                                    <option value="0"><?=lang('no')?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('approved')?><span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="approved" id="approved" class="form-control" required>
                                    <option value="1"><?=lang('yes')?></option>
                                    <option value="0"><?=lang('no')?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('verified')?><span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="verified" id="verified" class="form-control" required>
                                    <option value="1"><?=lang('yes')?></option>
                                    <option value="0"><?=lang('no')?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('fullaccess')?><span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="fullaccess" id="fullaccess" class="form-control" required>
                                    <option value="1"><?=lang('yes')?></option>
                                    <option value="0"><?=lang('no')?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('printlimited')?><span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="printlimited" id="printlimited" class="form-control" required>
                                    <option value="1"><?=lang('yes')?></option>
                                    <option value="0"><?=lang('no')?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('printunlimited')?><span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="printunlimited" id="printunlimited" class="form-control" required>
                                    <option value="1"><?=lang('yes')?></option>
                                    <option value="0"><?=lang('no')?></option>
                                </select>
                            </div>
                        </div>  
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label"><?=lang('status')?><span class="text-danger">*</span></label>
                    <div class="col-lg-4">
                        <input type="checkbox" name="status" class="form-control"  id="chk_status" checked>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="submit" id="btnSave" class="btn btn-primary">Save</button>
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
     $("#chk_status").bootstrapSwitch({
        'onText' : 'Active',
        'offText' : 'Not',
    });
    $('#cmb_menu_code').select2({
        theme: "bootstrap",
        width: "100%"
    });
    toastr.options = { "positionClass": "toast-top-right", };

    $('#add-new').click(function(e){
        $('#form-user-menu')[0].reset(); 
        $('#modal_form').modal('show'); 
        $('.modal-title').text('<?=lang('new_usermenu')?>'); 

        $('[name="rowID"]').val('');
        $('[name="menu_code"]').val('').change();
        $('[name="menu_code_tmp"]').val('');
    });

    // Pengaturan Datatable 
    var oTable =$('#table-user-menu').dataTable({
        "bProcessing": true,
        "bServerSide": true,
        "bLengthChange": true,
        "sServerMethod": "GET",
        "sAjaxSource": "{{ base_url() }}master/users/usermenu/fetch-data/{{$id_user}}",
        "columnDefs": [
            {"className": "dt-center", "targets": [0, 3]}
          ],
    }).fnSetFilteringDelay(1000);

    // Pengaturan Form Validation 
    var form_validator = $("#form-user-menu").validate({
        errorPlacement: function(error, element) {
            $(element).parent().closest('.form-group').append(error);
        },
        errorElement: "span",
        rules: {
            menu_code: "required"
        },
        messages: {
            menu_code: "{{lang('menu')}}" + " {{lang('not_empty')}}"
        },
        submitHandler : function(form){
            App.blockUI({
                target: '#form-wrapper'
            });
            $(form).ajaxSubmit({  
                beforeSubmit:  showRequest,  
                success:       showResponse,
                url:       '{{base_url()}}user/users_menu/save',      
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
    function edit_usermenu(value){   
        form_validator.resetForm();
        $("html, body").animate({
            scrollTop: 0
        }, 500);
        App.blockUI({
            target: '#form-wrapper'
        });
        $.getJSON('{{base_url()}}user/users_menu/view', {id: value}, function(json, textStatus) {
            if(json.status == "success"){
                var row = json.data;
                $('[name="rowID"]').val(row.id_user_menu);
                $('[name="menu_code_tmp"]').val(row.menu_id);
                $('[name="menu_code"]').val(row.menu_id).change();
                $('[name="availabled"]').val(row.Availabled);
                $('[name="created"]').val(row.Created);
                $('[name="viewed"]').val(row.Viewed);
                $('[name="updated"]').val(row.Updated);
                $('[name="deleted"]').val(row.Deleted);
                $('[name="approved"]').val(row.Approved);
                $('[name="verified"]').val(row.Verified);
                $('[name="fullaccess"]').val(row.FullAccess);
                $('[name="printlimited"]').val(row.PrintLimited);
                $('[name="printunlimited"]').val(row.PrintUnlimited);

                var status_value = true;
                if(row.StatusUsermenu == '0'){
                    status_value = false;
                }
                $("#chk_status").bootstrapSwitch('state',status_value);

                $('#modal_form').modal('show'); 
                $('.modal-title').text('Edit User Menu'); 
            }else if(json.status == "error"){
                toastr.error('Data tidak ditemukan.','Notification!');
            }
            App.unblockUI('#form-wrapper');
       });
    }

    // Proses hapus data
    function delete_usermenu(value){
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
                $.getJSON('{{base_url()}}user/users_menu/delete', {id: value}, function(json, textStatus) {
                    if(json.status == "success"){
                        toastr.success('Data has been deleted.','Notification!');
                    }else if(json.status == "error"){
                        toastr.error('Delete user menu failed','Notification!');
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