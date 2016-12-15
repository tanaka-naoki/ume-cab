<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use YuzuruS\Mecab\Markovchain;
use Illuminate\Support\Facades\Input;

class MarkovController extends Controller
{

	public function index()
	{
        return view('markov.index',['result' => '', 'text' => '']);
	}

	public function result()
    {
        $text = str_replace(array("\r\n", "\r", "\n"), '', Input::get('input_text'));
        $mc = new Markovchain(5000);
        $markovText = $mc->makeMarkovText($text);
        return view('markov.index',['result' => $markovText, 'text' => $text]);
    }


}