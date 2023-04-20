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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="<?php echo url('public/assets/css/rt-plugins.css'); ?>">
    <link href="https://unpkg.com/aos@2.3.0/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="">
    <link rel="stylesheet" href="<?php echo url('public/assets/css/app.css'); ?>">
    <link rel="stylesheet" href="<?php echo url('public/assets/css/style.css'); ?>">
    <script src="<?php echo url('public/assets/js/settings.js'); ?>" sync></script>
</head>

<body class="font-inter skin-default">
    <div class="loginwrapper">
        <div class="lg-inner-column">
            <div class="left-column relative z-[1] fixedImage">
                <img src="<?php echo url('public/assets/images/auth/cruise-bg-min.webp'); ?>" alt="" class="h-full w-full object-contain">
                {{-- <div class="top-left">
                    <h4>
                        <span class="text-slate-800 dark:text-slate-400 font-bold">
                            Unlock your Project <br> Performance
                        </span>
                    </h4>
                </div> --}}
            </div>
            <div class="right-column relative">
                <div class="inner-content h-full flex flex-col bg-white dark:bg-slate-800">
                    <div class="top-right">                       
                        @if (session('status'))
                            <div class="alert py-[18px] px-6 font-normal text-sm rounded-md bg-danger-500 text-white">
                                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                    <p class="flex-1 font-Inter">
                                        {{ session('status') }}
                                    </p>
                                    <div class="close-icon flex-0 text-xl cursor-pointer">
                                        <iconify-icon icon="line-md:close"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="auth-box h-full flex flex-col justify-center">
                        <div class="text-center 2xl:mb-10 mb-4">
                            <a href="javascript:void(0)" class="centerImg">
                                {{-- <img src="<?php //echo url('public/assets/images/logo/logo.svg'); ?>" alt="" class="mb-10 dark_logo">
                                <img src="<?php echo //url('public/assets/images/logo/logo-white.svg'); ?>" alt="" class="mb-10 white_logo"> --}}
                                <img src="<?php echo !empty($admin_detail->logo) ? url('public/assets/admin/adminimages/' . $admin_detail->logo) : url('public/assets/images/logo/logo.svg'); ?>" alt="">
                            </a>
                            <h4 class="font-medium signInHeading">Sign in</h4>
                            <div class="text-slate-500 text-base">
                                Sign in to your account to start using Admin Panel
                            </div>
                        </div>
                        {{-- START: Login Form --}}
                        <form class="space-y-4" id="common-form" action="<?php echo route('admin/check_login'); ?>" method="POST">
							<div class="error_msg" id="error_msg"></div>
                            <div class="fromGroup">
                                <label class="block capitalize form-label">email</label>
                                <div class="relative">
                                    <input type="email" name="email" id="email" class="form-control py-2" placeholder="example@gmail.com">
                                </div>
                            </div>
                            <div class="fromGroup">
                                <label class="block capitalize form-label">passwrod</label>
                                <div class="relative" id="passwordInputField">
									<input type="password" name="password" id="password" class="form-control py-2 passwordfield text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 pr-9 focus:!outline-none
									focus:!ring-0 border !border-slate-400 rounded-md mt-2" placeholder="password">
									<span
                                        class="text-xl text-slate-400 absolute top-1/2 -translate-y-1/2 right-3 cursor-pointer" id="toggleIcon">
                                        <iconify-icon id="hidePassword" class="passwordeye" icon="heroicons-outline:eye-off"></iconify-icon>
                                        <iconify-icon class="hidden passwordeye" id="showPassword" icon="heroicons-outline:eye"></iconify-icon>
                                    </span>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="hiddens">
                                    <span class="text-slate-500 dark:text-slate-400 text-sm leading-6 capitalize">
                                        Keep me signed in</span>
                                </label>
                                <a class="text-sm text-slate-800 dark:text-slate-400 leading-6 font-medium"
                                    href="<?php echo route('admin/forgot_password'); ?>">Forgot Password?
                                </a>
                            </div>
                            <button type="submit" class="btn btn-dark block w-full text-center">Sign in</button>
                        </form>
                        {{-- END: Login Form --}}
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

    <script>
        const alertBox = document.querySelector('.alert');
        const closeIcon = document.querySelector('.close-icon');
        setTimeout(() => {
            alertBox.classList.add('hide');

            setTimeout(() => {
                alertBox.remove();
            }, 500);
        }, 2000);
        closeIcon.addEventListener('click', () => {
            alertBox.classList.add('hide');

            setTimeout(() => {
                alertBox.remove();
            }, 500);
        });
    </script>
</body>

</html>
