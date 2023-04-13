@extends('admin/layout')
@section('content')
    <div class="content-wrapper transition-all duration-150 ltr:ml-[248px] rtl:mr-[248px]" id="content_wrapper">
        <div class="page-content mainBodyNewPadding">
            <div class="transition-all duration-150 container-fluid" id="page_layout">
                <div id="content_layout">

                    {{-- START: Breadcrumb --}}
                    <div class="mb-5">
                        <ul class="m-0 p-0 list-none">
                            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter">
                                <a href="<?php echo route('admin/dashboard'); ?>">
                                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                </a>
                            </li>
                            <li class="inline-block relative text-sm text-primary-500 font-Inter">
                                Users
                                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                            </li>
                            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                                Details
                            </li>
                        </ul>
                    </div>
                    {{-- END: BreadCrumb --}}

                    <div class="rounded-md overlay bg-no-repeat bg-center bg-cover card"
                        style="background-image: url('/assets/images/all-img/card-3.png');">
                        <div class="card-body h-full flex flex-col justify-center p-6">
                            <div class="card-text flex flex-col justify-between h-full">
                                <div class="m-portlet__body userDetailsPage">
                                    <div class="form-group m-form__group row">
                                        <label class="bold-label">Name :</label>
                                        <label><?php echo !empty($user_details) ? ucwords(strtolower($user_details->first_name.' '.$user_details->last_name)) : null; ?></label>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="bold-label">Email :</label>
                                        <label><?php echo !empty($user_details->email) ? $user_details->email : null; ?></label>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="bold-label">Contact No :</label>
                                        <label><?php echo !empty($user_details->contact_no) ? $user_details->country_code.' '.$user_details->contact_no : null; ?></label>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="bold-label">Gender :</label>
                                        <label><?php echo !empty($user_details->gender) ? ucwords($user_details->gender) : null; ?></label>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="bold-label">Age :</label>
                                        <label><?php echo !empty($user_details->age) ? $user_details->age.' '.'Years' : null; ?></label>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="bold-label">Country :</label>
                                        <label><?php echo !empty($user_details->country) ? $user_details->country : null; ?></label>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="bold-label">City :</label>
                                        <label><?php echo !empty($user_details->city) ? $user_details->city : null; ?></label>
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
