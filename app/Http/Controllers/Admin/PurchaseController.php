<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseRequest;
use App\Repository\Contracts\PurchaseInterface;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(
        Request          $request,
        PurchaseInterface $purchaseInterface
    ) {
        $models = $purchaseInterface->findPaginate($request);
        return response()->view('admin.model.purchase.index', [
            'models' => $models,
            'filter' => $request,
        ]);
    }

    public function create(Request $request, PurchaseInterface $purchaseInterface)
    {
        $productModels = $purchaseInterface->findAll($request);
        return response()->view('admin.model.purchase.create', ["products" => $productModels]);
    }

    public function store(PurchaseRequest $request, PurchaseInterface $purchaseInterface)
    {
        $purchaseInterface->save($request);
        toastr($purchaseInterface->getMessage()->text, $purchaseInterface->getMessage()->type);
        if ($purchaseInterface->getMessage()->type === "success") {
            return redirect()->route('admin.purchase.index');
        } else {
            return back();
        }
    }

    public function show($id, PurchaseInterface $interface)
    {
        $model =  $interface->findByid($id);

        if (!empty($model)) {
            return response()->view('admin.model.purchase.show', ["model" => $model]);
        } else {
            toastr($interface->getMessage()->text, $interface->getMessage()->type);
            return back();
        }
    }

    public function edit($id, Request $request, PurchaseInterface $interface)
    {
        $model =  $interface->findByid($id);

        if (!empty($model)) {
            return response()->view('admin.model.purchase.edit', ["model" => $model]);
        } else {
            toastr($interface->getMessage()->text, $interface->getMessage()->type);
            return back();
        }
    }

    public function update(PurchaseRequest $request, $id, PurchaseInterface $interface)
    {
        $model =  $interface->update($id, $request);
        toastr($interface->getMessage()->text, $interface->getMessage()->type);
        if ($interface->getMessage()->type == "success") {
            return redirect()->route('admin.purchase.index');
        } else {
            return response()->view('admin.model.purchase.edit', ["model" => $model]);
        }
    }

    public function destroy($id, PurchaseInterface $purchaseInterface)
    {
        $purchaseInterface->deleteById($id);
        toastr($purchaseInterface->getMessage()->text, $purchaseInterface->getMessage()->type);
        return back();
    }

    
    public function export(Request $request, PurchaseInterface $patientInterface)
    {
        return $patientInterface->exportToPDF($request);
    }

}