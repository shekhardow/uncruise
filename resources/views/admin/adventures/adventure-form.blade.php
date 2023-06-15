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
                                Activities
                                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                            </li>
                            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                                <?php echo !empty($adventure_detail) ? 'Edit' : 'Add'; ?> Activity
                            </li>
                        </ul>
                    </div>
                    <!-- END: BreadCrumb -->

                    <div class="space-y-5">
                        <div class="card">
                            <div class="card-body p-6">
                                <form method="post" id="submit-form" action="<?php echo !empty($adventure_detail) ? route('admin/updateAdventure', ['id' => encryptionID($adventure_detail->adventure_id)]) : route('admin/addAdventure'); ?>" enctype="multipart/form-data">
                                    <div class="grid lg:grid-cols-1 md:grid-cols-1 grid-cols-1 gap-5 mb-3">
                                        <div class="form-group input-area">
                                            <label for="adventure_name" class="form-label">Activity Name :</label>
                                            <input type="text" name="adventure_name" id="adventure_name" value="<?php echo !empty($adventure_detail->adventure_name) ? $adventure_detail->adventure_name : null; ?>" class="form-control">
                                        </div>
                                        
                                        <!-- <div class="form-group input-area">
                                            <label for="destination" class="form-label">Assign Destination :</label>
                                            <select name="destination" id="destination" class="form-control">
                                                <option value="" selected disabled>Select Destination</option>
                                                <?php //if(!empty($destinations)){ foreach($destinations as $destination){ ?>
                                                <option value="<?php //echo $destination->destination_id; ?>" <?php //echo (@$adventure_detail->destination_id == $destination->destination_id) ? 'selected' : null; ?>>
                                                    <?php //echo $destination->name; ?>
                                                </option>
                                                <?php //}} ?>
                                            </select>
                                        </div> -->
                                        
                                        <div class="form-group input-area lg:col-span-3 md:col-span-2 col-span-1">
                                            <label for="description" class="form-label">Description :</label>
                                            <textarea rows="3" name="description" id="description" class="tinymice block w-full py-2 px-3 border border-gray-300 rounded-md">
                                                <?php echo !empty($adventure_detail->description) ? $adventure_detail->description : null; ?>
                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group input-area lg:col-span-3 md:col-span-2 col-span-1 mt-5">
                                        <label for="thumbnail_image" class="form-label">Banner Image :</label>
                                        <div class="multiFilePreview">
                                            <label>
                                                <input type="file" name="thumbnail_image" class="w-full hidden" accept=".jpg, .jpeg, .png, .svg, .webp">
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
                                                <?php if(!empty($adventure_detail->thumbnail_image)){ ?>
                                                <img src="<?php echo $adventure_detail->thumbnail_image; ?>">
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group input-area lg:col-span-3 md:col-span-2 col-span-1 mt-5">
                                        <label for="other_iamges" class="form-label">Other Images :</label>
                                        <div class="multiFilePreview_favicon">
                                            <label>
                                                <input type="file" name="other_images[]" class="w-full hidden" multiple="multiple" accept=".jpg, .jpeg, .png, .svg, .webp">
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

                                            </div>
                                            <div id="oldfile">
                                                <?php if (!empty($destination_images)) { ?>
                                                <?php foreach ($destination_images as $value) { ?>
                                                <div class="relative inline-block">
                                                    <img src="<?php echo !empty($value->image_url) ? $value->image_url : null; ?>" style="width: 150px; height: 150px; float:left; margin:10px 0px 0px 10px">
                                                    <a href="<?php echo route('admin/deleteAdventure', ['id' => encryptionID($value->id)]); ?>" class="delete-image absolute top-0 right-0 p-1 rounded-full bg-red-500 text-white">X</a>
                                                </div>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end p-6 space-x-2 border-slate-200 rounded-b dark:border-slate-600">
                                        <a href="<?php echo route('admin/adventures'); ?>" class="btn inline-flex justify-center btn-outline-dark">Cancel</a>
                                        <button type="submit" id="submit-btn" class="btn inline-flex justify-center text-white bg-black-500">
                                            <?php echo !empty($adventure_detail) ? 'Update' : 'Submit'; ?>
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
