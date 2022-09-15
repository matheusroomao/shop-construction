<?php

namespace App\Repository\Business;

use App\Models\Product;
use App\Models\UserProduct;
use App\Repository\Contracts\UserProductInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProductRepository extends AbstractRepository implements UserProductInterface
{
    private $model = UserProduct::class;
    private $relationships = ['product', 'user'];
    private $dependences = [];
    private $unique = [];
    private $message = null;
    private $order = 'name';
    private $upload = [];


    public function __construct()
    {
        $this->model = app($this->model);
        parent::__construct($this->model, $this->relationships, $this->dependences, $this->unique, $this->upload);
    }

    public function findPaginate(Request $request)
    {
        $models = $this->model->query()->with($this->relationships);

        if (auth()->user()->type != "PROVIDER") {
            $models = $this->model->query()->with($this->relationships)->where('user_id', auth()->user()->id)->where('status', 'CARRINHO');
        }

        if ($request->exists('search')) {
            $this->setFilterGlobal($request, $models);
        } else {
            $this->setFilterByColumn($request, $models);
        }
        $this->setOrder($request, $models);
        $models = $models->paginate(8);
        $this->setMessage('Consulta Finalizada', 'success');
        return $models;
    }


    public function save(Request $request)
    {
        $product = Product::find($request->product_id);

        if ($product->quantyty < $request->quantyty == true) {
            $this->setMessage('Quantidade não disponível.', 'error');
            return null;
        }
        $model = new $this->model();
        $model->product_id = $request->product_id;
        $model->status = "CARRINHO";
        $model->value = $product->value * $request->quantyty;
        $model->quantyty = $request->quantyty;
        $model->user_id = Auth::user()->id;

        $product->quantyty = $product->quantyty - $request->quantyty;
        $product->save();
        $model->save();
        $this->setMessage('Adiconado ao carrinho', 'success');
        return $model;
    }
    
}
