<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Send Notification</h5>
            <button type="button" class="close reload" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="error_msg"></div>
            <form id="submit-form" method="post" enctype="multipart/form-data"
             action="<?php echo url('admin/sendNotficationToAll');?>">
                <div class="form-group m-form__group row">
                    <div class="col-lg-12 validate">
                        <label>Subject</label>
                        <input text="text" name="subject" id="subject"  class="form-control m-input">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-12 validate">
                        <label>Message</label>
                        <textarea name="message" id="message"  class="form-control m-input "></textarea>
                    </div>
                    <?php
                    if(!empty($user_id)){
                     foreach($user_id as $id){?>
                        <input type="hidden" name="user_id[]" value="<?php echo $id;?>">
                    <?php }
                    }?>
                </div>
                <div class="modal-footer">
                    <button class="reload btn btn-danger">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>