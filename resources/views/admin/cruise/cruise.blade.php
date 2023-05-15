@extends('admin/layout')
@section('content')
    <div class="content-wrapper transition-all duration-150 ltr:ml-[248px] rtl:mr-[248px]" id="content_wrapper">
        <div class="page-content mainBodyNewPadding ">
            <div class="transition-all duration-150 container-fluid" id="page_layout">
                <div id="content_layout">

                    {{-- START: Breadcrumb --}}
                    <div class="flex justify-between items-center mb-3">
                        <div class="mb-5 ">
                            <ul class="m-0 p-0 list-none">
                                <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter">
                                    <a href="<?php echo route('admin/dashboard'); ?>">
                                        <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                                        <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                    </a>
                                </li>
                                <li class="inline-block relative text-sm text-primary-500 font-Inter">
                                    Cruise
                                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                                </li>
                                <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                                    Listing
                                </li>
                            </ul>
                        </div>
                        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                           <a href="<?php echo route('admin/cruiseForm'); ?>" class="btn leading-0 inline-flex justify-center bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300 !font-normal"
                           data-tippy-content="Add New Cruise" data-tippy-placement="left">
                               <span class="flex items-center">
                                   <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="heroicons-outline:plus"></iconify-icon>
                                   <span>Add Cruise</span>
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
                                                        <th scope="col" class="table-th">Cruise</th>
                                                        <!-- <th scope="col" class="table-th">Type</th> -->
                                                        <th scope="col" class="table-th"> Description</th>
                                                        <th scope="col" class="table-th">Status</th>
                                                        <th scope="col" class="table-th">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                    <?php $i=1; if(!empty($cruises)){ foreach($cruises as $cruise){ ?>
                                                    <tr>
                                                        <td class="table-td"><?php echo $i; ?></td>
                                                        <td class="table-td smallText"><?php echo !empty($cruise->cruise_name) ? $cruise->cruise_name : null; ?></td>
                                                        <!-- <td class="table-td smallText"><?php // echo !empty($cruise->cruise_type) ? $cruise->cruise_type : null; ?></td> -->
                                                        <td class="table-td smallText"><?php echo !empty($cruise->detailed_description) ? $cruise->detailed_description : null; ?></td>
                                                        <td class="table-td">
                                                            <?php if($cruise->status == 'Active'){ $class = "text-success-500 bg-success-500"; $status = "Active"; $change_to = "Inactive";
                                                            }else{ $class = "text-danger-500 bg-danger-500"; $status = "Inactive"; $change_to = "Active"; } ?>
                                                            <!--<a href="<?php //echo url('admin/change-status/' . $cruise->cruise_id . '/' . $change_to . '/cruises/cruise_id/status'); ?>" status-type="<?php //echo $change_to; ?>"-->
                                                            <!--    class="status inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 <?php //echo $class; ?>">-->
                                                            <!--    <?php //echo $status ?>-->
                                                            <!--</a>-->
                                                             <div class="customSwitchBox">
                                                                <input type="checkbox" class="toggleStatus"  <?php echo @($cruise->status=='Active')?"Checked":"" ?> id="customSwitch_<?php echo $i ?>" data-table="cruises" data-changevalue="<?php echo @$change_to; ?>" data-column_name='status' data-wherecolumn="cruise_id" data-wherevalue="<?php echo @$cruise->cruise_id; ?>" data-url="<?php echo route('toggleStatus'); ?>"  name="customSwitch" value="<?php echo @$cruise->status ?>"/>
                                                                <label for="customSwitch_<?php echo $i ?>"></label>
                                                            </div>
                                                        </td>
                                                        <td class="table-td">
                                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                                <a class="action-btn" href="<?php echo route('admin/cruiseForm', ['id' => encryptionID($cruise->cruise_id)]); ?>" data-tippy-content="Edit Cruise" data-tippy-placement="top">
                                                                    <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                                                </a>
                                                                <a href="<?php echo url('admin/change-status/'.$cruise->cruise_id.'/Deleted/cruises/cruise_id/status'); ?>" class="status action-btn" data-tippy-content="Delete Cruise" data-tippy-placement="top">
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
