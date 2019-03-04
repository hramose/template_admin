@extends('default.views.layouts.default')

@section('title') {{ lang('system_name') }} - {{ lang('change_password') }} @stop

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
                <a href="{{ base_url() }}profile">My Profile</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>{{ lang('change_password') }}</span>
            </li>
        </ul>

    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title">{{ lang('change_password') }}</h3>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box blue-dark">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-edit"></i>
                        <span class="caption-subject">{{ lang('change_password') }}</span>
                    </div>
                    <div class="tools"></div>
                </div>
                <div id="form-wrapper" class="portlet-body">
                    <span class="text-danger">(*) Required</span>
                    {{ form_open(null,array('id' => 'form-pengguna', 'class' => 'form-horizontal')) }}
                    {{ form_input(array('id' => 'id','name' => 'id','type' => 'hidden'))}}
                    <div class="form-body">
                        <div class="form-group form-md-line-input" id="password-pengguna">
                            <label class="col-md-2 control-label">Old Password<span class="text-danger bold">*</span></label>
                            <div class="col-md-6">
                                {{ form_input(array('type'=>'password','name'=>'old_password','value'=>set_value('old_password'),'id'=>'old_password','class'=>'form-control'))}}
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input" id="password-pengguna">
                            <label class="col-md-2 control-label">New Password<span class="text-danger bold">*</span></label>
                            <div class="col-md-6">
                                {{ form_input(array('type'=>'password','name'=>'new_password','value'=>set_value('new_password'),'id'=>'new_password','class'=>'form-control'))}}
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input" id="password-pengguna">
                            <label class="col-md-2 control-label">{{ lang('confirm_password') }}<span class="text-danger bold">*</span></label>
                            <div class="col-md-6">
                                {{ form_input(array('type'=>'password','name'=>'retype_password','value'=>set_value('retype_password'),'id'=>'retype_password','class'=>'form-control'))}}
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-2 col-md-6">
                                <a class="btn default btn-sm" href="{{ base_url() }}profile"><i class="fa fa-chevron-circle-left"></i>{{lang('button_back')}}</a>
                                <button type="submit" class="btn blue btn-sm"><i class="fa fa-save"></i>{{lang('button_insert')}}</button>
                            </div>
                        </div>
                    </div>
                    {{ form_close() }}
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">

    // Pengaturan awal halaman
    toastr.options = {"positionClass": "toast-top-right", };

    // Pengaturan Form Validation
    var form_validator = $("#form-pengguna").validate({
        errorPlacement: function (error, element) {
            $(element).parent().closest('.form-group').append(error);
        },
        errorElement: "span",
        rules: {
            old_password: "required",
            new_password: "required",
            retype_password: "required",
        },
        messages: {
            old_password: "Old Password" + " {{lang('not_empty')}}",
            new_password: "New Password" + " {{lang('not_empty')}}",
            retype_password: "{{lang('confirm_password')}}" + " {{lang('not_empty')}}",
        },
        submitHandler: function (form) {
            App.blockUI({
                target: '#form-wrapper'
            });
            $(form).ajaxSubmit({
                beforeSubmit: showRequest,
                success: showResponse,
                url: '{{base_url()}}change-password/save',
                type: 'POST',
                clearForm: true,
                resetForm: true,
            });
            function showRequest(formData, jqForm, options) {
                var queryString = $.param(formData);
                return true;
            }

            function showResponse(responseText, statusText, xhr, $form) {

                if (responseText.status == "success") {
                    toastr.success('{{lang("message_save_success")}}', 'Notification!');
                } 
                else if (responseText.status == "error") {
                    toastr.error('{{lang("message_save_failed")}}', 'Notification!');
                } 
                else if (responseText.status == "wrong_password") {
                    toastr.error('{{lang("message_wrong_password")}}', 'Notification!');
                }

                App.unblockUI('#form-wrapper');
                setTimeout(function () {
                    window.location.reload()
                }, 1000);
            }

            return false;
        }
    });

</script>
@stop