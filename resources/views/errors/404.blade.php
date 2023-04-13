@extends('admin/layout')
@section('content')
    <div class="content-wrapper transition-all duration-150 ltr:ml-[248px] rtl:mr-[248px]" id="content_wrapper">
        <div class="page-content mainBodyNewPadding">
            <div class="transition-all duration-150 container-fluid" id="page_layout">
                <div id="content_layout">
                    <div class="min-h-screen flex flex-col justify-center items-center text-center py-20">
                        <img src="<?php echo url('public/assets/images/all-img/404.svg'); ?>" alt="">
                        <div class="max-w-[546px] mx-auto w-full mt-12">
                            <h4 class="text-slate-900 mb-4">Page not found</h4>
                            <div class="text-slate-600 dark:text-slate-300 text-base font-normal mb-10">
                                The page you are looking for might have been removed had its name changed or is temporarily
                                unavailable.
                            </div>
                        </div>
                        <div class="max-w-[300px] mx-auto w-full">
                            <a href="<?php echo route('admin/dashboard'); ?>" class="btn btn-dark dark:bg-slate-800 block text-center">
                                Go to homepage
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
