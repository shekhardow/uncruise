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
                                App Management
                                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                            </li>
                            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                                <?php echo !empty($title) ? $title : null; ?>
                            </li>
                        </ul>
                    </div>
                    {{-- END: BreadCrumb --}}

                    <div class="rounded-md overlay bg-no-repeat bg-center bg-cover card" style="background-image: url('/assets/images/all-img/card-3.png');">
                        <div class="card-body h-full flex flex-col justify-center p-6">
                            <div class="card-text flex flex-col justify-between h-full">

                                <form method="post" id="submit-setting-form" action="<?php echo route('admin/updateSiteSetting'); ?>">
                                    <div class="max-w-3xl mx-auto">
                                        <label class="card-title block mb-2">Update <?php echo !empty($title) ? $title : null; ?> :</label>
                                        <input type="hidden" name="type" value="<?php echo $type; ?>">
                                        <textarea rows="10" name="description" id="description" class="block w-full py-2 px-3 border border-gray-300 rounded-md tinymice">
                                            <?php echo !empty($site_setting->description) ? $site_setting->description : null; ?>
                                        </textarea>
                                    </div>
                                    <div class="mt-6 text-right">
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Update
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
