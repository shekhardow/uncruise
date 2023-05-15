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
        <div>
            {{-- <img src="" height="50" /> --}}
        </div>

        <p style="margin-top: 10px">Hi <?php echo $name; ?>,</p>

        <p style="margin-top: 30px">Your verification OTP is <?php echo $otp; ?></p>

        <p style="margin-top: 40px">If you have any questions or need any help with this, please contact</p>
        <p style="margin-top: 40px"><a href="mail:admin@ultimatepropertydashboard.com">admin@seriousdating.com</a></p>

        <p style="margin-top: 40px">
            Regards, <br />
            The Serious Dating App Team <br />
            Your Insights and Leads Delivered
        </p>
        <div class="footer">
            <p>© <?php echo date('Y'); ?> Serious Dating App LTD. All Rights Reserved.</p>
            <p>G 130 FIRST FLOAR SECTOR 63, NOIDA</p>
        </div>
    </div>
</body>

</html>
