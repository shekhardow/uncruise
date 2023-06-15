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
                                Social
                                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                            </li>
                            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                                Links
                            </li>
                        </ul>
                    </div>
                    {{-- END: BreadCrumb --}}

                    <div class="card xl:col-span-2">
                        <div class="card-body flex flex-col p-6">
                            <div class="card-text h-full ">
                                <form id="submit-form" method="post" action="{{ url('admin/update-social-link') }}" class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                                        <?php foreach($social_link as $key => $value){ if(!empty($social_data)){ foreach($social_data as $social){ if($social->social_name == $value){ ?>
                                        <div class="input-area relative">
                                            <label for="largeInput" class="form-label">{{ $value }}:</label>
                                            <input type="hidden" name="social_name[]" value="{{ $value }}">
                                            <input type="url" name="social_link[]" class="form-control" value="{{ $social->social_link }}" placeholder="Enter {{ $value }} Link">
                                            <span class="m-form__help text-xs font-light">Please enter your {{ $value }} Link</span>
                                        </div>
                                        <?php } } }else{?>
                                        <div class="input-area relative">
                                            <label for="largeInput" class="form-label">{{ $value }}:</label>
                                            <input type="hidden" name="social_name[]" value="{{ $value }}">
                                            <input type="url" name="social_link[]" class="form-control" placeholder="Enter {{ $value }} Link">
                                            <span class="m-form__help text-xs font-light">Please enter your {{ $value }} Link</span>
                                        </div>
                                        <?php } } ?>
                                    </div>
                                    <div class="mt-4 flex justify-end">
                                        <button type="submit" class="btn btn-dark">Submit</button>
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
