<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
        }
        a {
            color: #3787e3;
        }
        .container {
            max-width: 700px;
            margin: 50px 0;
            padding: 50px;
            border-radius: 10px;
            border: 1px solid #0003;
        }
        .activate-btn {
            padding: 12px 16px;
            background-color: #3787e3;
            border-radius: 6px;
            text-decoration: none;
            color: #fff;
        }
        .footer {
            margin-top: 70px;
        }
        .footer p {
            margin: 0 0 2px;
            text-align: center;
            font-size: 12px;
        }
        .ii a[href] {
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <p style="margin-top: 10px">Hi <?php echo !empty($name) ? $name : null; ?>,</p>
        <p style="margin-top: 30px"><?php echo !empty($message) ? $message : null; ?></p>
        <p style="margin-top: 40px">
            Regards, <br />
            The UnCruise Adventures Team
        </p>
        <div class="footer">
            <p>Copyright © <?php echo date('Y'); ?> UnCruise Adventures, All Rights Reserved</p>
            <p><?php echo !empty($address) ? $address : null; ?></p>
        </div>
    </div>
</body>

</html>
