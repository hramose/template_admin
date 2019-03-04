<html>
    <body>
        <h1 style="text-align: center">Sekolah Tinggi Penerbangan Indonesia</h1>

        <h1>Hello <?php echo $name ?></h1>
        <br/>
        <p>We're responding to your request for your password at <a href="<?php echo base_url() ?>">Semut Merah Warehouse Management System</a>.</p>
        <br/>
        <p>
            If this is correct, please select the link below to create a new password within the next 48 hours:<br/>
            <a href="<?php echo base_url() . "reset-password/" . $forgotten_password_code; ?>">Click this link</a>
        </p>
        <br/>
        <p>
            Please be aware that for security reasons we will delete any saved credit card information stored with your account when you update your password.
        </p>
        <br/>
        <p>Thank you,</p>
        <h2>Semut Merah WMS</h2>
        <a href="<?php echo base_url() ?>">Semut Merah Management System</a>
        <div style="height: 1px;width: 100%;background-color: #666"></div>
        <p>
            This message is a service email related to your use of Semut Merah WMS account.
            For general inquiries or to request support with your Semut Merah WMS account,
        </p>
    </body>
</html>