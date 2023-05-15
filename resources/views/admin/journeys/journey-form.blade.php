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
                                Journeys
                                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                            </li>
                            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                                <?php echo !empty($journey_details) ? 'Edit' : 'Add'; ?> Journey
                            </li>
                        </ul>
                    </div>
                    <!-- END: BreadCrumb -->

                    <div class="space-y-5">
                        <div class="card">
                            <div class="card-body p-6">
                                <form method="post" id="submit-form" action="<?php echo !empty($journey_details) ? route('admin/updateJourney', ['id' => encryptionID($journey_details->journey_id)]) : route('admin/addJourney'); ?>" enctype="multipart/form-data">
                                    <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5">
                                        <div class="form-group input-area">
                                            <label for="name" class="form-label">Journey Name :</label>
                                            <input type="text" name="name" id="name" value="<?php echo !empty($journey_details->journey) ? $journey_details->journey : null; ?>" class="form-control">
                                        </div>
                                        <div class="form-group input-area">
                                            <label for="journey_date" class="form-label">Journey Date :</label>
                                            <input type="date" name="journey_date" id="journey_date" value="<?php echo !empty($journey_details->journey_date) ? date('Y-m-d', strtotime($journey_details->journey_date)) : null; ?>" class="form-control">
                                        </div>
                                        <div class="form-group input-area">
                                            <label for="duration" class="form-label">Journey Duration :</label>
                                            <input type="text" name="duration" id="duration" value="<?php echo !empty($journey_details->duration) ? $journey_details->duration : null; ?>" class="form-control">
                                        </div>
                                        <div class="form-group input-area">
                                            <label for="cruise" class="form-label">Cruise :</label>
                                            <select name="cruise[]" id="cruise" class="form-control selecttag"  multiple="multiple">
                                              
                                                <?php if(!empty($cruise_details)){ foreach($cruise_details as $cruise){ ?>
                                                <option value="<?php echo $cruise->cruise_id; ?>"  <?php echo (@in_array( $cruise->cruise_id,$seleted_crew))?"selected":""; ?>>
                                                    <?php echo $cruise->cruise_name; ?>
                                                </option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                        <div class="form-group input-area">
                                            <label for="destination" class="form-label">Destination :</label>
                                            <select name="destination[]" id="destination" class="form-control selecttag"  multiple="multiple">
                                                
                                                <?php if(!empty($destination_details)){ foreach($destination_details as $destination){ ?>
                                                <option value="<?php echo $destination->destination_id; ?>" <?php echo (@in_array($destination->destination_id,$seleted_destination))?"selected":""; ?>>
                                                    <?php echo $destination->name; ?>
                                                </option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                        <div class="form-group input-area">
                                            <label for="adventure" class="form-label">Adventure:</label>
                                            <select name="adventure[]" id="adventure" class="form-control selecttag" multiple>
                                                
                                                <?php if(!empty($adventure_details)){ foreach($adventure_details as $adventure){ ?>
                                                <option value="<?php echo $adventure->adventure_id; ?>" <?php echo (@in_array($adventure->adventure_id,$seleted_adventures))?"selected":""; ?>>
                                                    <?php echo $adventure->adventure_name; ?>
                                                </option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                        <div class="form-group input-area lg:col-span-3 md:col-span-2 col-span-1">
                                            <label for="description" class="form-label">Description :</label>
                                            <textarea rows="3" name="description" id="description" class="tinymice block w-full py-2 px-3 border border-gray-300 rounded-md">
                                                <?php echo !empty($journey_details->description) ? $journey_details->description : null; ?>
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
                                                <?php if(!empty($journey_details->thumbnail_image)){ ?>
                                                <img src="<?php echo $journey_details->thumbnail_image; ?>">
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
                                            <div id="oldFilePreview">
                                                <?php if (!empty($journey_images)) { ?>
                                                <?php foreach ($journey_images as $value) { ?>
                                                <div class="relative inline-block previewImages">
                                                    <img src="<?php echo !empty($value->image_url) ? $value->image_url : null; ?>" class="preview-img">
                                                    <a href="<?php echo route('admin/deleteJourney', ['id' => encryptionID($value->id)]); ?>" class="delete-image cross-btn" data-tippy-content="Delete Image" data-tippy-placement="left">X</a>
                                                </div>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end p-6 space-x-2 border-slate-200 rounded-b dark:border-slate-600">
                                        <a href="<?php echo route('admin/journeys'); ?>" class="btn inline-flex justify-center btn-outline-dark">Cancel</a>
                                        <button type="submit" id="submit-btn" class="btn inline-flex justify-center text-white bg-black-500">
                                            <?php echo !empty($journey_details) ? 'Update' : 'Submit'; ?>
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
