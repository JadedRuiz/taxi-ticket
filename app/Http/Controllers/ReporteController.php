<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index() {
        if(session('user')) {
            $user = json_decode($this->decode_json(session('user')[0]));
            $entries = ["public/js/reporte.js", "public/sass/reporte.scss"];
            $reporteViajes = $this->reporteViajes();

            return view('admin/Reportes', compact('user','entries','reporteViajes'));
        }
        return view('admin/template/Login');
    } 

    public function reporteViajes() {

    }
}
