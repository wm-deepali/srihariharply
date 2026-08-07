@include('admin.top-header')

<div class="main-section">
    @include('admin.header')

    @include('admin.our-services._form',['service' => $service])

</div>

@include('admin.footer')