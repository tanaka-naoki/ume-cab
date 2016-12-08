<?php

namespace App\Http\Controllers;

use YuzuruS\Mecab\Markovchain;

class MarkovController extends Controller
{

	public function index()
	{
		$mc = new Markovchain();
		$text = '民主、共産、社民、国民新の野党４党は１３日、麻生首相と自公連立政権による国政運営は限界に来ているとして、衆院に内閣不信任決議案を、参院に首相問責決議案をそれぞれ提出した。内閣不信任案は１４日の衆院本会議で否決されるが、首相問責決議案は参院で野党が多数を占めているため、近く本会議で可決の見通し。参院での首相問責可決は昨年の福田首相に次いで２例目になる';
		$markovText = $mc->makeMarkovText($text);

		echo $markovText;
	}


}