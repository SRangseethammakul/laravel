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
                            <h3>ประเภทสินค้าทั้งหมด {{$countrow}} รายการ</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th scope="col">รหัสสินค้า</th>
                                    <th scope="col">หมวดสินค้า</th>
                                    <th scope="col">วันที่เพิ่ม</th>
                                    <th scope="col">วันที่แก้ไข</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach ($category as $item)
                                        <tr>
                                            <th scope="row">{{ $item->id}}</th>
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->created_at}}</td>
                                            <td>{{$item->updated_at}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                              </table>
                        </div>
                    </div>
                    {{-- end row --}}
                </div>
            </section>
            <!-- /.content -->

@endsection
