<?php
namespace Leplaisir;
/*
$m = new Markov(5);
$m->set_text('今の段階で仕事上で分からないことがあり、足りないことがあり、日本語の使い方が正しくかもしれないことがありますので、もし仕事で何にが間違ったところがありましたら、お先輩たぜひぜひ教えてくだい。');
$r = $m->execute();
var_dump($r);
*/
class Markov
{
    protected $text;
    protected $options = []; // memcab実行オプション
    protected $hierarchy = 3;   //

    protected $ary_text = [];    // 形態素解析後の文章配列
    protected $node_text = null;   // 形態素解析後の文章ノード
    protected $word_table = []; // マルコフ連鎖用テーブル
    protected $noun = [];   // 名詞配列
    protected $text_num = 1;
    protected $result = ''; // マルコフ連鎖結果
    protected $feature = [];

    /**
     * Markov constructor.
     * @param int $hierarchy
     */
    public function __construct($hierarchy=3)
    {
        if($hierarchy > 0)
        {
            $this->hierarchy = $hierarchy;
        }
    }

    public function set_text($text)
    {
        $this->text = $text;
    }

    public function get_text()
    {
        return $this->text;
    }

    public function set_hierarchy($hierarchy)
    {
        $this->hierarchy = $hierarchy;
    }

    public function get_hierarchy()
    {
        return $this->hierarchy;
    }

    public function get_ary_text()
    {
        return $this->ary_text;
    }

    public function get_node_text()
    {
        return $this->node_text;
    }

    public function get_word_table()
    {
        return $this->word_table;
    }

    public function get_noun()
    {
        return $this->noun;
    }

    public function get_result()
    {
        return $this->result;
    }

    public function get_feature()
    {
        return $this->feature;
    }

    public function clear()
    {
        $this->text = '';
        $this->options = [];
        $this->hierarchy = 3;

        $this->ary_text = [];
        $this->node_text = null;
        $this->word_table = [];
        $this->noun = [];
        $this->result = null;

        return $this;
    }

    /**
     * 実行処理
     * @param array $options mecab実行時のオプション
     * @return bool|string
     * @throws \Exception
     */
    public function execute($options = [])
    {
        if(!class_exists('\MeCab\Tagger'))
        {
            throw new \Exception('not found class \MeCab\Tagger');
            return false;
        }

        if(!$this->text)
        {
            throw new \Exception('empty text');
            return false;
        }

        $this->options = $options;

        // mecabによる形態素解析
        $this->create_space_separated();
        // マルコフ連鎖用テーブル作成
        $this->create_word_table();
        // マルコフ連鎖による文章生成
        $this->create_sentence();

        return $this->result;
    }

    /**
     * mecabによる形態素解析
     */
    protected function create_space_separated()
    {
        $optins = array_merge(['-O', 'wakati'], $this->options);

        $mecab = new \MeCab\Tagger($optins);
        $ss_text = $mecab->parse($this->text);
        $this->ary_text = explode(' ', preg_replace('/\n|\r|\r\n/', '', $ss_text ));

        $this->node_text = $mecab->parseToNode($this->text);
        foreach ($this->node_text as $n) {
            $feature = explode(',', $n->getFeature());
            $this->feature[] = $feature;
            if(isset($feature[0]) && $feature[0] == '名詞' && $feature[6] != '*')
            {
                $this->noun[] = $feature[6];
            }
        }
    }

    /**
     * マルコフ連鎖用テーブル作成
     */
    protected function create_word_table()
    {
        $tex_num = 0;
        $words = $this->ary_text;
        foreach($words as $key => $word)
        {
            if($word == '')
            {
                continue;
            }
            if($word == '。')
            {
                $tex_num++;
            }
            $t = [];
            for($i=1; $i<=$this->hierarchy; $i++)
            {
                if(!isset($this->ary_text[$key+$i]))
                {
                    break;
                }
                $t[] = $this->ary_text[$key+$i];
            }

            $this->word_table[$word][] = $t;
            $this->text_num = $tex_num;
        }
    }

    /**
     * マルコフ連鎖による文章生成
     */
    protected function create_sentence()
    {
        if(count($this->noun) <= 0 || count($this->word_table) <= 0)
        {
            return;
        }
        $text_num = 0;

        while(true)
        {
            $head_word = $this->noun[array_rand($this->noun)];
            if(isset($this->word_table[$head_word]))
            {
                $this->result = $head_word;
                break;
            }
        }

        $count = 0;

        $loop = intval(count($this->word_table) / $this->hierarchy);

        do{
            $index = 0;
            if(count($this->word_table[$head_word]) > 1)
            {
                $index = array_rand($this->word_table[$head_word]);
            }

            $words = $this->word_table[$head_word][$index];

            foreach($words as $word)
            {

                if($word == '')
                {
                    continue;
                }

                if ($word == '。')
                {
                    $text_num++;
                }

                $head_word = $word;
                $this->result .= $word;
            }

            if($this->text_num <= $text_num)
            {
                break;
            }

            $count++;
        }while(count($this->word_table) >= $count);
    }

}