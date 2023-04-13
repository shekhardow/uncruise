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
                                <span class="m-portlet__head-icon m--hide"><i class="la la-gear"></i></span>
                                <h3 class="m-portlet__head-text">{{$title}}</h3>
                            </div>
                        </div>
                    </div>
                    <form  method="post" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed" id="submit-form" action="{{url('admin/update_site_setting')}}">
                        @csrf
                        <div class="m-portlet__body">
                            <div class="form-group m-form__group row">
                                <div class="col-lg-12">
                                    <label>{{$title}} Content :</label>
                                    <input type="hidden" name="type" value="<?php echo $type; ?>">
                                    <textarea name="description" id="description" class="summernote"><?php echo !empty($site_setting->description) ? $site_setting->description : NULL ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                            <div class="m-form__actions m-form__actions--solid">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <!-- <button type="reset" class="btn btn-primary">Save</button> -->
                                    </div>
                                    <div class="col-lg-6 m--align-right">
                                        <button type="submit" class="btn btn-primary">Update</button>
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
