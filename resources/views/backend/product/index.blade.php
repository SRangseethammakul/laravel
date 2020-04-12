@extends('layouts.backend')

@section('content')

            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Dashboard</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard v2</li>
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
                        <div class="card-header">
                            <h3>สินค้าทั้งหมด {{ $products->total() }} รายการ</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card-body">
                            <a href="{{ route('product.create')}}" class = "btn btn-info btn-lg">เพิ่มสินค้า</a>
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th scope="col">รหัสสินค้า</th>
                                    <th scope="col">ชื่อสินค้า</th>
                                    <th scope="col">ราคา</th>
                                    <th scope="col">vat</th>
                                    <th scope="col">รูปภาพ</th>
                                    <th scope="col">ประเภท</th>
                                    <th scope="col">เครื่องมือ</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $item)
                                        <tr>
                                            <th scope="row">{{ $item->id}}</th>
                                            <td>{{Str::limit($item->name,20)}}</td>
                                            <td>{{number_format($item->price,2)}}</td>
                                            <td>{{$item->vatproduct}}</td>
                                            <td>
                                                <img src="{{ asset('storage/image/'.$item->picture) }}" width="60">
                                            </td>
                                            <td>{{$item->category->name}}</td>
                                            <td>
                                                <a href="{{ route('product.edit',['id'=>$item->id])}}" class="btn btn-info mr-2">
                                                    <li class="fa fa-pencil text-white"></li>
                                                </a>
                                                <form method="POST" class="d-inline" action="{{ route('product.destroy',['id'=>$item->id]) }}"
                                                    onsubmit="return confirm('แน่ใจว่าต้องการลบข้อมูล')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger">
                                                        <li class="fa fa-trash text-white"></li>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                              </table>

                              <br>
                              {{$products->links()}}
                        </div>
                    </div>
                    {{-- end row --}}
                </div>
            </section>
            <!-- /.content -->

@endsection


@section('footerscript')
    @if(session('feedback'))
        <script src="{{ asset('js/sweetalert2.all.min.js')}}"></script>
        <script>
            Swal.fire(
                '{{ session('feedback')}}', //
                'You clicked the button!',
                'success'
            )
        </script>
    @endif
@endsection
