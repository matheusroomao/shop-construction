<?php

namespace App\Repository\Contracts;

use Illuminate\Http\Request;

interface PurchaseInterface
{
    public function findPaginate(Request $request);
    public function findAll(Request $request);
    public function findById($id);

    public function save(Request $request);
    public function update($id, Request $request);
    public function deleteById($id);
    public function exportToPDF(Request $request);

    public function getMessage();
}
