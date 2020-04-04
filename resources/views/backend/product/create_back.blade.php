@extends('layouts.backend')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">เพิ่มสินค้า</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">เพิ่มสินค้า</li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <br>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
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

                            <form method="post" action="{{ route('product.store')}}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="name">Product Name</label>
                                    <input name="name" type="text" class="form-control" id="name" aria-describedby="nameHelp">
                                </div>

                                <div class="form-group">
                                    <label for="price">Product Price</label>
                                    <input name="price" type="text" class="form-control" id="price" aria-describedby="priceHelp">
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Category</label>
                                    {{Form::select('category_id', $categories->pluck('name','id'), null, ['placeholder' => 'Plase Select Category', 'class'=>"form-control"])}}
                                </div>

                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="picture" name="picture">
                                    <label class="custom-file-label" for="validatedCustomFile">Choose picture...</label>
                                    {{-- <div class="invalid-feedback">Example invalid custom file feedback</div> --}}
                                </div>

                                <button type="submit" class="btn btn-primary mt-3">Save</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

@endsection
