<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\model\AppAd;

class AppAdController extends Controller
{
    public function getAds($id) {
        $data = AppAd::where('material', $id)->get();
        return response()->json($data);
    }
}
