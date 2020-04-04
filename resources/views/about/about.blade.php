@extends('layouts.frontend')

@section('content')

<div class="row my-4">
    <div class="col-md-12">
        <h1>ACT<h1>
        <p>
        {{ $email }} {{ $tel }}<br>
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Minima, quo aspernatur beatae perferendis temporibus molestias nihil repellendus ipsa cupiditate quod exercitationem corrupti facere fugit dolorem atque nam excepturi repellat ad.
        </p>
        @foreach ($drinks as $drink)
            <ul>
                <li>{{$drink}}</li>
            </ul>
        @endforeach
    </div>

</div>
@endsection

@section('footerscript')
{{-- <script>
    alert('about');
</script> --}}
@endsection
