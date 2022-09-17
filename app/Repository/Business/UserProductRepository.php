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
    private $relationships = ['product', 'user', 'purchase'];
    private $dependences = ['purchase'];
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

    public function deleteById($id)
    {
        $model = $this->model->query()->with($this->relationships);

        $model = $model->find($id);
        if (empty($model)) {
            $this->setMessage('O registro não exite.', 'danger');
            return null;
        }
        $product = Product::find($model->product_id);
        $product->quantyty = $product->quantyty + $model->quantyty;
        $product->save();

        if ($this->dependencies($model) == false) {
            $this->setMessage('O registro não pode ser apagado, o mesmo está vinculado em outro lugar.', 'error');
            return null;
        }
        $this->uploadFiles($model);
        $model->destroy($model->id);
        $this->setMessage('O registro foi apagado com sucesso.', 'success');
        return null;
    }
}
