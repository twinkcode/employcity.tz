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

    public function readData(Request $request)
    {
        $goods = $request->has('random') ? $this->randomizeData() : json_decode(Storage::get('TZ_front/data.json'));
        $names = json_decode(Storage::get('TZ_front/names.json'));
        return [
            'goods' => $goods,
            'names' => $names,
        ];
    }

    private function randomizeData(){
        $goods = json_decode(Storage::get('TZ_front/data.json'),true);
        foreach ($goods['Value']['Goods'] as &$v){
            $v["C"] = mt_rand(1,999) / 100;
        }
        return $goods;
    }

}
