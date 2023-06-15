<!DOCTYPE html>
<html lang="en" dir="ltr" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <title>404 Not Found | Uncruise - Admin</title>
    <link rel="icon" type="image/png" href="<?php echo !empty($admin_detail->favicon) ? url('public/assets/admin/adminimages/' . $admin_detail->favicon) : url('public/assets/images/logo/favicon.svg'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="<?php echo url('public/assets/css/rt-plugins.css'); ?>">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.0/dist/aos.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css">
    <link rel="stylesheet" href="<?php echo url('public/assets/css/app.css'); ?>">
    <link rel="stylesheet" href="<?php echo url('public/assets/css/style.css'); ?>">
    <script src="<?php echo url('public/assets/js/settings.js'); ?>" sync></script>
</head>

<body class=" font-inter skin-default">

    <div class="min-h-screen flex flex-col justify-center items-center text-center py-20">
        <img src="<?php echo url('public/assets/images/all-img/404.svg'); ?>" id="notFoundImage" alt="">
        <div class="max-w-[546px] mx-auto w-full mt-12">
            <h4 class="text-slate-900 mb-4">Page not found</h4>
            <div class="text-slate-600 dark:text-slate-300 text-base font-normal mb-10">
                The page you are looking for might have been removed, had its name changed or is temporarily unavailable.
            </div>
        </div>
        <div class="max-w-[300px] mx-auto w-full">
            <a href="{{ url()->previous() }}" class="btn btn-dark dark:bg-slate-800 block text-center">
                Return to the back
            </a>
        </div>
    </div>

    <!-- scripts -->
    <script src="<?php echo url('public/assets/js/jquery-3.6.0.min.js'); ?>"></script>
    <script src="<?php echo url('public/assets/js/rt-plugins.js'); ?>"></script>
    <script src="<?php echo url('public/assets/js/app.js'); ?>"></script>
</body>

</html>
