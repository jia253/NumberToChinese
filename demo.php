<?php
$number = isset($_GET['number']) && is_numeric($_GET['number']) ? $_GET['number'] : 0;
$result = suan($number);
echo $result;
function suan($number=''){
	//零、壹、贰、叁、肆、伍、陆、柒、捌、玖、拾、佰、仟、万、亿。
	$levelUnit = [0=>'',1=>'万',2=>'亿',3=>'万亿'];
	$len = strlen($number); //数字的长度
	$str = [];
	if($len > 16){
		echo '金额太大，目前仅支持千万亿级别的';die;
	}
	if($len > 0){
		for ($i=0;$i<$len;$i++){
			$pos = $len-$i; //位置
			$currentNum = $number[$i]; //数字
			//以4位为一个等级 个 万 亿 万亿
			$level = intval($pos/4); 
			//每个等级分4位  
			$currentPos = $pos%4;
			$str[] = [
				'unit' => (isset($levelUnit[$level])) ? ($currentPos > 0 ? $levelUnit[$level] : $levelUnit[$level-1]) : '',
				'chinese' => five($currentPos,$currentNum,$level),
			];
		}
	}
	$res = '';
	$zero = []; //记录零出现
	foreach ($str as $key => $value) {
		if($value['chinese'] != '零'){
			if(in_array($value['chinese'], ['万','亿'])){ //防止出现万前边是零
				$res = trim($res,'零').$value['chinese'];
			}else{
				$res.=$value['chinese']; 
			}
			$zero = [];
		}else{
			if(!$zero){
				$res .= $value['chinese'];
				$zero [] = '零';	
			}
			
		}
	}
	// print_r($str);
	return trim($res,'零');
}
/**
* @param $pos 当前数字模4后的位置
* @param $currentNum 当前数值
* @param $level 单位级别
**/
function five($pos='',$currentNum='',$level=''){
	$res = '';
	$levelUnit = [1=>'万',2=>'亿','3'=>'万亿'];
	$unit = [1=>'个',2=>'拾',3=>'佰',4=>'仟'];
	$hanzi = ['0'=>'零','1'=>'壹','2'=>'贰','3'=>'叁','4'=>'肆','5'=>'伍','6'=>'陆','7'=>'柒','8'=>'捌','9'=>'玖'];
	if($pos == 1){
		$res = $currentNum != 0 ? $hanzi[$currentNum] : '';
	}else{
		$pos = $pos ==0 ? 4: $pos;
		$res = $currentNum == 0 ? '零' : $hanzi[$currentNum].$unit[$pos] ;
	}

	if(isset($levelUnit[$level]) && $pos ==1){ 
		//万亿级别也是以亿为单位 数组元素万亿作为标识
		$res .= $levelUnit[$level] == '万亿' ? '万' : $levelUnit[$level];
	}
	return $res;
}
