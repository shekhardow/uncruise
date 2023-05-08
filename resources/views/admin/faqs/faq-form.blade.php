<div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
    <!-- Modal header -->
    <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
        <h3 class="text-xl font-medium text-white dark:text-white capitalize">
            <?php echo !empty($faq_detail) ? 'Edit' : 'Add'; ?> FAQ
        </h3>
        <button type="reset" class="closeModal text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
            <span class="sr-only">Close modal</span>
        </button>
    </div>
    <!-- Modal body -->
    <div>
        <form id="submit-form" method="post" action="<?php echo !empty($faq_detail) ? route('admin/updateFaq', ['faq_id' => encryptionID($faq_detail->faq_id)]) : route('admin/addFaq'); ?>">
            <div class="p-6 space-y-6">
                <div class="form-group input-group">
                    <label for="question" class="text-sm font-Inter font-normal text-slate-900 block">Question :</label>
                    <input type="text" id="question" name="question" placeholder="Enter question" value="<?php echo !empty($faq_detail->question) ? $faq_detail->question : null; ?>" class="text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 focus:!outline-none focus:!ring-0 border !border-slate-400 rounded-md mt-2">
                </div>
                <div class="form-group input-group">
                    <label for="answer" class="text-sm font-Inter font-normal text-slate-900 block">Answer :</label>
                    <textarea rows="3" name="answer" id="answer" class="block w-full py-2 px-3 border border-gray-300 rounded-md tinymice">
                        <?php echo !empty($faq_detail->answer) ? $faq_detail->answer : null; ?>
                    </textarea>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center justify-end p-6 space-x-2 border-slate-200 rounded-b dark:border-slate-600">
                <button type="reset" class="btn inline-flex justify-center btn-outline-dark closeModal" data-bs-dismiss="modal">Cancel</button>
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
