<?php
/**
 * Created by PhpStorm.
 * User: ALLOON
 * Date: 24.10.2018
 * Time: 10:57
 */

class StringController
{
    private $nul = 'ноль';
    private $ten=array(
        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
    );
    private $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
    private $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
    private $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
    private $unit=array( // Units
        array('коп','','',1),
        array('грн','','',0),
        array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
        array('миллион' ,'миллиона','миллионов' ,0),
        array('миллиард','милиарда','миллиардов',0),
    );

    public function number2string($num) {
        header('Content-Type: application/json; charset=utf-8');

        //разлаживаем флоат на челую идробную часть
        list($sum,$kop) = explode('.',sprintf("%015.2f", floatval($num)));

        $out = array();
        if (intval($sum)>0) {
            foreach(str_split($sum,3) as $uk=>$v) { // by 3 symbols
                if (!intval($v)) continue;
                $uk = sizeof($this->unit)-$uk-1; // unit key
                $gender = $this->unit[$uk][3];
                list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
                // mega-logic
                $out[] = $this->hundred[$i1]; # 1xx-9xx
                if ($i2>1) $out[]= $this->tens[$i2].' '.$this->ten[$gender][$i3]; # 20-99
                else $out[]= $i2>0 ? $this->a20[$i3] : $this->ten[$gender][$i3]; # 10-19 | 1-9
                // единиц без sum & kop
                if ($uk>1) $out[]= $this->morph($v,$this->unit[$uk][0],$this->unit[$uk][1],$this->unit[$uk][2]);
            } //foreach
        }
        else $out[] = $this->nul;
        $out[] = $this->unit[1][0]; // sum
        $out[] = $kop.' '.$this->unit[0][0]; // kop
        $result = trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));

        echo json_encode(array('result'=>$result));
        return;
    }

    /**
     * Склоняем словоформу
     * @ author runcore
     */
    private function morph($n, $f1, $f2, $f5) {
        $n = abs(intval($n)) % 100;
        if ($n>10 && $n<20) return $f5;
        $n = $n % 10;
        if ($n>1 && $n<5) return $f2;
        if ($n==1) return $f1;
        return $f5;
    }
}