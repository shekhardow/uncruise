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
    tinymce.init({
        selector: 'textarea.tinymice', // Replace this CSS selector to match the placeholder element for TinyMCE
        plugins: 'code table lists',
        toolbar: 'undo redo | formatselect| bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
        height: 300
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
    $(document).ready(function() {
        $('.selecttag').select2({
            placeholder: 'Please type...',
            dropdownAutoWidth : true,
            // width: '100%'
        });
    });
</script>

</body>

</html>
