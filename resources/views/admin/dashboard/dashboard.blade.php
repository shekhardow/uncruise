@extends('admin/layout')
@section('content')
    <div class="content-wrapper transition-all duration-150 ltr:ml-[248px] rtl:mr-[248px]" id="content_wrapper">
        <div class="page-content mainBodyNewPadding">
            <div class="transition-all duration-150 container-fluid" id="page_layout">
                <div id="content_layout">

                    <div>
                        <div class="flex justify-between flex-wrap items-center mb-6">
                            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
                                Dashboard
                            </h4>
                            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                                {{-- <button class="btn leading-0 inline-flex justify-center bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300 !font-normal">
                                    <span class="flex items-center">
                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="heroicons-outline:calendar"></iconify-icon>
                                        <span>Weekly</span>
                                    </span>
                                </button>
                                <button class="btn leading-0 inline-flex justify-center bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300 !font-normal">
                                    <span class="flex items-center">
                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="heroicons-outline:filter"></iconify-icon>
                                        <span>Select Date</span>
                                    </span>
                                </button> --}}
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div class="card p-6">
                                <div class="grid xl:grid-cols-4 lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5 place-content-center">

                                    <div class="xl:col-span-8 col-span-12">
                                        <div class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-3">

                                            <div class="rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-50">
                                                <div class="flex space-x-4 h-full items-center rtl:space-x-reverse">
                                                    <div class="flex-none">
                                                        <div class="h-20 w-20 rounded-full">
                                                            <img src="<?php echo !empty($admin_detail->profile_pic) ? url('public/assets/admin/adminimages/' . $admin_detail->profile_pic) : url('public/assets/images/all-img/main-user.png'); ?>" alt="" class="w-full h-full profile-image">
                                                        </div>
                                                    </div>
                                                    <div class="flex-1">
                                                        <h4 class="text-xl font-medium mb-2">
                                                            <span class="block font-light">
                                                                <?php
                                                                date_default_timezone_set('Asia/Kolkata');
                                                                $h = date('G');
                                                                if ($h >= 5 && $h <= 11) {
                                                                    echo 'Good Morning,';
                                                                } elseif ($h >= 12 && $h <= 15) {
                                                                    echo 'Good Afternoon,';
                                                                } else {
                                                                    echo 'Good Evening,';
                                                                }
                                                                ?>
                                                            </span>
                                                            <span class="block"><?php echo !empty($admin_detail->name) ? $admin_detail->name : 'User'; ?></span>
                                                        </h4>
                                                        <p class="text-sm dark:text-slate-300">Welcome to Admin Panel</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <a href="<?php echo route('admin/users'); ?>">
                                                <div class=" bg-info-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-50 text-center">
                                                    <div class="text-info-500 mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full bg-white text-2xl mb-4">
                                                        {{-- <iconify-icon icon="heroicons-outline:menu-alt-1"></iconify-icon> --}}
                                                        <iconify-icon icon="heroicons-outline:user-group"></iconify-icon>
                                                    </div>
                                                    <span class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                                                        Total Users
                                                    </span>
                                                    <span class="block mb- text-2xl text-slate-900 dark:text-white font-medium">
                                                        <?php echo !empty($total_users) ? number_format($total_users) : '0'; ?>
                                                    </span>
                                                </div>
                                            </a>

                                            <a href="<?php echo route('admin/cruise'); ?>">
                                                <div class=" bg-warning-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-50 text-center">
                                                    <div class="text-warning-500 mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full bg-white text-2xl mb-4">
                                                        <iconify-icon icon="tabler:ship"></iconify-icon>
                                                    </div>
                                                    <span class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                                                        Cruise
                                                    </span>
                                                    <span class="block mb- text-2xl text-slate-900 dark:text-white font-medium">
                                                        <?php echo !empty($total_surveys) ? number_format($total_surveys) : '0'; ?>
                                                    </span>
                                                </div>
                                            </a>

                                            <a href="<?php echo route('admin/destinations'); ?>">
                                                <div class=" bg-primary-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-50 text-center">
                                                    <div class="text-primary-500 mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full bg-white text-2xl mb-4">
                                                        <iconify-icon icon="material-symbols:location-on-outline"></iconify-icon>
                                                    </div>
                                                    <span class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                                                        Destinations
                                                    </span>
                                                    <span class="block mb- text-2xl text-slate-900 dark:text-white font-medium">
                                                        <?php echo !empty($blockedusers) ? number_format($blockedusers) : '0'; ?>
                                                    </span>
                                                </div>
                                            </a>

                                            <a href="<?php echo route('admin/adventures'); ?>">
                                                <div class="greenColor rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-50 text-center">
                                                    <div class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full bg-white text-2xl mb-4">
                                                        <iconify-icon icon="gis:earth-atlantic-o" class="textGreen"></iconify-icon>
                                                    </div>
                                                    <span class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                                                        Adventures
                                                    </span>
                                                    <span class="block mb- text-2xl text-slate-900 dark:text-white font-medium">
                                                        <?php echo !empty($blockedusers) ? number_format($blockedusers) : '0'; ?>
                                                    </span>
                                                </div>
                                            </a>

                                            <a href="<?php echo route('admin/journeys'); ?>">
                                                <div class="blueColor rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-50 text-center">
                                                    <div class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full bg-white text-2xl mb-4">
                                                        <iconify-icon icon="ic:outline-travel-explore" class="textBlue"></iconify-icon>
                                                    </div>
                                                    <span class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                                                        Journeys
                                                    </span>
                                                    <span class="block mb- text-2xl text-slate-900 dark:text-white font-medium">
                                                        <?php echo !empty($blockedusers) ? number_format($blockedusers) : '0'; ?>
                                                    </span>
                                                </div>
                                            </a>

                                            <a href="<?php echo route('admin/reviews'); ?>">
                                                <div class="bg-info-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-50 text-center">
                                                    <div class="text-info-500 mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full bg-white text-2xl mb-4">
                                                        <iconify-icon icon="material-symbols:rate-review-outline-rounded"></iconify-icon>
                                                    </div>
                                                    <span class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                                                        Reviews
                                                    </span>
                                                    <span class="block mb- text-2xl text-slate-900 dark:text-white font-medium">
                                                        <?php echo !empty($total_users) ? number_format($total_users) : '0'; ?>
                                                    </span>
                                                </div>
                                            </a>
                                            
                                            <a href="<?php echo route('admin/testimonials'); ?>">
                                                <div class="bg-warning-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-50 text-center">
                                                    <div class="text-warning-500 mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full bg-white text-2xl mb-4">
                                                        <iconify-icon icon="bi:chat-quote"></iconify-icon>
                                                    </div>
                                                    <span class="block text-sm text-slate-600 font-medium dark:text-white mb-1">
                                                        Testimonials
                                                    </span>
                                                    <span class="block mb- text-2xl text-slate-900 dark:text-white font-medium">
                                                        <?php echo !empty($total_surveys) ? number_format($total_surveys) : '0'; ?>
                                                    </span>
                                                </div>
                                            </a>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
