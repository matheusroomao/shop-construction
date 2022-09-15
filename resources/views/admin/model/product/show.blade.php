@extends('layout.master2')


@section('content')

<nav class="navbar navbar-expand-lg ">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="{{ route('admin.product.index') }}">Produtos</a>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">

        </div>
    </div>
</nav>


<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none mb-2">
            <div class="row">
                <div class="col text-center">
                    @if($model->picture() != null)
                    <img class="d-flex align-items-center text-white text-decoration-none">
                    <img src="{{ $model->picture }}" class="img-thumbnail" alt="60"></img>
                    @else
                    <img class="d-flex align-items-center text-white text-decoration-none">
                    <img src="{{ url('img/placeholder.png') }}" class="img-thumbnail" alt="60"></img>
                    @endif
                </div>
                <div class="col text-center">
                    <span class="card-text mb-0">
                        <b>{{ $model->name }}</b>
                    </span>
                    <p class="text-start">{{$model->description}}</p>

                </div>
                <div class="col text-center">
                    <span class="card-text mb-0">
                        Preço:
                    </span>
                    <h5> R${{(number_format($model->value,2,',','.')) }}</h5>
                </div>
                <div class="col text-center">
                    <span class="card-text mb-0">
                        Marca: {{ $model->brand->name }}
                    </span>
                    <p class="card-text mb-0 text-secondary"> Disponíveis: {{ $model->quantyty }} </p>
                </div>
            </div>
        </div>
        @if($model->quantyty >= 1)
        <form class="row g-3" method="post" action="{{ route('admin.user.product.store') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{$model->id}}">
            <div class="col-auto">
                <input type="number" min="1" name="quantyty"  class="form-control" id="quantyty" placeholder="Quantidade">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success mb-3">Comprar</button>
            </div>
        </form>
        @endif
    </div>

</div>



@endsection