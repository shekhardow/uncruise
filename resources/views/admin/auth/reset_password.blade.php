<!DOCTYPE html>
<html lang="en" dir="ltr" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <title><?php echo !empty($title) ? $title . ' | ' : null; ?>Uncruise - Admin</title>
    <link rel="icon" type="image/png" href="<?php echo !empty($admin_detail->favicon) ? url('public/assets/admin/adminimages/' . $admin_detail->favicon) : url('public/assets/images/logo/favicon.svg'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo url('public/assets/css/rt-plugins.css'); ?>">
    <link href="https://unpkg.com/aos@2.3.0/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="">
    <link rel="stylesheet" href="<?php echo url('public/assets/css/app.css'); ?>">
    <link rel="stylesheet" href="<?php echo url('public/assets/css/style.css'); ?>">
    <script src="<?php echo url('public/assets/js/settings.js'); ?>" sync></script>
</head>

<body class=" font-inter skin-default">
    <div class="loginwrapper" id="">
        <div class="lg-inner-column">
            <div class="left-column relative z-[1] fixedImage">
                <img src="<?php echo url('public/assets/images/auth/cruise-bg-min.webp'); ?>" alt="" class="h-full w-full object-contain">
            </div>
            <div class="right-column relative">
                <div class="inner-content h-full flex flex-col bg-white dark:bg-slate-800">
                    <div class="auth-box2 flex flex-col justify-center h-full">
                        <div class="text-center 2xl:mb-10 mb-5">
                            <a href="javascript:void(0)" class="centerImg">
                                {{-- <img src="<?php //echo url('public/assets/images/logo/logo.svg'); ?>" alt="" class="mb-10 dark_logo">
                                <img src="<?php //echo url('public/assets/images/logo/logo-white.svg'); ?>" alt="" class="mb-10 white_logo"> --}}
                                <img src="<?php echo !empty($admin_detail->logo) ? url('public/assets/admin/adminimages/' . $admin_detail->logo) : url('public/assets/images/logo/logo.svg'); ?>" alt="">
                            </a>
                            <h4 class="font-medium mb-4 signInHeading">Reset password</h4>
                        </div>
                        {{-- START: Forgot Password Form --}}
                        <form class="space-y-4" id="common-form" action="<?php echo route('admin/sendPasswordResetOtp'); ?>" method="POST">
                            <div class="error_msg" id="error_msg"></div>
                            <div class="form-group">
                                <label class="block capitalize form-label">New Password*</label>
                                <input type="password" name="email" class="form-control py-2" placeholder="Create new password">
                            </div>
                            <div class="form-group">
                                <label class="block capitalize form-label">Confirm Password*</label>
                                <input type="password" name="email" class="form-control py-2" placeholder="Re-Enter new password">
                            </div>                         
                            <div class="form-group">
                                <label class="block capitalize form-label">OTP*</label>
                                <input type="number" name="email" class="form-control py-2" placeholder="Verify with OTP">
                            </div>
                            <button type="submit" class="btn btn-dark block w-full text-center">Send recovery email</button>
                        </form>
                        {{-- END: Forgot Password Form --}}

                        <div class="md:max-w-[345px] mx-auto font-normal text-slate-500 dark:text-slate-400 2xl:mt-12 mt-8 uppercase text-sm">
                            Forget It,
                            <a href="<?php echo route('admin/login'); ?>" class="text-slate-900 dark:text-white font-medium hover:underline">
                                Send me Back
                            </a>
                            to The Sign In
                        </div>
                    </div>
                    <div class="auth-footer text-center">
                        Copyright © <span id="thisYear"></span> UnCruise Adventures, All rights Reserved
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- scripts -->
    <script src="<?php echo url('public/assets/js/jquery-3.6.0.min.js'); ?>"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="<?php echo url('public/assets/js/rt-plugins.js'); ?>"></script>
    <script src="<?php echo url('public/assets/js/app.js'); ?>"></script>
	<script src="<?php echo url('public/assets/admin/event.js')?>"></script>
</body>

</html>