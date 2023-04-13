@extends('admin/layout')
@section('content')
    <div class="content-wrapper transition-all duration-150 ltr:ml-[248px] rtl:mr-[248px]" id="content_wrapper">
        <div class="page-content mainBodyNewPadding">
            <div class="transition-all duration-150 container-fluid" id="page_layout">
                <div id="content_layout">
                    <!-- BEGIN: Breadcrumb -->
                    <div class="mb-5">
                        <ul class="m-0 p-0 list-none">
                            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                                <a href="<?php echo route('admin/dashboard'); ?>">
                                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                                    <iconify-icon icon="heroicons-outline:chevron-right"
                                        class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                </a>
                            </li>
                            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                                Profile
                                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                            </li>
                            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                                Details
                            </li>
                        </ul>
                    </div>
                    <!-- END: BreadCrumb -->
                    <div class="space-y-5 profile-page">
                        <div class="wizard card">
                            <div class="card-header">
                                <h4 class="card-title">Personal Details</h4>
                            </div>
                            <div class="card-body p-6">
                                <div class="wizard-steps flex z-[5] items-center relative justify-center md:mx-8">

                                    <div class="active pass relative z-[1] items-center item flex flex-start flex-1 last:flex-none group wizard-step" data-step="1">
                                        <div class="number-box">
                                            <span class="number">1</span>
                                            <span class="no-icon text-3xl">
                                                <iconify-icon icon="bx:check-double"></iconify-icon>
                                            </span>
                                        </div>
                                        <div class="bar-line"></div>
                                        <div class="circle-box">
                                            <span class="w-max">Personal info</span>
                                        </div>
                                    </div>

                                    <div class="relative z-[1] items-center item flex flex-start flex-1 last:flex-none group wizard-step" data-step="1">
                                        <div class="number-box">
                                            <span class="number">2</span>
                                            <span class="no-icon text-3xl">
                                                <iconify-icon icon="bx:check-double"></iconify-icon>
                                            </span>
                                        </div>
                                        <div class="bar-line"></div>
                                        <div class="circle-box">
                                            <span class="w-max">Images</span>
                                        </div>
                                    </div>

                                    <div class="relative z-[1] items-center item flex flex-start flex-1 last:flex-none group wizard-step" data-step="1">
                                        <div class="number-box">
                                            <span class="number">3</span>
                                            <span class="no-icon text-3xl">
                                                <iconify-icon icon="bx:check-double"></iconify-icon>
                                            </span>
                                        </div>
                                        <div class="bar-line"></div>
                                        <div class="circle-box">
                                            <span class="w-max">Password</span>
                                        </div>
                                    </div>

                                    <div class="relative z-[1] items-center item flex flex-start flex-1 last:flex-none group wizard-step" data-step="1">
                                        <div class="number-box">
                                            <span class="number">4</span>
                                            <span class="no-icon text-3xl">
                                                <iconify-icon icon="bx:check-double"></iconify-icon>
                                            </span>
                                        </div>
                                        <div class="bar-line"></div>
                                        <div class="circle-box">
                                            <span class="w-max">Address</span>
                                        </div>
                                    </div>

                                </div>

                                <form class="wizard-form mt-10" id="submitProfileForm" method="POST" action="<?php echo route('admin/updateProfile'); ?>" autocomplete="off">
                                    <div class="wizard-form-step active" data-step="1">
                                        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
                                            <div class="lg:col-span-3 md:col-span-2 col-span-1">
                                                <h4 class="text-base text-slate-800 dark:text-slate-300 my-6">Edit Your Personal Information</h4>
                                            </div>
                                            <div class="input-area">
                                                <label for="name" class="form-label">Name*</label>
                                                <input type="text" id="name" name="name" value="<?php echo !empty($admin_detail) ? $admin_detail->name : null; ?>" placeholder="Enter Your Full Name" class="form-control" autocomplete="off">
                                            </div>
                                            <div class="input-area">
                                                <label for="email" class="form-label">Email*</label>
                                                <input type="email" id="email" name="email" value="<?php echo !empty($admin_detail) ? $admin_detail->email : null; ?>" placeholder="Enter Your Email" class="form-control">
                                            </div>
                                            <div class="input-area">
                                                <label for="phone" class="form-label">Phone number*</label>
                                                <input type="text" id="phone" name="phone" value="<?php echo !empty($admin_detail) ? $admin_detail->contact_no : null; ?>" placeholder="Enter Your Phone Number" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wizard-form-step" data-step="2">
                                        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
                                            <div class="lg:col-span-3 md:col-span-2 col-span-1">
                                                <h4 class="text-base text-slate-800 dark:text-slate-300 my-6">Change Images
                                                </h4>
                                            </div>
                                            <div class="input-area">
                                                <label for="profile_pic" class="form-label">Profile Picture</label>
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
                                                            <img src="<?php echo !empty($admin_detail->profile_pic) ? url('public/assets/admin/adminimages/'.$admin_detail->profile_pic) : null; ?>">
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-area">
                                                <label for="favicon" class="form-label">FavIcon</label>
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
                                                            <img src="<?php echo !empty($admin_detail->favicon) ? url('public/assets/admin/adminimages/'.$admin_detail->favicon) : null; ?>">
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-area">
                                                <label for="logo" class="form-label">Logo</label>
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
                                                        <?php if(!empty($admin_detail->image_url)){ ?>
                                                            <img src="<?php echo !empty($admin_detail->image_url) ? url('public/assets/admin/adminimages/'.$admin_detail->image_url) : null; ?>">
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wizard-form-step" data-step="3">
                                        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
                                            <div class="lg:col-span-3 md:col-span-2 col-span-1">
                                                <h4 class="text-base text-slate-800 dark:text-slate-300 my-6">Change Password</h4>
                                            </div>
                                            <div class="input-area">
                                                <label for="password" class="form-label">Old Password*</label>
                                                <input type="text" id="password" name="password" class="form-control" placeholder="Old Password" autocomplete="off">
                                            </div>
                                            <div class="input-area">
                                                <label for="newPassword" class="form-label">New Password*</label>
                                                <input type="text" id="newPassword" name="newPassword" class="form-control" placeholder="New Password" autocomplete="off">
                                            </div>
                                            <div class="input-area">
                                                <label for="confirmPassword" class="form-label">Confirm New Password*</label>
                                                <input type="text" id="confirmPassword" class="form-control" placeholder="Confirm Password" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wizard-form-step" data-step="4">
                                        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
                                            <div class="lg:col-span-3 md:col-span-2 col-span-1">
                                                <h4 class="text-base text-slate-800 dark:text-slate-300 my-6">Edit Address
                                                </h4>
                                            </div>
                                            <div class="input-area lg:col-span-3 md:col-span-2 col-span-1">
                                                <label for="address" class="form-label">Address*</label>
                                                <textarea name="address" id="address" rows="3" class="form-control" placeholder="Your Address"><?php echo !empty($admin_detail->address) ? $admin_detail->address : null; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-6 space-x-3">
                                        <button class="btn btn-dark prev-button" type="button">prev</button>
                                        <button class="btn btn-dark next-button"  data-page="1" type="button" id="submitButton">submit</button>
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
