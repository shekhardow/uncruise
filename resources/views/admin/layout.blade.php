@include('admin/common/header')
@if (@$title != 'Login')
    @include('admin/common/sidebar')
    {{-- @include('admin/common/setting') --}}
@endif
@section('content')

@show
@include('admin/common/footer')
