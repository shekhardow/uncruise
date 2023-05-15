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
                                Listing
                            </li>
                        </ul>
                    </div>
                    {{-- END: BreadCrumb --}}

                    <div class="space-y-5">
                        <div class="card">
                            <div class="card-body px-6 pb-6">
                                <div class="overflow-x-auto -mx-6 dashcode-data-table">
                                    <span class="col-span-8 hidden"></span>
                                    <span class="col-span-4 hidden"></span>
                                    <div class="inline-block min-w-full align-middle">
                                        <div class="overflow-hidden">
                                            <table id="myTable" class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 data-table">
                                                <thead class=" bg-slate-200 dark:bg-slate-700">
                                                    <tr>
                                                        <th scope="col" class="table-th">S/N</th>
                                                        <th scope="col" class="table-th">Post</th>
                                                        <th scope="col" class="table-th">Destination Name</th>
                                                        <th scope="col" class="table-th table-centers">Set as Testimonial</th>
                                                        <th scope="col" class="table-th">Status</th>
                                                        <th scope="col" class="table-th">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                    <?php $i=1; if(!empty($testimonials)){ foreach($testimonials as $testimonial){ ?>
                                                    <tr>
                                                        <td class="table-td"><?php echo $i; ?></td>
                                                        <td class="table-td smallText"><?php echo !empty($testimonial->review) ? limitWords($testimonial->review, 10) : null; ?></td>
                                                      <td class="table-td smallText"><?php echo !empty($testimonial->review_type) ? $testimonial->review_name : null; ?></td>
                                                        <td class="table-td text-center">
                                                            <?php if($testimonial->mark_as_testimonial == 'Testimonial'){ $class = "text-success-500 bg-success-500"; $status = "Testimonial"; $change_to = "No";
                                                            }else{ $class = "text-danger-500 bg-danger-500"; $status = "No"; $change_to = "Testimonial"; } ?>
                                                            <!--<a href="<?php // echo url('admin/change-status/' . $testimonial->review_id . '/' . $change_to . '/reviews/review_id/mark_as_testimonial'); ?>" status-type="<?php echo $change_to; ?>"-->
                                                            <!--    class="status inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 <?php echo $class; ?>">-->
                                                            <!--    <?php echo $status ?>-->
                                                            <!--</a>-->
                                                            
                                                            <div class="customSwitchBox">
                                                                <input type="checkbox" class="toggleStatus"  <?php echo @($testimonial->mark_as_testimonial=='Testimonial')?"Checked":"" ?> id="customSwitch_<?php echo $i ?>" data-table="reviews" data-changevalue="<?php echo @($testimonial->mark_as_testimonial=='Testimonial')?"No":"Testimonial" ?>" data-column_name='mark_as_testimonial' data-wherecolumn="review_id" data-wherevalue="<?php echo @$testimonial->review_id; ?>" data-url="<?php echo route('toggleStatus'); ?>"  name="customSwitch" value="<?php echo @$testimonial->mark_as_testimonial ?>"/>
                                                                <label for="customSwitch_<?php echo $i ?>"></label>
                                                            </div>
                                                            
                                                        </td>
                                                        <td class="table-td">
                                                            <?php if($testimonial->status == 'Active'){ $class = "text-success-500 bg-success-500"; $status = "Active"; $change_to = "Inactive";
                                                            }else{ $class = "text-danger-500 bg-danger-500"; $status = "Inactive"; $change_to = "Active"; } ?>
                                                            <!--<a href="<?php //echo url('admin/change-status/' . $testimonial->review_id . '/' . $change_to . '/reviews/review_id/status'); ?>" status-type="<?php //echo $change_to; ?>"-->
                                                            <!--    class="status inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 <?php // echo $class; ?>">-->
                                                            <!--    <?php //echo $status ?>-->
                                                            <!--</a>-->
                                                            
                                                             <div class="customSwitchBox">
                                                                <input type="checkbox" class="toggleStatus"  <?php echo @($testimonial->status=='Active')?"Checked":"" ?> id="customSwitchStatus_<?php echo $i ?>" data-table="reviews" data-changevalue="<?php echo @$change_to; ?>" data-column_name='status' data-wherecolumn="review_id" data-wherevalue="<?php echo @$testimonial->review_id; ?>" data-url="<?php echo route('toggleStatus'); ?>"  name="customSwitch" value="<?php echo @$testimonial->status ?>"/>
                                                                <label for="customSwitchStatus_<?php echo $i ?>"></label>
                                                            </div>
                                                        </td>
                                                        <td class="table-td">
                                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                                <a href="<?php echo route('admin/testimonialDetails',['id' => encryptionID($testimonial->review_id)]); ?>" class="action-btn" data-tippy-content="View Testimonial details" data-tippy-placement="top">
                                                                    <iconify-icon icon="heroicons:eye"></iconify-icon>
                                                                </a>
                                                                <a href="<?php echo url('admin/change-status/'.$testimonial->review_id.'/Deleted/reviews/review_id/status'); ?>" class="status action-btn" data-tippy-content="Delete Testimonial" data-tippy-placement="top">
                                                                    <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php $i++; }} ?>
                                                </tbody>
                                            </table>
                                        </div>
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
