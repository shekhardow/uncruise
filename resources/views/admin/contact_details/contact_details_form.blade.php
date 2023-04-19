<div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
    <!-- Modal header -->
    <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
        <h3 class="text-xl font-medium text-white dark:text-white capitalize">
            Edit Contact Details
        </h3>
        <button type="reset"
            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
            <span class="sr-only" id="closeModal">Close modal</span>
        </button>
    </div>
    <!-- Modal body -->
    <div>
        <form id="submit-form" method="post" action="<?php echo route('admin/updateContactDetails', ['contact_detail_id' => encryptionID($contact_details->contact_detail_id)]); ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                <div class="form-group input-group">
                    <label for="company_name" class="text-sm font-Inter font-normal text-slate-900 block">Company Name :</label>
                    <input type="text" id="company_name" name="company_name" placeholder="Enter company name" value="<?php echo !empty($contact_details->company_name) ? $contact_details->company_name : null; ?>" class="text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 focus:!outline-none focus:!ring-0 border !border-slate-400 rounded-md mt-2">
                </div>
                <div class="form-group input-group">
                    <label for="address" class="text-sm font-Inter font-normal text-slate-900 block">Address :</label>
                    <input type="text" id="address" name="address" placeholder="Enter address" value="<?php echo !empty($contact_details->address) ? $contact_details->address : null; ?>" class="text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 focus:!outline-none focus:!ring-0 border !border-slate-400 rounded-md mt-2">
                </div>
                <div class="form-group input-group">
                    <label for="contact_no1" class="text-sm font-Inter font-normal text-slate-900 block">Contact 1 :</label>
                    <input type="text" id="contact_no1" name="contact_no1" placeholder="Enter contact no 1" value="<?php echo !empty($contact_details->contact_no1) ? $contact_details->contact_no1 : null; ?>" class="text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 focus:!outline-none focus:!ring-0 border !border-slate-400 rounded-md mt-2">
                </div>
                <div class="form-group input-group">
                    <label for="contact_no2" class="text-sm font-Inter font-normal text-slate-900 block">Contact 2 :</label>
                    <input type="text" id="contact_no2" name="contact_no2" placeholder="Enter contact no 2" value="<?php echo !empty($contact_details->contact_no2) ? $contact_details->contact_no2 : null; ?>" class="text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 focus:!outline-none focus:!ring-0 border !border-slate-400 rounded-md mt-2">
                </div>
                <div class="form-group input-group">
                    <label for="email1" class="text-sm font-Inter font-normal text-slate-900 block">Email Id 1 :</label>
                    <input type="email" id="email1" name="email1" placeholder="Enter email 1" value="<?php echo !empty($contact_details->email1) ? $contact_details->email1 : null; ?>" class="text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 focus:!outline-none focus:!ring-0 border !border-slate-400 rounded-md mt-2">
                </div>
                <div class="form-group input-group">
                    <label for="email2" class="text-sm font-Inter font-normal text-slate-900 block">Email Id 2 :</label>
                    <input type="email" id="email2" name="email2" placeholder="Enter email 2" value="<?php echo !empty($contact_details->email2) ? $contact_details->email2 : null; ?>" class="text-sm font-Inter font-normal text-slate-600 block w-full py-3 px-4 focus:!outline-none focus:!ring-0 border !border-slate-400 rounded-md mt-2">
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center justify-end p-6 space-x-2 border-slate-200 rounded-b dark:border-slate-600">
                <button type="reset" class="btn inline-flex justify-center btn-outline-dark">Reset</button>
                <button type="submit" id="submit-btn" class="btn inline-flex justify-center text-white bg-black-500">
                    Update
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
