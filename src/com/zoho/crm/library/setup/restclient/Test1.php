<?php

class Test1
{
	public static function testIt()
	{
		$arr=array("1"=>"one","2"=>"two","3"=>"three");
		self::setIt($arr['13']);
		echo $arr['1'];
	}
	public function setIt($val)
	{
		$boolean1=(boolean)$val;
		echo $boolean1;
		if($val==null)
		{
			echo "in if";
		}
		echo "\n".$val;
	}
	public function printIt($a,$b=0,$c="hello",$d=40)
	{
		echo $a."\n";
		echo $b."\n";
		echo $c."\n";
		echo $d."\n";
	}
}
Test1::testIt();
$ins=new Test1();
$ins->printIt("1",2);
$ins->printIt("1",2,"hi");
$ins->printIt("1",2,"hi",50);

$str="First.image.jpg";
echo strrpos($str,'.');


$long1=34567890213456;
$string1="".$long1;
var_dump($string1);
$arr1=array(410405000001111007,410405000001111008,410405000001111009,410405000001111001,410405000001111002);
var_dump( implode(",", $arr1));
?>