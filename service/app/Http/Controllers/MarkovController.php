<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use YuzuruS\Mecab\Markovchain;
use Illuminate\Support\Facades\Input;

class MarkovController extends Controller
{

	public function index()
	{
        return view('markov.index',['result' => '']);
	}

	public function result()
    {
        $text = Input::get('input_text');
        $mc = new Markovchain();
        $markovText = $mc->makeMarkovText($text);
        return view('markov.index',['result' => $markovText]);
    }


}