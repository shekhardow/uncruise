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
                                Users
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
                                        <div class="overflow-hidden ">
                                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 data-table">
                                                <thead class=" bg-slate-200 dark:bg-slate-700">
                                                    <tr>
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
                                                        <td class="table-td">
                                                            <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-success-500 bg-success-500">
                                                                Active
                                                            </div>
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
