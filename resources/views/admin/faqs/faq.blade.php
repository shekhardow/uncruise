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
                                    FAQs
                                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                                </li>
                                <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                                    Listing
                                </li>
                            </ul>
                        </div>
                        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                            <button class="openModel btn leading-0 inline-flex justify-center bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300 !font-normal"
                            data-url="<?php echo route('admin/openFaqForm'); ?>" data-tippy-content="Add New FAQ" data-tippy-placement="left">
                                <span class="flex items-center">
                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="heroicons-outline:plus"></iconify-icon>
                                    <span>Add FAQ</span>
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
                                                        <th scope="col" class="table-th">S/N</th>
                                                        <th scope="col" class="table-th">Question</th>
                                                        <th scope="col" class="table-th">Answer</th>
                                                        <th scope="col" class="table-th">Status</th>
                                                        <th scope="col" class="table-th">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                    <?php $i=1; if(!empty($faqs)){ foreach($faqs as $faq){ ?>
                                                    <tr>
                                                        <td class="table-td"><?php echo $i; ?></td>
                                                        <td class="table-td"><?php echo !empty($faq->question) ? $faq->question : null; ?></td>
                                                        <td class="table-td"><?php echo !empty($faq->answer) ? $faq->answer : null; ?></td>
                                                        <?php if($faq->status == 'Active'){
                                                            $class = "text-success-500 bg-success-500";
                                                            $status = "Active";
                                                            $change_to = "Inactive";
                                                        }else{
                                                            $class = "text-danger-500 bg-danger-500";
                                                            $status = "Inactive";
                                                            $change_to = "Active";
                                                        } ?>
                                                        <td class="table-td">
                                                            <a href="<?php echo url('admin/change-status/' . $faq->faq_id . '/' . $change_to . '/faqs/faq_id/status'); ?>" status-type="<?php echo $change_to; ?>"
                                                                class="status inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 <?php echo $class; ?>"
                                                                data-tippy-content="Change status to {{$change_to}}" data-tippy-placement="top">
                                                                <?php echo $status ?>
                                                            </a>
                                                        </td>
                                                        <td class="table-td">
                                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                                <a class="action-btn openModel" data-url="<?php echo route('admin/openFaqForm'); ?>" data-id="<?php echo $faq->faq_id; ?>" href="javascript:void(0)" data-tippy-content="Edit FAQ" data-tippy-placement="top">
                                                                    <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                                                </a>
                                                                <a href="<?php echo url('admin/change-status/'.$faq->faq_id.'/Deleted/faqs/faq_id/status'); ?>" class="status action-btn" data-tippy-content="Delete FAQ" data-tippy-placement="top">
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
