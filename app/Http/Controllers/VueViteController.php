<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VueViteController extends Controller
{
    public function index()
    {
        return view('vue_layouts.index');
    }

    public function readData()
    {
        $goods = Storage::get('TZ_front/data.json');
        $names = Storage::get('TZ_front/names.json');
        return [
            'goods' => json_decode($goods),
            'names' => json_decode($names),
        ];
    }


}
