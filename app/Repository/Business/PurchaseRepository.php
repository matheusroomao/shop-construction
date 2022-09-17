<?php

namespace App\Repository\Business;

use App\Export\PDF\CSPDF;
use App\Export\PDF\PurchaseExport;
use App\Models\CartPurchase;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use App\Models\UserProduct;
use App\Repository\Contracts\PurchaseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseRepository extends AbstractRepository implements PurchaseInterface
{
    private $model = Purchase::class;
    private $relationships = ['user', 'userProduct'];
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
            $models = $this->model->query()->with($this->relationships)->where('user_id', auth()->user()->id);
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
        $userProducts = UserProduct::where('status', "CARRINHO")->where('user_id', Auth()->user()->id)->get();
        $value = 0;
        foreach ($userProducts as $userProduct) {
            $value = $value + $userProduct->value;
        }

        $model = new $this->model();
        $model->status = "NOVO";
        $model->user_id = Auth::user()->id;
        $model->amount = $value;
        $model->save();
        foreach ($userProducts as $userProduct) {
            $userProduct->purchase_id = $model->id;
            $userProduct->status = "PROCESSAMENTO";
            $userProduct->save();
        }
        $this->setMessage('Compra realizada com sucesso.', 'success');
        return $model;
    }

    public function update($id, Request $request)
    {
        $model = $this->model->query()->with($this->relationships);

        $model = $model->find($id);
        if (empty($model)) {
            $this->setMessage('O registro não exite.', 'danger');
            return null;
        }

        $userProducts = UserProduct::where('purchase_id', $model->id)->get();
        if ($request->status == "APROVADO") {
            foreach ($userProducts as $userProduct) {
                $userProduct->status = "PEDIDO APROVADO";
                $userProduct->save();
            }
        }
        if ($request->status == "REPROVADO") {
            foreach ($userProducts as $userProduct) {
                $userProduct->status = "PEDIDO REPROVADO";
                $product = Product::find($userProduct->product->id);
                $product->quantyty = $product->quantyty + $userProduct->quantyty;
                $product->save();
                $userProduct->save();
            }
        }

        $request->request->remove('_token');
        $request->request->remove('_method');
        $request->request->remove('created_at');

        $data = $model->getAttributes();
        $array_diff = array_diff($request->all(), $data);

        $model->fill($array_diff);
        $this->uploadFiles($model, $request);
        $model->save();

        $this->setMessage('O registro foi atualizado com sucesso', 'success');
        return $model;
    }

    public function exportToPDF(Request $request)
    { 
        $models = $this->model->query()->with($this->relationships)->where('id', $request->id)->get();

        if (auth()->user()->type != "PROVIDER") {
            $models = $this->model->query()->with($this->relationships)->where('id', $request->id)->where('user_id', auth()->user()->id)->get();
        }
        CSPDF::config();
        $pdf = new PurchaseExport();
        $this->setMessage('Consulta Finalizada', 'success');
        return $pdf->initialize($request, $models);
    }
}
