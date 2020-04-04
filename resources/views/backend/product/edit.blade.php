﻿@extends('layouts.backend')
@section('content')

<div class="card mx-1 my-2">
    <div class="card-header">
        <h3 class="card-title">แก้ไขสินค้า -> {{$product->name}} </h3>
    </div>

    <div class="card-body">

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {!! Form::model($product, ['novalidate','route' => ['product.update',$product->id], 'method' => 'put', 'files' => true,'class'=> ($errors->any()) ? 'was-validated' : 'needs-validation']) !!}
        {{-- <form novalidate class="{{ ($errors->any()) ? 'was-validated' : 'needs-validation' }}" method="post" enctype="multipart/form-data"
            action="{{ route('product.store') }}">
            @csrf --}}

            <div class="form-group">
                    <label for="exampleFormControlInput1">ชื่อสินค้า</label> {{ Form::text('name', null,['class'=>'form-control ',
                    'required']) }}
                    @if ($errors->has('name'))
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                    @endif
            </div>

            <div class="form-group">
                <label for="exampleFormControlInput1">ราคา</label> {{ Form::text('price', null,['class'=>'form-control ',
                'required']) }}
                @if ($errors->has('price'))
                <div class="invalid-feedback">{{ $errors->first('price') }}</div>
                @endif
            </div>

            <div class="form-group">
                    <label for="exampleFormControlSelect1">เลือกหมวดสินค้า</label> {{ Form::select('category_id', App\Category::all()->pluck('name',
                    'id'), null, ['class' => 'selectpicker form-control', 'placeholder' => 'กรุณาเลือกหมวดสินค้า...', 'required','data-live-search="true"']) }}
                    @if ($errors->has('category_id'))
                    <div class="invalid-feedback">{{ $errors->first('category_id') }}</div>
                    @endif
                </div>

            <div class="form-group">
            <img src="{{ asset('storage/image/'.$product->picture) }}">
            </div>

            <div class="custom-file">
                <input name="picture" type="file" class="custom-file-input" id="customFile" {{ $errors->has('picture') ? ' required' : ' ' }}>
                <label class="custom-file-label" for="customFile">เแก้ไขูปภาพใหม่</label>
                @if ($errors->has('picture'))
                    @foreach ($errors->get('picture') as $message)
                        <div class="invalid-feedback">{{  $message }}</div>
                    @endforeach
                @endif

            </div>


            <button type="submit" class="btn btn-primary mt-3">บันทึก</button>

        </form>


    </div>
    <!-- /.card-body -->
</div>
@endsection


@section('footerscript')

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

<script>
    $('select').selectpicker();
</script>
@endsection
