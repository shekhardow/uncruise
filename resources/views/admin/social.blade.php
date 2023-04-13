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
                                        Social
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <form class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed"
                        id="submit-form" method="post" action="{{url('admin/update-social-link')}}">
                        @csrf
                            <div class="m-portlet__body">
                                <div class="error_msg" id="error_msg"></div>
                                <div class="form-group m-form__group row">
                                    <?php foreach($social_link as $key => $value){ if(!empty($social_data)){ foreach($social_data as $social){ if($social->social_name == $value){ ?>
                                        <div class="col-lg-6 mb-5">
                                            <label>{{$value}}:</label>
                                            <input type="hidden" name="social_name[]" value="{{$value}}">
                                            <input type="url" name="social_link[]" class="form-control m-input" value="{{$social->social_link}}" placeholder="Enter {{$value}} Link">
                                            <span class="m-form__help">Please enter your {{$value}} Link</span>
                                        </div>
                                    <?php } } }else{?>
                                        <div class="col-lg-6 mb-5">
                                            <label>{{$value}}:</label>
                                            <input type="hidden" name="social_name[]"  value="{{$value}}">
                                            <input type="url" name="social_link[]" class="form-control m-input"  placeholder="Enter {{$value}} Link">
                                            <span class="m-form__help">Please enter your {{$value}} Link</span>
                                        </div>
                                    <?php } } ?>
                                </div>
                            </div>
                            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                                <div class="m-form__actions m-form__actions--solid">
                                    <div class="row">
                                        <div class="col-lg-6">
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