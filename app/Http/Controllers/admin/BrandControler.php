<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandControler extends Controller
{
    public function create(){
        return view('admin.brands.create');
    }
}
