@extends('admin/layout')
@section('content')
    <div class="content-wrapper transition-all duration-150 ltr:ml-[248px] rtl:mr-[248px]" id="content_wrapper">
        <div class="page-content mainBodyNewPadding">
            <div class="transition-all duration-150 container-fluid" id="page_layout">
                <div id="content_layout">

                    <div>
                        <div class="flex justify-between flex-wrap items-center mb-6">
                            <h4
                                class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
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
                                @if (session('status'))
                                <div class="alert py-[18px] px-6 font-normal text-sm rounded-md bg-primary-500 text-white">
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
                        </div>
                        <div class="space-y-8">
                            <div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-5">
                                <div class="bg-no-repeat bg-cover bg-center p-5 rounded-[6px] relative"
                                    style="background-image: url(<?php echo url('public/assets/images/all-img/widget-bg-2.png'); ?>)">
                                    <div class="max-w-[180px]">
                                        <h4 class="text-xl font-medium text-white mb-2">
                                            <span class="block font-normal">
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
                                                ?></span>
                                            <span class="block">Uncruise Admin</span>
                                        </h4>
                                        <p class="text-sm text-white font-normal">
                                            Welcome to admin panel
                                        </p>
                                    </div>
                                </div>
                                <div class="bg-no-repeat bg-cover bg-center px-5 py-8 rounded-[6px] relative flex items-center"
                                    style="background-image: url(<?php echo url('public/assets/images/all-img/widget-bg-6.png'); ?>)">
                                    <div class="flex-1">
                                        <div class="max-w-[180px]">
                                            <h4 class="text-2xl font-medium text-white mb-2">
                                                <span class="block text-sm">Total Users,</span>
                                                <span class="block">
                                                    <?php echo !empty($total_users) ? number_format($total_users) : '0'; ?>
                                                </span>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="flex-none">
                                        <a href="<?php echo route('admin/users'); ?>" class="btn-light bg-white btn-sm btn">View details</a>
                                    </div>
                                </div>
                                <div class="bg-no-repeat bg-cover bg-center px-5 py-8 rounded-[6px] relative flex items-center"
                                    style="background-image: url(<?php echo url('public/assets/images/all-img/widget-bg-7.png'); ?>)">
                                    <div class="flex-1">
                                        <div class="max-w-[180px]">
                                            <h4 class="text-2xl font-medium text-slate-900 mb-2">
                                                <span class="block text-sm dark:text-slate-800">Total Surveys,</span>
                                                <span class="block dark:text-slate-800">
                                                    <?php echo !empty($total_surveys) ? number_format($total_surveys) : '0'; ?>
                                                </span>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="flex-none">
                                        <a href="<?php echo route('admin/surveys'); ?>" class="btn-light bg-white btn-sm btn">View details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
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
@endsection
