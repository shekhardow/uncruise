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
                                Ships
                                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                            </li>
                            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                                <?php echo !empty($cruise_detail) ? 'Edit' : 'Add'; ?> Ship
                            </li>
                        </ul>
                    </div>
                    <!-- END: BreadCrumb -->

                    <div class="space-y-5">
                        <div class="card">
                            <div class="card-body p-6">
                                <form method="post" id="submit-form" action="<?php echo !empty($cruise_detail) ? route('admin/updateCruise', ['id' => encryptionID($cruise_detail->ship_id)]) : route('admin/addCruise'); ?>" enctype="multipart/form-data">
                                    <div class="grid lg:grid-cols-1 md:grid-cols-1 grid-cols-1 gap-5 mb-3">
                                        <div class="form-group input-area">
                                            <label for="ship_name" class="form-label">Ship Name :</label>
                                            <input type="text" name="ship_name" id="ship_name" value="<?php echo !empty($cruise_detail->ship_name) ? $cruise_detail->ship_name : null; ?>" class="form-control">
                                        </div>

                                        <!--<div class="form-group input-area">-->
                                        <!--    <label for="ship_type" class="form-label">Cruise Type :</label>-->
                                        <!--    <select name="ship_type" id="ship_type" class="form-control">-->
                                        <!--        <option value="" selected disabled>Select Cruise Type</option>-->
                                        <!--        <?php //if(!empty($ship_types)){ foreach($ship_types as $type){ ?>-->
                                        <!--        <option value="<?php //echo $type->ship_type; ?>" <?php //echo (@$cruise_detail->ship_type == $type->ship_type) ? 'selected' : null; ?>>-->
                                        <!--            <?php //echo $type->ship_type; ?>-->
                                        <!--        </option>-->
                                        <!--        <?php //}} ?>-->
                                        <!--    </select>-->
                                        <!--</div>-->

                                        <div class="form-group input-area lg:col-span-1 md:col-span-1 col-span-1">
                                            <label for="detailed_description" class="form-label">Detailed Description :</label>
                                            <textarea rows="3" name="detailed_description" id="detailed_description" class="tinymice block w-full py-2 px-3 border border-gray-300 rounded-md">
                                                <?php echo !empty($cruise_detail->detailed_description) ? $cruise_detail->detailed_description : null; ?>
                                            </textarea>
                                        </div>

                                        <div class="form-group input-area">
                                            <label for="size & year" class="form-label">Size & Year :</label>
                                            <select name="sizeyear[]"  class="selecttag form-control w-full mt-2 py-2" multiple="multiple">
                                                    <?php if(!empty($seleted_size)){
                                                        foreach($seleted_size as $size){?>
                                                                <option selected value="<?php echo $size;  ?>"  class=" inline-block font-Inter font-normal text-sm text-slate-600"><?php echo $size;  ?></option>
                                                        <?php }
                                                    } ?>
                                            </select>
                                        </div>

                                        <div class="form-group input-area">
                                            <label for="guest" class="form-label">Guest :</label>
                                            <select name="guest[]"  class="selecttag form-control w-full mt-2 py-2" multiple="multiple">
                                                    <?php if(!empty($seleted_guest)){
                                                        foreach($seleted_guest as $size){?>
                                                                <option  selected value="<?php echo $size;  ?>"  class=" inline-block font-Inter font-normal text-sm text-slate-600"><?php echo $size;  ?></option>
                                                        <?php }
                                                    } ?>
                                            </select>
                                        </div>


                                        <div class="form-group input-area">
                                            <label for="crew" class="form-label">Crew :</label>
                                            <select name="crew[]"  class="selecttag form-control w-full mt-2 py-2" multiple="multiple">
                                            <?php if(!empty($seleted_crew)){
                                                        foreach($seleted_crew as $size){?>
                                                                <option  selected value="<?php echo $size;  ?>"  class=" inline-block font-Inter font-normal text-sm text-slate-600"><?php echo $size;  ?></option>
                                                        <?php }
                                                    } ?>
                                            </select>
                                        </div>

                                        <div class="form-group input-area">
                                            <label for="Destinatios" class="form-label">Destinatios :</label>
                                            <select name="destinatios[]"  class="selecttag form-control w-full mt-2 py-2" multiple="multiple">
                                                <?php if($destinations->isNotEmpty()){
                                                        foreach($destinations as $destination){?>
                                                                <option  value="<?php echo $destination->destination_id;  ?>" <?php echo (@in_array($destination->destination_id,@$seleted_destination))?"selected":""; ?> class=" inline-block font-Inter font-normal text-sm text-slate-600"><?php echo  @$destination->name ?></option>
                                                        <?php }
                                                    } ?>
                                            </select>
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
                                                <?php if(!empty($cruise_detail->thumbnail_image)){ ?>
                                                <img src="<?php echo $cruise_detail->thumbnail_image; ?>">
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group input-area lg:col-span-3 md:col-span-2 col-span-1 mt-3">
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
                                                <?php if (!empty($ship_images)) { ?>
                                                <?php foreach ($ship_images as $value) { ?>
                                                <div class="relative inline-block previewImages">
                                                    <img src="<?php echo !empty($value->image_url) ? $value->image_url : null; ?>" class="preview-img">
                                                    <a href="<?php echo route('admin/deleteCruise', ['id' => encryptionID($value->id)]); ?>" class="delete-image cross-btn" data-tippy-content="Delete Image" data-tippy-placement="left">X</a>
                                                </div>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end p-6 space-x-2 border-slate-200 rounded-b dark:border-slate-600">
                                        <a href="<?php echo route('admin/cruise'); ?>" class="btn inline-flex justify-center btn-outline-dark">Cancel</a>
                                        <button type="submit" id="submit-btn" class="btn inline-flex justify-center text-white bg-black-500">
                                            <?php echo !empty($cruise_detail) ? 'Update' : 'Submit'; ?>
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
