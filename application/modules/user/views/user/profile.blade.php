@extends('default.views.layouts.default')

@section('title') {{ lang('system_name') }} - My Profile @stop

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
                <span>My Profile</span>
            </li>
        </ul>

    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title">My Profile</h3>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box blue-dark">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-edit"></i>
                        <span class="caption-subject">Details of My Profile</span>
                    </div>
                    <div class="tools"></div>
                </div>
                <div id="form-wrapper" class="portlet-body">
                    <span class="text-danger">(*) Required</span>
                    {{ form_open(null,array('id' => 'form-user', 'class' => 'form-horizontal', 'autocomplete' => 'off')) }}
                    {{ form_input(array('id' => 'id','name' => 'id','type' => 'hidden'))}}
                    <div class="form-body">
                        <div class="form-group form-md-line-input">
                            <label class="col-md-2 control-label">{{ lang('username') }}<span class="text-danger bold">*</span></label>
                            <div class="col-md-6">
                                {{ form_input('username',set_value('username'),'id="username" class="form-control" placeholder="Username"')}}
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-2 control-label">{{ lang('first_name') }}<span class="text-danger bold">*</span></label>
                            <div class="col-md-6">
                                {{ form_input('first_name',set_value('first_name'),'id="first_name" class="form-control" placeholder="First Name"')}}
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-2 control-label">{{ lang('last_name') }}<span class="text-danger bold">*</span></label>
                            <div class="col-md-6">
                                {{ form_input('last_name',set_value('last_name'),'id="last_name" class="form-control" placeholder="Last Name"')}}
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-2 control-label">{{ lang('email') }}<span class="text-danger bold">*</span></label>
                            <div class="col-md-6">
                                {{ form_input(array('type'=>'email','name'=>'email','value'=>set_value('email'),'id'=>'email','class'=>'form-control'))}}
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-2 control-label">{{ lang('address') }}<span class="text-danger bold">*</span></label>
                            <div class="col-md-6">
                                {{ form_input('address',set_value('address'),'id="address" class="form-control"')}}
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-2 control-label">{{ lang('city') }}<span class="text-danger bold">*</span></label>
                            <div class="col-md-6">
                                {{ form_input(array('type'=>'text','name'=>'city','value'=>set_value('city'),'id'=>'city','class'=>'form-control'))}}
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input">
                            <label class="col-md-2 control-label">{{ lang('phone') }}<span class="text-danger bold">*</span></label>
                            <div class="col-md-6">
                                {{ form_input('phone',set_value('phone'),'id="phone" class="form-control phone"')}}
                                <div class="form-control-focus"> </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-2 col-md-6">
                                <a href="{{ base_url() }}change-password" class="btn default btn-sm"><i class="fa fa-lock"></i>{{lang('button_change_password')}}</a>
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
    // Menampilkan data pada form
    function viewData(value)
    {
        App.blockUI({
            target: '#form-wrapper'
        });
        $.getJSON('{{base_url()}}profile/data', {id: value}, function (json, textStatus) {
            if (json.status == "success") {
                var row = json.data;
                var row_group = json.data_group;
                $('#id').val(row.id);
                $('#username').val(row.username);
                $('#first_name').val(row.first_name);
                $('#last_name').val(row.last_name);
                $('#email').val(row.email);
                $('#address').val(row.address);
                $('#city').val(row.city);
                $('#phone').val(row.phone);
            } else if (json.status == "error") {
                toastr.error('Data not found.', 'Notification!');
            }
            App.unblockUI('#form-wrapper');
        });
    }

    // Pengaturan Form Validation
    var form_validator = $("#form-user").validate({
        errorPlacement: function (error, element) {
            $(element).parent().closest('.form-group').append(error);
        },
        errorElement: "span",
        rules: {
            username: "required",
            first_name: "required",
            last_name: "required",
            email: "required",
            address: "required",
            city: "required",
            phone: "required",
        },
        messages: {
            username: "{{lang('username')}}" + " {{lang('not_empty')}}",
            first_name: "{{lang('first_name')}}" + " {{lang('not_empty')}}",
            last_name: "{{lang('last_name')}}" + " {{lang('not_empty')}}",
            email: "{{lang('email')}}" + " {{lang('not_empty')}}",
            address: "{{lang('address')}}" + " {{lang('not_empty')}}",
            city: "{{lang('city')}}" + " {{lang('not_empty')}}",
            phone: "{{lang('phone')}}" + " {{lang('not_empty')}}",
        },
        submitHandler: function (form) {
            App.blockUI({
                target: '#form-wrapper'
            });
            $(form).ajaxSubmit({
                beforeSubmit: showRequest,
                success: showResponse,
                url: '{{base_url()}}profile/save',
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
                } else if (responseText.status == "error") {

                    toastr.error('{{lang("message_save_failed")}}', 'Notification!');
                } else if (responseText.status == "unique") {

                    toastr.error('{{lang("already_exist")}}', 'Notification!');
                }

                App.unblockUI('#form-wrapper');
                setTimeout(function () {
                    window.location.reload()
                }, 1000);
            }

            return false;
        }
    });

    $(function () {
        $(".phone").inputmask("+6299999999999999");

        viewData();
    });

</script>
@stop