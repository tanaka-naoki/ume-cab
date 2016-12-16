<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use League\Flysystem\Exception;
use YuzuruS\Mecab\Markovchain;
use Illuminate\Support\Facades\Input;
use \Leplaisir\Markov;

class MarkovController extends Controller
{

	public function index()
	{
        return view('markov.index',['result' => ['',''], 'text' => '']);
	}

	public function result()
    {
        $text = str_replace(array("\r\n", "\r", "\n"), '', Input::get('input_text'));

        try
        {
            $leplaisir = new Markov(5);
            $leplaisir->set_text($text);
            $le_result = $leplaisir->execute(['-d', '/usr/lib/mecab/dic/mecab-ipadic-neologd']);

        }
        catch (Exception $e)
        {
            $le_result = 'error';
            $markovText = 'error';
        }

        return view('markov.index',['result' => ['', $le_result], 'text' => $text]);
    }


}