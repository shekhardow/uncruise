@extends('admin/layout')
@section('content')
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Contact Details
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Company Name</th>
                            <th>Address</th>
                            <th>Contact No 1</th>
                            <th>Contact No 2</th>
                            <th>Email 1</th>
                            <th>Email 2</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="width:auto;">1</td>
                            <td style="width:auto;"><?php echo !empty($contact_detail->company_name) ? $contact_detail->company_name : "NULL"; ?></td>
                            <td style="width:auto;"><?php echo !empty($contact_detail->address) ? $contact_detail->address : "NULL"; ?></td>
                            <td style="width:auto;"><?php echo !empty($contact_detail->contact_no1) ? $contact_detail->contact_no1 : "NULL"; ?></td>
                            <td style="width:auto;"><?php echo !empty($contact_detail->contact_no2) ? $contact_detail->contact_no2 : "NULL"; ?></td>
                            <td style="width:auto;"><?php echo !empty($contact_detail->email1) ? $contact_detail->email1 : "NULL"; ?></td>
                            <td style="width:auto;"><?php echo !empty($contact_detail->email2) ? $contact_detail->email2 : "NULL"; ?></td>
                            <td style="width: 8%">
                                <a href="{{url('admin/update-contact')}}" class="m-badge btn m-badge--info m-badge--wide" title="Edit Contact Details"><i class="fa fa-edit"></i> Edit</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection