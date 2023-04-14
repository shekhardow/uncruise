<div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
    <!-- Modal header -->
    <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
        <h3 class="text-xl font-medium text-white dark:text-white capitalize">
            Vertically Center
        </h3>
        <button type="button"
            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                    dark:hover:bg-slate-600 dark:hover:text-white"
            data-bs-dismiss="modal">
            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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
                    <label for="name" class="text-sm font-Inter font-normal text-slate-900 block">Name:</label>
                    <input type="text" id="name"
                        class="text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 focus:!outline-none focus:!ring-0 border
                    !border-slate-400 rounded-md mt-2"
                        placeholder="Type Your Email">
                </div>
                <div class="input-group">
                    <label for="password" class="text-sm font-Inter font-normal text-slate-900 block">Password</label>
                    <div class="relative" id="passwordInputField">
                        <input type="password" id="password"
                            class="passwordfield text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 pr-9 focus:!outline-none
                            focus:!ring-0 border !border-slate-400 rounded-md mt-2"
                            placeholder="Type Your Password" autocomplete="off">
                        <span class="text-xl text-slate-400 absolute top-1/2 -translate-y-1/2 right-3 cursor-pointer" id="toggleIcon">
                            <iconify-icon id="hidePassword" icon="heroicons-outline:eye-off"></iconify-icon>
                            <iconify-icon class="hidden" id="showPassword" icon="heroicons-outline:eye"></iconify-icon>
                        </span>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                <button data-bs-dismiss="modal" type="button" class="btn inline-flex justify-center btn-outline-dark">Close</button>
                <button data-modal-hide="disabled_animation" type="submit" class="btn inline-flex justify-center text-white bg-black-500">Log In</button>
            </div>
        </form>
    </div>
</div>
