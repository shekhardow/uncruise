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
                                Testimonials
                                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                            </li>
                            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                                Details
                            </li>
                        </ul>
                    </div>
                    {{-- END: BreadCrumb --}}

                    <div class="rounded-md overlay bg-no-repeat bg-center bg-cover card">
                        <div class="card-body h-full flex flex-col justify-center p-6">
                            <div class="card-text flex flex-col justify-between h-full">
                                <div class="m-portlet__body userDetailsPage grid grid-cols-2 gap-2 fullGrid">
                                    <div class="form-group m-form__group row">
                                        <label class="bold-label">Review For :</label>
                                        <label><?php echo !empty($review_details->review_type) ? $review_details->review_type : null; ?></label>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="bold-label">
                                            <?php if(@$review_details->review_type == 'Destination'){ echo 'Destination Name';
                                            }elseif(@$review_details->review_type == 'Cruise'){ echo 'Cruise Name';
                                            }else{ echo 'Adventure Name'; } ?> :
                                        </label>
                                        <label>
                                            <?php if(@$review_details->review_type == 'Destination'){ echo @$review_details->destination_name;
                                            }elseif(@$review_details->review_type == 'Cruise'){ echo @$review_details->ship_name;
                                            }else{ echo @$review_details->adventure_name; } ?>
                                        </label>
                                    </div>
                                    <div class="form-group lg:col-span-3 md:col-span-2 col-span-1">
                                        <label class="bold-label">Review :</label>
                                        <label><?php echo !empty($review_details->review) ? $review_details->review : null; ?></label>
                                    </div>
                                    {{-- <div class="m-portlet__body userDetailsPage">
                                        <div class="form-group m-form__group row">
                                            <label class="bold-label">Other Images :</label>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                        {{-- <div class="boxContainer">
                            <?php //if(!empty($review_details)){ foreach($review_details as $image){ ?>
                            <div class="outerBox">
                                <div class="maltipalBox">
                                    <img src="<?php //echo !empty($image->image_url) ? $image->image_url : null; ?>" class="boxImages" alt="Image">
                                </div>
                            </div>
                            <?php //}} ?>
                        </div> --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
