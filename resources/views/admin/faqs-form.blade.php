<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?php if(!empty($faq_detail)){ echo "Edit";}else{ echo "Add";}?>Faq</h5>
            <button type="button" class="close reload" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="error_msg"></div>
            <form id="submit-form" method="post" enctype="multipart/form-data"
             action="<?php 
                if(!empty($faq_detail)){ 
                    echo url('admin/update_faq/'.$faq_detail->id);
                }else{
                    echo url('admin/add_faq');
                }?>" >
                @csrf
                <div class="form-group m-form__group row">
                    <div class="col-lg-12 validate">
                        <label for="title" class="form-control-label">Question</label>
                        <input type="text" class="form-control m-input" id="question" name="question" value="<?php echo !empty($faq_detail->question) ? $faq_detail->question : NULL;?>" placeholder="Enter Faq">
                    </div>
                
                    <div class="col-lg-12 validate">
                        <label>Answer</label>
                        <textarea name="answer" id="posted_by" value="<?php echo !empty($faq_detail->answers) ? $faq_detail->answers : NULL;?>" class="form-control m-input summernote" placeholder="Enter Answers">
                            <?php echo !empty($faq_detail->answers) ? $faq_detail->answers : NULL;?>
                            
                        </textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button class="reload btn btn-danger">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>