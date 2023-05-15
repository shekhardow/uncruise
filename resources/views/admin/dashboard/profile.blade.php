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
                                Profile
                                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                            </li>
                            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                                Update Profile Details
                            </li>
                        </ul>
                    </div>
                    <!-- END: BreadCrumb -->

                    <div class="space-y-5">
                        <div class="card">
                            <div class="card-body p-6">
                                <form method="post" id="submit-form" action="<?php echo route('admin/updateProfile'); ?>" enctype="multipart/form-data" autocomplete="off">
                                    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
                                        <div class="form-group input-area">
                                            <label for="name" class="form-label">Name* :</label>
                                            <input type="text" name="name" value="<?php echo !empty($admin_detail) ? $admin_detail->name : null; ?>" class="form-control">
                                        </div>
                                        <div class="form-group input-area">
                                            <label for="email" class="form-label">Email* :</label>
                                            <input type="email" name="email" value="<?php echo !empty($admin_detail) ? $admin_detail->email : null; ?>" class="form-control">
                                        </div>
                                        <div class="form-group input-area">
                                            <label for="phone" class="form-label">Contact Number :</label>
                                            <input type="text" name="phone" value="<?php echo !empty($admin_detail) ? $admin_detail->contact_no : null; ?>" class="form-control">
                                        </div>
                                        <div class="form-group input-area">
                                            <label for="address" class="form-label">Address :</label>
                                            <input type="text" name="address" value="<?php echo !empty($admin_detail) ? $admin_detail->address : null; ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 mt-5">
                                        <div class="form-group input-area">
                                            <label for="profile_pic" class="form-label">Profile Picture :</label>
                                            <div class="multiFilePreview">
                                                <label>
                                                    <input type="file" name="profile_pic" class="w-full hidden" multiple="multiple" accept=".jpg, .jpeg, .png, .svg, .webp">
                                                    <span class="w-full h-[40px] file-control flex items-center custom-class">
                                                        <span class="flex-1 overflow-hidden text-ellipsis whitespace-nowrap">
                                                            <span id="placeholder" class="text-slate-400">
                                                                Choose a file or drop it here...
                                                            </span>
                                                        </span>
                                                        <span class="file-name flex-none cursor-pointer border-l px-4 border-slate-200 dark:border-slate-700 h-full inline-flex items-center bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-sm rounded-tr rounded-br font-normal">
                                                            Browse
                                                        </span>
                                                    </span>
                                                </label>
                                                <div id="file_preview_profile_pic">
                                                    <?php if(!empty($admin_detail->profile_pic)){ ?>
                                                    <img src="<?php echo !empty($admin_detail->profile_pic) ? url('public/assets/admin/adminimages/' . $admin_detail->profile_pic) : null; ?>">
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group input-area">
                                            <label for="favicon" class="form-label">FavIcon :</label>
                                            <div class="multiFilePreview_favicon">
                                                <label>
                                                    <input type="file" name="favicon" class="w-full hidden" multiple="multiple" accept=".jpg, .jpeg, .png, .svg, .webp">
                                                    <span class="w-full h-[40px] file-control flex items-center custom-class">
                                                        <span class="flex-1 overflow-hidden text-ellipsis whitespace-nowrap">
                                                            <span id="placeholder" class="text-slate-400">
                                                                Choose a file or drop it here...
                                                            </span>
                                                        </span>
                                                        <span class="file-name flex-none cursor-pointer border-l px-4 border-slate-200 dark:border-slate-700 h-full inline-flex items-center bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-sm rounded-tr rounded-br font-normal">
                                                            Browse
                                                        </span>
                                                    </span>
                                                </label>
                                                <div id="file_preview_favicon">
                                                    <?php if(!empty($admin_detail->favicon)){ ?>
                                                    <img src="<?php echo !empty($admin_detail->favicon) ? url('public/assets/admin/adminimages/' . $admin_detail->favicon) : null; ?>">
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group input-area">
                                            <label for="logo" class="form-label">Logo :</label>
                                            <div class="multiFilePreview_logo">
                                                <label>
                                                    <input type="file" name="logo" class="w-full hidden" multiple="multiple" accept=".jpg, .jpeg, .png, .svg, .webp">
                                                    <span class="w-full h-[40px] file-control flex items-center custom-class">
                                                        <span class="flex-1 overflow-hidden text-ellipsis whitespace-nowrap">
                                                            <span id="placeholder" class="text-slate-400">
                                                                Choose a file or drop it here...
                                                            </span>
                                                        </span>
                                                        <span class="file-name flex-none cursor-pointer border-l px-4 border-slate-200 dark:border-slate-700 h-full inline-flex items-center bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-sm rounded-tr rounded-br font-normal">
                                                            Browse
                                                        </span>
                                                    </span>
                                                </label>
                                                <div id="file_preview_logo">
                                                    <?php if(!empty($admin_detail->logo)){ ?>
                                                    <img src="<?php echo !empty($admin_detail->logo) ? url('public/assets/admin/adminimages/' . $admin_detail->logo) : null; ?>">
                                                    <?php } ?>
                                                </div>
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
