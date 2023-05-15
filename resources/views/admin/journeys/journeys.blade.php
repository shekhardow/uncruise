@extends('admin/layout')
@section('content')
    <div class="content-wrapper transition-all duration-150 ltr:ml-[248px] rtl:mr-[248px]" id="content_wrapper">
        <div class="page-content mainBodyNewPadding mainBodyTopPadding">
            <div class="transition-all duration-150 container-fluid" id="page_layout">
                <div id="content_layout">

                    {{-- START: Breadcrumb --}}
                    <div class="flex justify-between items-center mb-3">
                        <div class="mb-5 breadcrumb">
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
                                    Listing
                                </li>
                            </ul>
                        </div>
                        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                            <a href="<?php echo route('admin/journeyForm'); ?>" class="btn leading-0 inline-flex justify-center bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300 !font-normal"
                            data-tippy-content="Add New Journey" data-tippy-placement="left">
                                <span class="flex items-center">
                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="heroicons-outline:plus"></iconify-icon>
                                    <span>Add Journey</span>
                                </span>
                            </a>
                        </div>
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
                                                        <th scope="col" class="table-th">Journey</th>
                                                        <th scope="col" class="table-th">Journey Date</th>
                                                        <th scope="col" class="table-th">Duration</th>
                                                        <th scope="col" class="table-th">Status</th>
                                                        <th scope="col" class="table-th">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                    <?php $i=1; if(!empty($journeys)){ foreach($journeys as $journey){ ?>
                                                    <tr>
                                                        <td class="table-td"><?php echo $i; ?></td>
                                                        <td class="table-td smallText"><?php echo !empty($journey->journey) ? $journey->journey : null; ?></td>
                                                        <td class="table-td smallText"><?php echo !empty($journey->journey_date) ? date('d M, Y', strtotime($journey->journey_date)) : null; ?></td>
                                                        <td class="table-td smallText"><?php echo !empty($journey->duration) ? $journey->duration : null; ?></td>
                                                        <td class="table-td">
                                                            <?php if($journey->status == 'Active'){ $class = "text-success-500 bg-success-500"; $status = "Active"; $change_to = "Inactive";
                                                            }else{ $class = "text-danger-500 bg-danger-500"; $status = "Inactive"; $change_to = "Active"; } ?>
                                                            <!--<a href="<?php //echo url('admin/change-status/' . $journey->journey_id . '/' . $change_to . '/journeys/journey_id/status'); ?>" status-type="<?php //echo $change_to; ?>"-->
                                                            <!--    class="status inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 <?php //echo $class; ?>">-->
                                                            <!--    <?php // echo $status ?>-->
                                                            <!--</a>-->
                                                             <div class="customSwitchBox">
                                                                <input type="checkbox" class="toggleStatus"  <?php echo @($journey->status=='Active')?"Checked":"" ?> id="customSwitch_<?php echo $i ?>" data-table="journeys" data-changevalue="<?php echo @$change_to; ?>" data-column_name='status' data-wherecolumn="journey_id" data-wherevalue="<?php echo @$journey->journey_id; ?>" data-url="<?php echo route('toggleStatus'); ?>"  name="customSwitch" value="<?php echo @$journey->status ?>"/>
                                                                <label for="customSwitch_<?php echo $i ?>"></label>
                                                            </div>
                                                        </td>
                                                        <td class="table-td">
                                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                                <a class="action-btn" href="<?php echo route('admin/journeyForm', ['id' => encryptionID($journey->journey_id)]); ?>" data-tippy-content="Edit Journey" data-tippy-placement="top">
                                                                    <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                                                </a>
                                                                <a href="<?php echo url('admin/change-status/'.$journey->journey_id.'/Deleted/journeys/journey_id/status'); ?>" class="status action-btn" data-tippy-content="Delete Journey" data-tippy-placement="top">
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
