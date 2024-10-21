<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VehiculosController extends Controller
{
    public function index() {
        if(session()->has('data-user')) {
            $user = json_decode($this->decode_json(session('data-user')));
            $entries = ["public/js/vehiculo.js", "public/sass/vehiculo.scss"];

            return view('admin/Vehiculos', compact('user','entries'));
        }
        return view('admin/template/Login');
    }
}
