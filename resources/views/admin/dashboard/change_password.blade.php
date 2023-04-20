@extends('admin/layout')
@section('content')
    <div class="content-wrapper transition-all duration-150 ltr:ml-[248px] rtl:mr-[248px]" id="content_wrapper">
        <div class="page-content mainBodyNewPadding">
            <div class="transition-all duration-150 container-fluid" id="page_layout">
                <div id="content_layout">
                    <!-- BEGIN: Breadcrumb -->
                    <div class="mb-5">
                        <ul class="m-0 p-0 list-none">
                            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter">
                                <a href="<?php echo route('admin/dashboard'); ?>">
                                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                </a>
                            </li>
                            <li class="inline-block relative text-sm text-primary-500 font-Inter">
                                Password
                                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                            </li>
                            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                                Change Password
                            </li>
                        </ul>
                    </div>
                    <!-- END: BreadCrumb -->

                    <div class="space-y-5">
                        <div class="card">
                            <div class="card-body p-6">
                                <form method="post" id="submit-form" action="<?php echo route('admin/updatePassword'); ?>" autocomplete="off">
                                    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
                                        <div class="form-group input-area">
                                            <label for="old_password" class="form-label">Old Password* :</label>
                                            <div class="relative" id="passwordInputField">
                                                <input type="password" name="old_password" id="old_password" class="form-control py-2 passwordfield text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 pr-9 focus:!outline-none
                                                focus:!ring-0 border !border-slate-400 rounded-md mt-2" placeholder="Old Password">
                                                <span class="text-xl text-slate-400 absolute top-1/2 -translate-y-1/2 right-3 cursor-pointer" id="toggleIcon">
                                                    <iconify-icon id="hidePassword" class="passwordeye" icon="heroicons-outline:eye-off"></iconify-icon>
                                                    <iconify-icon class="hidden passwordeye" id="showPassword" icon="heroicons-outline:eye"></iconify-icon>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group input-area">
                                            <label for="new_password" class="form-label">New Password* :</label>
                                            <div class="relative" id="passwordInputField">
                                                <input type="password" name="new_password" id="new_password" class="form-control py-2 passwordfield text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 pr-9 focus:!outline-none
                                                focus:!ring-0 border !border-slate-400 rounded-md mt-2" placeholder="New Password">
                                                <span class="text-xl text-slate-400 absolute top-1/2 -translate-y-1/2 right-3 cursor-pointer" id="toggleIcon">
                                                    <iconify-icon id="hidePassword" class="passwordeye" icon="heroicons-outline:eye-off"></iconify-icon>
                                                    <iconify-icon class="hidden passwordeye" id="showPassword" icon="heroicons-outline:eye"></iconify-icon>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group input-area">
                                            <label for="confirm_password" class="form-label">Confirm Password* :</label>
                                            <div class="relative" id="passwordInputField">
                                                <input type="password" name="confirm_password" id="confirm_password" class="form-control py-2 passwordfield text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 pr-9 focus:!outline-none
                                                focus:!ring-0 border !border-slate-400 rounded-md mt-2" placeholder="Confirm Password">
                                                <span class="text-xl text-slate-400 absolute top-1/2 -translate-y-1/2 right-3 cursor-pointer" id="toggleIcon">
                                                    <iconify-icon id="hidePassword" class="passwordeye" icon="heroicons-outline:eye-off"></iconify-icon>
                                                    <iconify-icon class="hidden passwordeye" id="showPassword" icon="heroicons-outline:eye"></iconify-icon>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end p-6 space-x-2 border-slate-200 rounded-b dark:border-slate-600">
                                        <button type="reset" class="btn inline-flex justify-center btn-outline-dark">Cancel</button>
                                        <button type="submit" id="submit-btn" class="btn inline-flex justify-center text-white bg-black-500">
                                            Update
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
