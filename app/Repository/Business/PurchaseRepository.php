<?php

namespace App\Repository\Business;

use App\Export\PDF\CSPDF;
use App\Export\PDF\PurchaseExport;
use App\Models\CartPurchase;
use App\Models\Purchase;
use App\Models\UserProduct;
use App\Repository\Contracts\PurchaseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseRepository extends AbstractRepository implements PurchaseInterface
{
    private $model = Purchase::class;
    private $relationships = ['user','userProduct'];
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
            $userProduct->status = " ";
            $userProduct->save();
        }
        $this->setMessage('Compra realizada com sucesso.', 'success');
        return $model;
    }

    public function exportToPDF(Request $request)
    {
        $models = $this->model->query()->with($this->relationships)->where('id', $request->id);
        
        $models = $models->get();
        CSPDF::config();
        $pdf = new PurchaseExport();
        $this->setMessage('Consulta Finalizada', 'success');
        return $pdf->initialize($request, $models);
    }

}
