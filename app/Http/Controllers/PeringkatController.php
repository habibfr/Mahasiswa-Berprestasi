<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PeringkatController extends Controller
{
  public function index()
  {
    return view('content.peringkat.index', ['judul' => 'Peringkat']);
  }
}
