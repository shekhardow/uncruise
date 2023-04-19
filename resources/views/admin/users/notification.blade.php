<style>
    .tinymce {
        height: 150px;
    }
</style>
<div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
    <!-- Modal header -->
    <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
        <h3 class="text-xl font-medium text-white dark:text-white capitalize">
            <?php echo !empty($faq_detail) ? 'Edit' : 'Add'; ?> FAQ
        </h3>
        <button type="reset" id="closeModal"
            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
            <span class="sr-only">Close modal</span>
        </button>
    </div>
    <!-- Modal body -->
    <div>
        <form id="submit-form" method="post" action="<?php echo route('admin/sendNotification'); ?>">
            <div class="p-6 space-y-6">
                <div class="form-group input-group">
                    <label for="subject" class="text-sm font-Inter font-normal text-slate-900 block">Subject :</label>
                    <input type="text" id="subject" name="subject" placeholder="Add Subject" class="text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 focus:!outline-none focus:!ring-0 border !border-slate-400 rounded-md mt-2">
                </div>
                <div class="form-group input-group">
                    <label for="message" class="text-sm font-Inter font-normal text-slate-900 block">Message :</label>
                    <textarea rows="3" id="message" name="message" class="block w-full py-2 px-3 border border-gray-300 rounded-md tinymice"></textarea>
                </div>
                <div class="form-group input-group">
                    <?php if(!empty($user_id)){ foreach($user_id as $id){ ?>
                        <input type="hidden" name="user_id[]" value="<?php echo $id;?>">
                    <?php }} ?>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center justify-end p-6 space-x-2 border-slate-200 rounded-b dark:border-slate-600">
                <button type="reset" class="btn inline-flex justify-center btn-outline-dark">Reset</button>
                <button type="submit" id="submit-btn" class="btn inline-flex justify-center text-white bg-black-500">
                    <?php echo !empty($faq_detail) ? 'Update' : 'Submit'; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://unpkg.com/tippy.js@6.3.2/dist/tippy-bundle.umd.min.js"></script>
<script>
    tippy('[data-tippy-content]')
</script>

<script>
    tinymce.init({
        selector: 'textarea.tinymice', // Replace this CSS selector to match the placeholder element for TinyMCE
        plugins: 'code table lists',
        toolbar: 'undo redo | formatselect| bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
    });
</script>
