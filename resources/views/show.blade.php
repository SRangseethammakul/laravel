@extends('layouts.frontend')

@section('content')
<div class="row">

    <div class="col-lg-3">

      <h1 class="my-4">สินค้า</h1>
      <div class="list-group">
        {{-- <a href="{{route('product',['id'=>1])}}" class="list-group-item">Category 1</a> --}}
        @foreach ($category_All as $item)
            <a href="{{ route('welcome.show',['id' => $item->id]) }}" class="list-group-item">{{$item->name}}</a>
        @endforeach

      </div>

    </div>
    <!-- /.col-lg-3 -->

    <div class="col-lg-9">

    <h1 class="my-4">{{$category->name}}</h1>
      <div class="row">
    @foreach ($category->products as $item)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
            <a href="#"><img class="card-img-top" src="{{ asset('storage/image/'.$item->picture)}}" alt=""></a>
            <div class="card-body">
                <h4 class="card-title">
                    <a href="#">{{$item->name}}</a>
                </h4>
                <br>
                <h5>{{$item->price}}</h5>
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet numquam aspernatur!</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('cart.store',['product_id'=>$item->id]) }}" class="btn btn-info">หยิบใส่ตะกร้า</a>
            </div>
            </div>
        </div>
    @endforeach
    </div>

      <!-- /.row -->

    </div>
    <!-- /.col-lg-9 -->

  </div>
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
