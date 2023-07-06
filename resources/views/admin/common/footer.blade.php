<!-- BEGIN: Footer For Desktop and tab -->
<footer class="md:block hidden footerNewClass" id="footer">
    <div
        class="site-footer px-6 bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-300 py-4 ltr:ml-[248px] rtl:mr-[248px]">
        <div class="grid md:grid-cols-2 grid-cols-1 md:gap-5">
            <div class="text-center ltr:md:text-start rtl:md:text-right text-sm">
                <?php echo footerContent(); ?>
            </div>
            <div class="ltr:md:text-right rtl:md:text-end text-center text-sm">
                Hand-crafted &amp; Made by
                <a href="https://designoweb.com/" target="_blank" class="text-primary-500 font-semibold">
                    Designoweb Technologies
                </a>
            </div>
        </div>
    </div>
</footer>
<!-- END: Footer For Desktop and tab -->

<div class="bg-white bg-no-repeat custom-dropshadow footer-bg dark:bg-slate-700 flex justify-around items-center backdrop-filter backdrop-blur-[40px] fixed left-0 bottom-0 w-full z-[9999] bothrefm-0 py-[12px] px-4 md:hidden">
    <!-- <a href="#">
        <div>
            <span class="relative cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mb-1 dark:text-white text-slate-900 ">
                <iconify-icon icon="heroicons-outline:mail"></iconify-icon>
                <span class="absolute right-[5px] lg:hrefp-0 -hrefp-2 h-4 w-4 bg-red-500 text-[8px] font-semibold flex flex-col items-center justify-center rounded-full text-white z-[99]">
                    10
                </span>
            </span>
            <span class="block text-[11px] text-slate-600 dark:text-slate-300">
                Messages
            </span>
        </div>
    </a> -->
    <a href="#" class="relative bg-white bg-no-repeat backdrop-filter backdrop-blur-[40px] rounded-full footer-bg dark:bg-slate-700 h-[65px] w-[65px] z-[-1] -mt-[40px] flex justify-center items-center">
        <div class="h-[50px] w-[50px] rounded-full relative left-[0px] hrefp-[0px] custom-dropshadow">
            <img src="<?php echo !empty($admin_detail->profile_pic) ? url('public/assets/admin/adminimages/' . $admin_detail->profile_pic) : url('public/assets/images/all-img/user.png'); ?>" alt="" class="w-full h-full rounded-full border-2 border-slate-100 profile-image">
        </div>
    </a>
    <!-- <a href="#">
        <div>
            <span class=" relative cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mb-1 dark:text-white text-slate-900">
                <iconify-icon icon="heroicons-outline:bell"></iconify-icon>
                <span class="absolute right-[17px] lg:hrefp-0 -hrefp-2 h-4 w-4 bg-red-500 text-[8px] font-semibold flex flex-col items-center justify-center rounded-full text-white z-[99]">
                    2
                </span>
            </span>
            <span class=" block text-[11px] text-slate-600 dark:text-slate-300">
                Notifications
            </span>
        </div>
    </a> -->
</div>
</div>

{{-- START: Modal --}}
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto md:inset-0 h-modal
    !items-start md:h-full backdrop-blur-sm transition-all duration-300"
    id="model_wrapper" tabindex="-1" aria-labelledby="model_wrapper" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl top-0 !-translate-y-0 relative w-auto pointer-events-none">
        <div class="modal-content modelWrapper border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">

        </div>
    </div>
</div>
{{-- END: Modal --}}

</main>
<!-- scripts -->
<script src="<?php echo url('public/assets/js/jquery-3.6.0.min.js'); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.0.0/tinymce.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.5/sweetalert2.min.js"></script>
<script src="<?php echo url('public/assets/js/rt-plugins.js'); ?>"></script>
<script src="<?php echo url('public/assets/js/step-form.js'); ?>"></script>
<script src="<?php echo url('public/assets/admin/event.js'); ?>"></script>
<script src="<?php echo url('public/assets/js/app.js'); ?>"></script>

<script src="https://unpkg.com/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://unpkg.com/tippy.js@6.3.2/dist/tippy-bundle.umd.min.js"></script>
<script>
    tippy('[data-tippy-content]')
</script>

<script>
    // tinymce.init({
    //     selector: 'textarea.tinymice', // Replace this CSS selector to match the placeholder element for TinyMCE
    //     plugins: 'code table lists image',
    //     toolbar: 'undo redo | formatselect| bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table image',
    //     height: 300,
    //     images_upload_credentials: true,
    // });
    
    tinymce.init({
        selector: 'textarea.tinymice',
        plugins: 'image print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
        imagetools_cors_hosts: ['picsum.photos'],
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        autosave_ask_before_unload: true,
        autosave_interval: "30s",
        autosave_prefix: "{path}{query}-{id}-",
        autosave_restore_when_empty: false,
        autosave_retention: "2m",
        image_advtab: true,
        forced_root_block: false,
        content_style: "#tinymce { margin-left: 10px; }",
        images_upload_credentials: true,
        
        content_css: 'mycontent.css', 
        /*content_css: '//www.tiny.cloud/css/codepen.min.css',*/
        link_list: [
            { title: 'My page 1', value: '' },
            { title: 'My page 2', value: '' }
        ],
        image_list: [
            { title: 'My page 1', value: '' },
            { title: 'My page 2', value: '' }
        ],
        image_class_list: [
            { title: 'my image', value: 'img-fluid' },
        
        ],
        importcss_append: true,
        file_picker_callback: function (callback, value, meta) {
            /* Provide file and text for the link dialog */
            if (meta.filetype === 'file') {
                callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
            }
        
            /* Provide image and alt text for the image dialog */
            if (meta.filetype === 'image') {
                callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
            }
        
            /* Provide alternative source and posted for the media dialog */
            if (meta.filetype === 'media') {
                callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
            }
        },
        templates: [
            { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
            { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
            { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
        ],
        template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
        template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
        height: 300,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_noneditable_class: "mceNonEditable",
        toolbar_mode: 'sliding',
        contextmenu: "link image imagetools table",
        content_css : 'writer',
        relative_urls : 0,
        remove_script_host : 0,
       
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        },
        
    });

    tinymce.init({
        selector: 'textarea.amenities',
        height: 150,
        menubar: false,
        plugins: 'link', // Include the 'link' plugin
        toolbar: 'undo redo | bold italic underline | numlist bullist | link', // Add the 'link' button
    });
</script>

<script>
    $(document).ready(function() {
        $(window).on("load", function() {
            // setTimeout(function(){
            $(".loderBody").fadeOut('slow');
            // },500)
        });
    });
</script>

<script>
    $(".selecttag").select2({
        tags: true,
        placeholder: "Type here..",
        width: '100%',
    });
</script>
<script>
    $(".selectTagBox").select2({
        tags: true,
        closeOnSelect : false,
        placeholder: "Select option..",
        width: '100%',
    });
</script>

</body>

</html>
