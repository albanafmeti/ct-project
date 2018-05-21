@extends('layouts.app')

@section('main-content')
    <div>

        <div class="card mb-4">
            <div class="card-header">Add new product</div>
            <div class="card-body">
                <form-component
                    add-product-url="{{ route('ajax.product.store') }}"></form-component>
            </div>
        </div>

        <div class="card">
            <div class="card-header">List of products</div>
            <div class="card-body">
                <product-component
                    products-url="{{ route('ajax.product.index') }}"
                    delete-product-url="{{ route('ajax.product.delete', ['__id__']) }}"></product-component>

                <modal-component edit-product-url="{{ route('ajax.product.update', ['__id__']) }}"></modal-component>
            </div>
        </div>
    </div>
@endsection
