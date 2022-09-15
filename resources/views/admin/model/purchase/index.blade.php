@extends('layout.master2')


@section('content')

<nav class="navbar navbar-expand-lg ">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">Compras</a>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                </li>

            </ul>
            <form class="d-flex" role="search">
                <input type="text" class="form-control me-2" id="name" name="search" placeholder="Buscar" autofocus value="{{ request()->get('search') }}">
                <button class="btn btn-outline-secondary me-2" type="submit">Filtrar</button>
            </form>
        </div>
    </div>
</nav>


<div class="row">
    <div class="col-lg-12">
        @foreach($models as $model)
        <div class="card shadow-none mb-2">
            <div class="card-body row p-2">
                <div class="col text-truncate">
                    <span class="card-text mb-0">
                        Preço:
                    </span>
                    <p class="card-text mb-0 text-secondary">
                        R${{(number_format($model->amount,2,',','.')) }}
                    </p>
                </div>

                <div class="col text-truncate">
                    <span class="card-text mb-0">
                        Compra realizada em:
                    </span>
                    <p class="card-text mb-0 text-secondary">
                        {{ $model->created_at->format('d/m/Y') }} as {{ $model->created_at->format('H:i') }}
                    </p>
                </div>

                <div class="col text-truncate">
                    <span class="card-text mb-0  text-info">
                        {{ $model->status }}
                    </span>
                </div>

                <div class="col d-flex justify-content-end">
                    <div>
                        <a href="{{ route('admin.purchase.export',$model->id) }}" target="_blank" class="btn btn-default">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if(auth()->user()->type == "PROVIDER")
                        <button type="button" class="btn btn-default" data-bs-toggle="modal" data-bs-target="#modaldelete{{$model->id}}">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                        @if($model->status == "NOVO")
                        <button type="button" class="btn btn-default" data-bs-toggle="modal" data-bs-target="#modalaprov{{$model->id}}">
                            <i class="bi bi-check-square-fill"></i>
                        </button>
                        <button type="button" class="btn btn-default" data-bs-toggle="modal" data-bs-target="#modalrepro{{$model->id}}">
                            <i class="bi bi-x-square-fill"></i>
                        </button>
                        @endif

                        @endif
                        <form action="{{ route('admin.purchase.destroy',$model->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <div class="modal fade" id="modaldelete{{$model->id}}" tabindex="-1" aria-labelledby="modaldeletetitle{{$model->id}}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modaldeletetitle{{$model->id}}">Apagar Registro</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Você tem certeza disso?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                                            <button type="submit" class="btn btn-primary">Sim, apagar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>


                        <div class="col d-flex justify-content-end">
                            <div>
                                <form action="{{ route('admin.purchase.update',$model->id) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal fade" id="modalaprov{{$model->id}}" tabindex="-1" aria-labelledby="modalaprov{{$model->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable">
                                            <input type="hidden" name="status" value="APROVADO">

                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalaprov{{$model->id}}">Deseja aprovar a compra ?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Você tem certeza disso?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                                                    <button type="submit" class="btn btn-primary">Sim</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form action="{{ route('admin.purchase.update',$model->id) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal fade" id="modalrepro{{$model->id}}" tabindex="-1" aria-labelledby="modalrepro{{$model->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable">
                                            <input type="hidden" name="status" value="REPROVADO">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalrepro{{$model->id}}">Dejesa reprovar a compra ?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Você tem certeza disso?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                                                    <button type="submit" class="btn btn-primary">Sim</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@if ($models->hasPages())
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item">
            <a class="page-link" href="{{ $models->previousPageUrl() }}">Previous</a>
        </li>

        <li class="page-item">
            <a class="page-link" href="{{ $models->nextPageUrl() }}">Next</a>
        </li>
    </ul>
</nav>
@endif


@endsection