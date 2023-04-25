@extends('admin/layout')
@section('content')
    <div class="content-wrapper transition-all duration-150 ltr:ml-[248px] rtl:mr-[248px]" id="content_wrapper">
        <div class="page-content mainBodyNewPadding">
            <div class="transition-all duration-150 container-fluid" id="page_layout">
                <div id="content_layout">

                    {{-- START: Breadcrumb --}}
                    <div class="flex justify-between items-center mb-4">
                        <div class="mb-5 breadcrumb">
                            <ul class="m-0 p-0 list-none">
                                <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter">
                                    <a href="<?php echo route('admin/dashboard'); ?>">
                                        <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                                        <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                    </a>
                                </li>
                                <li class="inline-block relative text-sm text-primary-500 font-Inter">
                                    Users
                                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                                </li>
                                <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                                    Listing
                                </li>
                            </ul>
                        </div>
                        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                            <button class="send_notification btn leading-0 inline-flex justify-center bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300 !font-normal" 
                            data-url="<?php echo route('admin/notification'); ?>" data-tippy-content="Send Notification" data-tippy-placement="left">
                                <span class="flex items-center">
                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="fluent:mail-24-regular"></iconify-icon>
                                    <span>Send Notification</span>
                                </span>
                            </button>
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
                                                        <th>
                                                            <div class="checkbox-area">
                                                                <label class="inline-flex items-center cursor-pointer">
                                                                    <input type="checkbox" value="" class="hidden check">
                                                                    <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                                        <img src="<?php echo url('public/assets/images/icon/ck-white.svg'); ?>" alt="" class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                                                    <span class="text-slate-500 dark:text-slate-400 text-sm leading-6"></span>
                                                                </label>
                                                            </div>
                                                        </th>
                                                        <th scope="col" class="table-th">S/N</th>
                                                        <th scope="col" class="table-th">User</th>
                                                        <th scope="col" class="table-th">Email</th>
                                                        <th scope="col" class="table-th">Phone</th>
                                                        <th scope="col" class="table-th">Status</th>
                                                        <th scope="col" class="table-th">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                    <?php $i=1; if(!empty($users)){ foreach($users as $user){ ?>
                                                    <tr>
                                                        <td class="table-td new-checkbox-class">
                                                            <div class="checkbox-area">
                                                                <label class="inline-flex items-center cursor-pointer">
                                                                    <input type="checkbox" name="user_id" value="<?php echo $user->user_id; ?>" class="hidden users_id">
                                                                    <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                                        <img src="<?php echo url('public/assets/images/icon/ck-white.svg'); ?>" alt="" class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                                                    <span class="text-slate-500 dark:text-slate-400 text-sm leading-6"></span>
                                                                </label>
                                                              </div>
                                                        </td>
                                                        <td class="table-td"><?php echo $i; ?></td>
                                                        <td class="table-td">
                                                            <span class="flex">
                                                                <span class="w-7 h-7 rounded-full ltr:mr-3 rtl:ml-3 flex-none">
                                                                    <img src="<?php echo url('public/assets/images/all-img/customer_1.png'); ?>" alt="1" class="object-cover w-full h-full rounded-full">
                                                                </span>
                                                                <span class="text-sm text-slate-600 dark:text-slate-300 capitalize"><?php echo !empty($user) ? $user->first_name.' '.$user->last_name : null; ?></span>
                                                            </span>
                                                        </td>
                                                        <td class="table-td"><?php echo !empty($user->email) ? $user->email : null; ?></td>
                                                        <td class="table-td">
                                                            <div><?php echo !empty($user->contact_no) ? $user->country_code.' '.$user->contact_no : null; ?></div>
                                                        </td>
                                                        <?php if($user->status == 'Active'){
                                                            $class = "text-success-500 bg-success-500";
                                                            $status = "Active";
                                                            $change_to = "Inactive";
                                                        }else{
                                                            $class = "text-danger-500 bg-danger-500";
                                                            $status = "Inactive";
                                                            $change_to = "Active";
                                                        } ?>
                                                        <td class="table-td">
                                                            <a href="<?php echo url('admin/change-faq-status/' . $user->user_id . '/' . $change_to . '/users/user_id/status'); ?>" status-type="<?php echo $change_to; ?>" 
                                                                class="status inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 <?php echo $class; ?>"
                                                                data-tippy-content="Change status to {{$change_to}}" data-tippy-placement="top">
                                                                <?php echo $status ?>
                                                            </a>
                                                        </td>
                                                        <td class="table-td">
                                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                                <a href="<?php echo route('admin/userDetails',['user_id' => encryptionID($user->user_id)]); ?>" class="action-btn" data-tippy-content="View <?php echo !empty($user->first_name) ? strtolower($user->first_name)."'s" : null; ?> details" data-tippy-placement="top">
                                                                    <iconify-icon icon="heroicons:eye"></iconify-icon>
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
