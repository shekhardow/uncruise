@extends('admin/layout')
@section('content')
    <div class="content-wrapper transition-all duration-150 ltr:ml-[248px] rtl:mr-[248px]" id="content_wrapper">
        <div class="page-content mainBodyNewPadding">
            <div class="transition-all duration-150 container-fluid" id="page_layout">
                <div id="content_layout">
                    <!-- BEGIN: Breadcrumb -->
                    <div class="mb-5">
                        <ul class="m-0 p-0 list-none">
                            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter">
                                <a href="<?php echo route('admin/dashboard'); ?>">
                                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                                </a>
                            </li>
                            <li class="inline-block relative text-sm text-primary-500 font-Inter">
                                Destinations
                                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                            </li>
                            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                                <?php echo !empty($destination_detail) ? 'Edit' : 'Add'; ?> Destination
                            </li>
                        </ul>
                    </div>
                    <!-- END: BreadCrumb -->

                    <div class="space-y-5">
                        <div class="card">
                            <div class="card-body p-6">
                                <form method="post" id="submit-form" action="<?php echo !empty($destination_detail) ? route('admin/updateDestination', ['id' => encryptionID($destination_detail->destination_id)]) : route('admin/addDestination'); ?>" enctype="multipart/form-data">
                                    <div class="">
                                        <div class="grid lg:grid-cols-2 md:grid-cols-1 grid-cols-1 gap-5 mb-3">
                                            <div class="form-group input-area">
                                                <label for="name" class="form-label">Destination Name :</label>
                                                <input type="text" name="name" id="name" value="<?php echo !empty($destination_detail->name) ? $destination_detail->name : null; ?>" class="form-control">
                                            </div>

                                            <div class="form-group input-area">
                                                <label for="location" class="form-label">Location :</label>
                                                <input type="text" name="location" id="location" value="<?php echo !empty($destination_detail->location) ? $destination_detail->location : null; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="grid lg:grid-cols-1 md:grid-cols-1 grid-cols-1 gap-5 mb-3">
                                            <div class="form-group input-area">
                                                <label for="multiSelect" class="form-label">Adventures:</label>
                                                    <select name="adventures[]" id="multiSelect" class="selectTagBox customSelect2 form-control w-full mt-2 py-2" multiple="multiple">
                                                    <?php if($adventures->isNotEmpty()){
                                                        foreach($adventures as $adventure){?>
                                                                <option  value="<?php echo $adventure->adventure_id;  ?>" <?php echo (@in_array($adventure->adventure_id,$seleted_adventures))?"selected":""; ?> class=" inline-block font-Inter font-normal text-sm text-slate-600"><?php echo  @$adventure->adventure_name ?></option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="container" class="space-y-3 mt-3">
                                            <label for="location" class="form-label">Amenities :</label>
                                            <?php if($seleted_amenities->isNotEmpty()){ $i=0; foreach($seleted_amenities as $sa){ ?>
                                                <div class="flex items-center amenitiesBox">
                                                    <div class="w-1/2 mr-2">
                                                        <textarea type="text" name="title[]" class="amenities input-field form-control w-full" placeholder="Enter Title"><?php echo @$sa->amenitie_title; ?></textarea>
                                                    </div>
                                                    <div class="w-1/2">
                                                        <textarea type="text" name="subtitle[]" class="amenities input-field form-control w-full" placeholder="Write Description Here"><?php echo @$sa->amenitie_descriptions; ?></textarea>
                                                    </div>
                                                    <?php if($i==0){ ?>
                                                        <span class="add-row cursor-pointer ml-2 text-lg text-green-500">+</span>
                                                    <?php }else{?>
                                                        <span class="exist-row cursor-pointer ml-2 text-lg text-red-500">-</span>
                                                    <?php } ?>
                                                </div>
                                            <?php  $i++; } }else{?>
                                                <div class="flex items-center amenitiesBox">
                                                    <div class="w-1/2 mr-2">
                                                        <textarea type="text" name="title[]" class="amenities input-field form-control w-full" placeholder="Title" value=""></textarea>
                                                    </div>
                                                    <div class="w-1/2">
                                                        <textarea type="text" name="subtitle[]" class="amenities input-field form-control w-full" placeholder="Description"></textarea>
                                                    </div>
                                                    <span class="add-row cursor-pointer ml-2 text-lg text-green-500">+</span>
                                                </div>
                                            <?php } ?>
                                        </div>

                                        <div class="form-group input-area lg:col-span-3 md:col-span-2 col-span-1 mt-3">
                                            <label for="description" class="form-label">Description :</label>
                                            <textarea rows="3" name="description" id="description" class="tinymice block w-full py-2 px-3 border border-gray-300 rounded-md"><?php echo !empty($destination_detail->description) ? $destination_detail->description : null; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group input-area lg:col-span-3 md:col-span-2 col-span-1 mt-3">
                                        <label for="thumbnail_image" class="form-label">Banner Image :</label>
                                        <div class="multiFilePreview">
                                            <label>
                                                <input type="file" name="thumbnail_image" class="w-full hidden" accept=".jpg, .jpeg, .png, .svg, .webp">
                                                <span class="w-full h-[40px] file-control flex items-center custom-class">
                                                    <span class="flex-1 overflow-hidden text-ellipsis whitespace-nowrap">
                                                        <span id="placeholder" class="text-slate-400">
                                                            Choose a file or drop it here...
                                                        </span>
                                                    </span>
                                                    <span class="file-name flex-none cursor-pointer border-l px-4 border-slate-200 dark:border-slate-700 h-full inline-flex items-center bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-sm rounded-tr rounded-br font-normal">
                                                        Browse
                                                    </span>
                                                </span>
                                            </label>
                                            <div id="file_preview_profile_pic">
                                                <?php if(!empty($destination_detail->thumbnail_image)){ ?>
                                                <img src="<?php echo $destination_detail->thumbnail_image; ?>">
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group input-area lg:col-span-3 md:col-span-2 col-span-1 mt-3">
                                        <label for="other_iamges" class="form-label">Other Images :</label>
                                        <div class="multiFilePreview_favicon">
                                            <label>
                                                <input type="file" name="other_images[]" class="w-full hidden" multiple="multiple" accept=".jpg, .jpeg, .png, .svg, .webp">
                                                <span class="w-full h-[40px] file-control flex items-center custom-class">
                                                    <span class="flex-1 overflow-hidden text-ellipsis whitespace-nowrap">
                                                        <span id="placeholder" class="text-slate-400">
                                                            Choose a file or drop it here...
                                                        </span>
                                                    </span>
                                                    <span class="file-name flex-none cursor-pointer border-l px-4 border-slate-200 dark:border-slate-700 h-full inline-flex items-center bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-sm rounded-tr rounded-br font-normal">
                                                        Browse
                                                    </span>
                                                </span>
                                            </label>
                                            <div id="file_preview_favicon">

                                            </div>
                                            <div id="oldFilePreview">
                                                <?php if (!empty($destination_images)) { ?>
                                                <?php foreach ($destination_images as $value) { ?>
                                                <div class="relative inline-block previewImages">
                                                    <img src="<?php echo !empty($value->image_url) ? $value->image_url : null; ?>" class="preview-img">
                                                    <a href="<?php echo route('admin/deleteDestination', ['id' => encryptionID($value->id)]); ?>" class="delete-image cross-btn" data-tippy-content="Delete Image" data-tippy-placement="left">X</a>
                                                </div>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end p-6 space-x-2 border-slate-200 rounded-b dark:border-slate-600">
                                        <a href="<?php echo route('admin/destinations'); ?>" class="btn inline-flex justify-center btn-outline-dark">Cancel</a>
                                        <button type="submit" id="submit-btn" class="btn inline-flex justify-center text-white bg-black-500">
                                            <?php echo !empty($destination_detail) ? 'Update' : 'Submit'; ?>
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

    <script>
        // Function to add a new row
        function addRow() {
            var container = document.getElementById('container');
            var row = document.createElement('div');
            row.classList.add('flex', 'items-center', 'amenitiesBox');

            var inputContainer = document.createElement('div');
            inputContainer.classList.add('flex', 'w-full');
            // inputContainer.classList.add( 'flex', ' items-center', 'w-1/2', 'mr-2');

            var inputContainerChild = document.createElement('div');
            inputContainerChild.classList.add('w-1/2', 'mr-2');

            var inputContainerChild2 = document.createElement('div');
            inputContainerChild2.classList.add('w-1/2');


            var input1 = document.createElement('textarea');
            input1.name = 'title[]';
            input1.placeholder = "Title";
            input1.classList.add('input-field', 'amenities', 'form-control', 'w-1/6');

            var input2 = document.createElement('textarea');
            input2.name = 'subtitle[]';
            input2.placeholder = "Description";
            input2.classList.add('input-field', 'amenities', 'form-control', 'w-1/6');

            var addBtn = document.createElement('span');
            addBtn.classList.add('add-row', 'cursor-pointer', 'ml-2', 'text-lg', 'text-green-500');
            addBtn.textContent = '+';

            var removeBtn = document.createElement('span');
            removeBtn.classList.add('remove-row', 'cursor-pointer', 'ml-2', 'text-lg', 'text-red-500');
            removeBtn.textContent = '-';

            addBtn.addEventListener('click', addRow);
            removeBtn.addEventListener('click', function() {
                container.removeChild(row);
            });

            inputContainer.appendChild(inputContainerChild);
            inputContainer.appendChild(inputContainerChild2);

            inputContainerChild.appendChild(input1);
            inputContainerChild2.appendChild(input2);

            row.appendChild(inputContainer);
            row.appendChild(addBtn);
            row.appendChild(removeBtn);

            container.appendChild(row);

            tinymce.init({
                selector: 'textarea.amenities',
                height: 150,
                menubar: false,
                plugins: 'link',
                toolbar: 'undo redo | bold italic underline | numlist bullist | link',
            });
        }

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('exist-row')) {
                var row = event.target.parentNode;
                row.parentNode.removeChild(row);
            }
        });

        // Attach event listener to the initial add row button
        var initialAddBtn = document.querySelector('.add-row');
        initialAddBtn.addEventListener('click', addRow);
    </script>
@endsection
