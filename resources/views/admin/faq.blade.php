@extends('admin/layout')
@section('content')
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- END: Subheader -->
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                           Faqs
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                            <a href="{{url('admin/faqform')}}" class="model_wrapper btn btn-accent m-btn--pill m-btn--custom m-btn--icon m-btn--air">
                                <span>
                                    <i class="la la-plus"></i>
                                    <span>Add</span>
                                </span>
                            </a>
                        </li>
                        <li class="m-portlet__nav-item"></li>
                    </ul>
                    {{-- <ul class="m-portlet__nav">
                        <a href="{{url('admin/admin/notifiction')}}" class="send_totification btn btn-accent m-btn--pill m-btn--custom m-btn--icon m-btn--air">
                                <span>
                                    <i class="la la-bell"></i>
                                    <span>Send Notification</span>
                                </span>
                            </a>
                        <li class="m-portlet__nav-item"></li>
                    </ul> --}}
                </div>
            </div>
            <div class="m-portlet__body">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                    <thead>
                        <tr>
                            <!-- <th><input type="checkbox" class="check"/></th> -->
                            {{-- <th>
                                <label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand">
                                    <input type="checkbox" value="" class="m-checkable check">
                                    <span></span>
                                </label>
                            </th> --}}
                            <th>S.No.</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($faq))
                        <?php $i = 1;?>
                        @foreach ($faq as $faqs)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$faqs->question}}</td>
                            <td>{{$faqs->answer}}</td>
                            <td style="width: 8%">
                            <a href="{{url('admin/delete_faq/'.$faqs->id)}}" data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-sm" title="Delete Faq"><i class="fa fa-trash"></i></a>
                            <a href="{{url('admin/faqform/'.$faqs->id)}}" data-toggle="tooltip" data-placement="top" class="btn btn-success btn-sm " title="Edit Faq"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                        <?php $i++;?> 
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
