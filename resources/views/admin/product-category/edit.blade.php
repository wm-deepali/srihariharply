@include('admin.top-header')
<div class="main-section">
    @include('admin.header')
    @include('admin.product-category._form', ['category' => $category])
</div>
@include('admin.footer')