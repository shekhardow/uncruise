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
                                Reviews
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
                                                        <th scope="col" class="table-th table-centers">Status</th>
                                                        <th scope="col" class="table-th">User</th>
                                                        <!--<th scope="col" class="table-th">Post</th>-->
                                                        <th scope="col" class="table-th">Adventure Name</th>
                                                        <th scope="col" class="table-th">Adventure Date</th>
                                                        <!--<th scope="col" class="table-th">Booking ID</th>-->
                                                        <!--<th scope="col" class="table-th">Personal ID</th>-->
                                                         <!--<th scope="col" class="table-th">Documents</th>-->
                                                        <th scope="col" class="table-th">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                    <?php $i=1; if(!empty($reviews)){ foreach($reviews as $review){ //dd($review); ?>
                                                    <tr>
                                                        <td class="table-td text-center">
                                                            <?php if($review->status == 'Active'){ $class = "text-success-500 bg-success-500"; $status = "Active"; $change_to = "Inactive";
                                                            }else{ $class = "text-danger-500 bg-danger-500"; $status = "Inactive"; $change_to = "Active"; } ?>
                                                            <div class="customSwitchBox">
                                                                <input type="checkbox" class="toggleStatus" <?php echo (@$review->status == 'Active') ? "Checked" : ""; ?> id="customSwitch_<?php echo $i; ?>" data-table="journey_reviews" data-changevalue="<?php echo @$change_to; ?>" data-column_name="status" data-wherecolumn="journey_review_id" data-wherevalue="<?php echo @$review->journey_review_id; ?>" data-url="<?php echo route('toggleStatus'); ?>" name="customSwitch" value="<?php echo @$review->status; ?>"/>
                                                                <label for="customSwitch_<?php echo $i; ?>"></label>
                                                            </div>
                                                        </td>
                                                        <td class="table-td smallText"><?php echo !empty($review->first_name) ? $review->first_name. ' ' . $review->last_name : null; ?></td>
                                                        <!--<td class="table-td smallText"><?php //echo !empty($review->review) ? limitWords($review->review, 5) : null; ?></td>-->
                                                        <td class="table-td smallText"><?php echo !empty($review->journey) ? $review->journey : null; ?></td>
                                                        <td class="table-td smallText"><?php echo !empty($review->journey_date) ? date('d M, Y', strtotime($review->journey_date)) : null; ?></td>
                                                        <!--<td class="table-td smallText"><?php //echo !empty($review->booking_id) ? $review->booking_id : null; ?></td>-->
                                                        <!--<td class="table-td smallText"><?php //echo !empty($review->personal_id) ? $review->personal_id : null; ?></td>-->
                                                        <td class="table-td">
                                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                                <!--<a href="<?php //echo route('admin/reviewDetails',['id' => encryptionID($review->journey_review_id)]); ?>" class="action-btn" data-tippy-content="View Review details" data-tippy-placement="top">-->
                                                                <a href="javascript:void(0)" class="action-btn">
                                                                    <iconify-icon icon="heroicons:eye"></iconify-icon>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <!--<td class="table-td smallText"><iconify-icon icon="material-symbols:file-present" width="25" height="25"></iconify-icon></td>-->
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
