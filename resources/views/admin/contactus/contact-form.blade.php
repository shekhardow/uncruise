@extends('admin/layout')
@section('content')
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="m-portlet">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                        Edit Contact Details
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <form method="post" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed" id="submit-form" action="<?php echo url('admin/do-update-contact'); ?>">
                            @csrf
                            <div class="m-portlet__body">
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-4">
                                        <label>Company Name</label>
                                        <input type="text" name="company_name" id="company_name" class="form-control" value="<?php echo !empty($contact_detail->company_name) ? $contact_detail->company_name : NULL?>">
                                    </div>
                                    <div class="col-lg-4">
                                        <label>Email 1</label>
                                        <input type="email" name="email1" id="email1" class="form-control" value="<?php echo !empty($contact_detail->email1) ? $contact_detail->email1 : NULL?>">
                                    </div>
                                    <div class="col-lg-4">
                                        <label>Email 2</label>
                                        <input type="email" name="email2" id="email2" class="form-control" value="<?php echo !empty($contact_detail->email2) ? $contact_detail->email2 : NULL?>">
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-4">
                                        <label>Contact No 1</label>
                                        <input type="text" name="contact_no1" id="contact_no1" class="form-control" value="<?php echo !empty($contact_detail->contact_no1) ? $contact_detail->contact_no1 : NULL?>">
                                    </div>
                                    <div class="col-lg-4">
                                        <label>Contact No 2</label>
                                        <input type="text" name="contact_no2" id="contact_no2" class="form-control" value="<?php echo !empty($contact_detail->contact_no2) ? $contact_detail->contact_no2 : NULL?>">
                                    </div>
                                </div>
                                <div class="form-group m-form__group row discription_first">
                                <div class="col-lg-12">
                                        <label>Address</label>
                                        <textarea class="summernote" name="address" id="address"><?php echo !empty($contact_detail->address) ? $contact_detail->address : NULL?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                                <div class="m-form__actions m-form__actions--solid">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <a href="<?php echo url('admin/contactus'); ?>" class="btn btn-danger">Cancel</a>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  
@endsection