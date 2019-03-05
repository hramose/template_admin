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
                    <div class="card-block">
                        <div class="dt-responsive table-responsive">
                            <table id="table-menu" class="table table-striped table-bordered table-hover dt-responsive nowrap" width="100%" >
                                <thead>
                                    <tr>
                                        <th width="10%">{{ lang('options') }}</th>
                                        <th width="14%">{{ lang('menu_code') }}</th>
                                        <th>{{ lang('menu_name') }}</th>
                                        <th>{{ lang('menu_parent') }}</th>
                                        <th>{{ lang('menu_link') }}</th>
                                        <th>{{ lang('status') }}</th>
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
        "bProcessing": true,
        "bServerSide": true,
        "bLengthChange": true,
        "bSort":true,
        "bFilter": true,
        "saveState":true,
        "sServerMethod": "GET",
        "sAjaxSource": "{{ base_url() }}master/menu/fetch-data",
        "columnDefs": [
            {"className": "dt-center", "targets": [0, 5]}
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
            App.blockUI({
                target: '#form-wrapper'
            });
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

                App.unblockUI('#form-wrapper');
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
            title : "Peringatan!",
            confirm: function() {

                App.blockUI({
                    target: '#table-wrapper'
                });

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
        App.blockUI({
            target: '#form-wrapper'
        });
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