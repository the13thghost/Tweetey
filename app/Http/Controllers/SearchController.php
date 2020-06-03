<?php

namespace App\Http\Controllers;

use App\Tweet;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {
        return view('search');
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {
        $data = Tweet::select("body")
                ->where("body","LIKE","%{$request->input('query')}%")
                ->get();
   
        $data1 = array();
        foreach ($data as $dat)
            {
                $data1[] = $dat->body;
            }
        return response()->json($data1);
    }
}
