<!-- BEGIN: Footer For Desktop and tab -->
<footer class="md:block hidden footerNewClass" id="footer">
    <div
        class="site-footer px-6 bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-300 py-4 ltr:ml-[248px] rtl:mr-[248px]">
        <div class="grid md:grid-cols-2 grid-cols-1 md:gap-5">
            <div class="text-center ltr:md:text-start rtl:md:text-right text-sm">
                Copyright © <span id="thisYear"></span> UnCruise Adventures, All rights Reserved
            </div>
            {{-- <div class="ltr:md:text-right rtl:md:text-end text-center text-sm">
                Hand-crafted &amp; Made by
                <a href="https://codeshaper.net" target="_blank" class="text-primary-500 font-semibold">
                    Codeshaper
                </a>
            </div> --}}
        </div>
    </div>
</footer>
<!-- END: Footer For Desktop and tab -->

<div
    class="bg-white bg-no-repeat custom-dropshadow footer-bg dark:bg-slate-700 flex justify-around items-center backdrop-filter backdrop-blur-[40px] fixed left-0 bottom-0 w-full z-[9999] bothrefm-0 py-[12px] px-4 md:hidden">
    <a href="#">
        <div>
            <span
                class="relative cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mb-1 dark:text-white text-slate-900 ">
                <iconify-icon icon="heroicons-outline:mail"></iconify-icon>
                <span
                    class="absolute right-[5px] lg:hrefp-0 -hrefp-2 h-4 w-4 bg-red-500 text-[8px] font-semibold flex flex-col items-center justify-center rounded-full text-white z-[99]">
                    10
                </span>
            </span>
            <span class="block text-[11px] text-slate-600 dark:text-slate-300">
                Messages
            </span>
        </div>
    </a>
    <a href="#"
        class="relative bg-white bg-no-repeat backdrop-filter backdrop-blur-[40px] rounded-full footer-bg dark:bg-slate-700 h-[65px] w-[65px] z-[-1] -mt-[40px] flex justify-center items-center">
        <div class="h-[50px] w-[50px] rounded-full relative left-[0px] hrefp-[0px] custom-dropshadow">
            <img src="<?php echo url('public/assets/images/users/user-1.jpg'); ?>" alt="" class="w-full h-full rounded-full border-2 border-slate-100">
        </div>
    </a>
    <a href="#">
        <div>
            <span
                class=" relative cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mb-1 dark:text-white text-slate-900">
                <iconify-icon icon="heroicons-outline:bell"></iconify-icon>
                <span
                    class="absolute right-[17px] lg:hrefp-0 -hrefp-2 h-4 w-4 bg-red-500 text-[8px] font-semibold flex flex-col items-center justify-center rounded-full text-white z-[99]">
                    2
                </span>
            </span>
            <span class=" block text-[11px] text-slate-600 dark:text-slate-300">
                Notifications
            </span>
        </div>
    </a>
</div>
</div>

{{-- START: Disabled Animation Modal --}}
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto md:inset-0 h-modal
    !items-start md:h-full backdrop-blur-sm transition-all duration-300"
    id="disabled_animation" tabindex="-1" aria-labelledby="disabled_animation" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl top-0 !-translate-y-0 relative w-auto pointer-events-none">
        <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                    rounded-md outline-none text-current">
            <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                    <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                        Vertically Center
                    </h3>
                    <button type="button"
                        class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                dark:hover:bg-slate-600 dark:hover:text-white"
                        data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                        11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div>
                    <form>
                        <div class="p-6 space-y-6">
                            <div class="input-group">
                                <label for="name"
                                    class="text-sm font-Inter font-normal text-slate-900 block">Name:</label>
                                <input type="text" id="name"
                                    class="text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 focus:!outline-none focus:!ring-0 border
                                !border-slate-400 rounded-md mt-2"
                                    placeholder="Type Your Email">
                            </div>
                            <div class="input-group">
                                <label for="password"
                                    class="text-sm font-Inter font-normal text-slate-900 block">Password</label>
                                <div class="relative" id="passwordInputField">
                                    <input type="password" id="password"
                                        class="passwordfield text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 pr-9 focus:!outline-none
                                        focus:!ring-0 border !border-slate-400 rounded-md mt-2"
                                        placeholder="Type Your Password" autocomplete="off">
                                    <span
                                        class="text-xl text-slate-400 absolute top-1/2 -translate-y-1/2 right-3 cursor-pointer"
                                        id="toggleIcon">
                                        <iconify-icon id="hidePassword" icon="heroicons-outline:eye-off"></iconify-icon>
                                        <iconify-icon class="hidden" id="showPassword" icon="heroicons-outline:eye">
                                        </iconify-icon>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                            <button data-bs-dismiss="modal" type="button"
                                class="btn inline-flex justify-center btn-outline-dark">Close</button>
                            <button data-modal-hide="disabled_animation" type="submit"
                                class="btn inline-flex justify-center text-white bg-black-500">Log In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- END: Disabled Animation Modal --}}

{{-- <button data-bs-toggle="modal" data-bs-target="#disabled_animation" class="btn inline-flex justify-center btn-outline-dark">Disabled Animation Modal</button> --}}
</main>
<!-- scripts -->
<script src="<?php echo url('public/assets/js/jquery-3.6.0.min.js'); ?>"></script>
<script src="<?php echo url('public/assets/js/rt-plugins.js'); ?>"></script>
<script src="<?php echo url('public/assets/js/step-form.js'); ?>"></script>
<script src="<?php echo url('public/assets/js/app.js'); ?>"></script>
<script src="<?php echo url('public/assets/admin/event.js')?>"></script>
<script src="https://unpkg.com/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://unpkg.com/tippy.js@6.3.2/dist/tippy-bundle.umd.min.js"></script>
<script>
  tippy('[data-tippy-content]')
</script>

</body>

</html>
