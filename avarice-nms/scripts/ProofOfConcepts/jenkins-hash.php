<?PHP //http://www.experts-exchange.com/Web_Development/Web_Languages-Standards/PHP/Q_27486210.html
function rot($x,$k){
((($x)<<($k)) | (($x)>>(32-($k)))); 
	return;
  }

function mix(&$a, &$b, &$c){
    $a &= 0xffffffff; $b &= 0xffffffff; $c &= 0xffffffff;
    $a -= $c; $a &= 0xffffffff; $a ^= rot($c,4);  $a &= 0xffffffff; $c += $b; $c &= 0xffffffff;
    $b -= $a; $b &= 0xffffffff; $b ^= rot($a,6);  $b &= 0xffffffff; $a += $c; $a &= 0xffffffff;
    $c -= $b; $c &= 0xffffffff; $c ^= rot($b,8);  $c &= 0xffffffff; $b += $a; $b &= 0xffffffff;
    $a -= $c; $a &= 0xffffffff; $a ^= rot($c,16); $a &= 0xffffffff; $c += $b; $c &= 0xffffffff;
    $b -= $a; $b &= 0xffffffff; $b ^= rot($a,19); $b &= 0xffffffff; $a += $c; $a &= 0xffffffff;
    $c -= $b; $c &= 0xffffffff; $c ^= rot($b,4);  $c &= 0xffffffff; $b += $a; $b &= 0xffffffff;
    return array($a,$b,$c);
  }

function last(&$a, &$b, &$c){
    $a &= 0xffffffff; $b &= 0xffffffff; $c &= 0xffffffff;
    $c ^= $b; $c &= 0xffffffff; $c -= rot($b,14); $c &= 0xffffffff;
    $a ^= $c; $a &= 0xffffffff; $a -= rot($c,11); $a &= 0xffffffff;
    $b ^= $a; $b &= 0xffffffff; $b -= rot($a,25); $b &= 0xffffffff;
    $c ^= $b; $c &= 0xffffffff; $c -= rot($b,16); $c &= 0xffffffff;
    $a ^= $c; $a &= 0xffffffff; $a -= rot($c,4);  $a &= 0xffffffff;
    $b ^= $a; $b &= 0xffffffff; $b -= rot($a,14); $b &= 0xffffffff;
    $c ^= $b; $c &= 0xffffffff; $c -= rot($b,24); $c &= 0xffffffff;
    return array($a,$b,$c);
};

function hashlittle2($data, $initval = 0, $initval2 = 0){
$length = strlen($data); $lenpos = $length;

    $a = (0xdeadbeef + ($length) + $initval); $b = (0xdeadbeef + ($length) + $initval); $c = (0xdeadbeef + ($length) + $initval);

    $c += $initval2; $c &= 0xffffffff; $p = 0;  # string offset
    while ($lenpos > 12){
        $a += (ord($data[$p+0]) + (ord($data[$p+1])<<8) + (ord($data[$p+2])<<16) + (ord($data[$p+3])<<24));     $a &= 0xffffffff;     $b += (ord($data[$p+4]) + (ord($data[$p+5])<<8) + (ord($data[$p+6])<<16) + (ord($data[$p+7])<<24));     $b &= 0xffffffff;     $c += (ord($data[$p+8]) + (ord($data[$p+9])<<8) + (ord($data[$p+10])<<16) + (ord($data[$p+11])<<24));     $c &= 0xffffffff;     mix($a, $b, $c);     $p += 12;     $lenpos -= 12;   }

    if ($lenpos == 12){
     $c += (ord($data[$p+8]) + (ord($data[$p+9])<<8) + (ord($data[$p+10])<<16) + (ord($data[$p+11])<<24));  $b += (ord($data[$p+4]) + (ord($data[$p+5])<<8) + (ord($data[$p+6])<<16) + (ord($data[$p+7])<<24));  $a += (ord($data[$p+0]) + (ord($data[$p+1])<<8) + (ord($data[$p+2])<<16) + (ord($data[$p+3])<<24));
   }
    if ($lenpos == 11){
     $c += (ord($data[$p+8]) + (ord($data[$p+9])<<8) + (ord($data[$p+10])<<16));  $b += (ord($data[$p+4]) + (ord($data[$p+5])<<8) + (ord($data[$p+6])<<16) + (ord($data[$p+7])<<24));  $a += (ord($data[$p+0]) + (ord($data[$p+1])<<8) + (ord($data[$p+2])<<16) + (ord($data[$p+3])<<24));
   }
    if ($lenpos == 10){
     $c += (ord($data[$p+8]) + (ord($data[$p+9])<<8));  $b += (ord($data[$p+4]) + (ord($data[$p+5])<<8) + (ord($data[$p+6])<<16) + (ord($data[$p+7])<<24));  $a += (ord($data[$p+0]) + (ord($data[$p+1])<<8) + (ord($data[$p+2])<<16) + (ord($data[$p+3])<<24));
   }
    if ($lenpos == 9){
     $c += (ord($data[$p+8]));  $b += (ord($data[$p+4]) + (ord($data[$p+5])<<8) + (ord($data[$p+6])<<16) + (ord($data[$p+7])<<24));  $a += (ord($data[$p+0]) + (ord($data[$p+1])<<8) + (ord($data[$p+2])<<16) + (ord($data[$p+3])<<24));
   }
    if ($lenpos == 8){
     $b += (ord($data[$p+4]) + (ord($data[$p+5])<<8) + (ord($data[$p+6])<<16) + (ord($data[$p+7])<<24));  $a += (ord($data[$p+0]) + (ord($data[$p+1])<<8) + (ord($data[$p+2])<<16) + (ord($data[$p+3])<<24));
   }
    if ($lenpos == 7){
     $b += (ord($data[$p+4]) + (ord($data[$p+5])<<8) + (ord($data[$p+6])<<16));  $a += (ord($data[$p+0]) + (ord($data[$p+1])<<8) + (ord($data[$p+2])<<16) + (ord($data[$p+3])<<24));
   }
    if ($lenpos == 6){
     $b += ((ord($data[$p+5])<<8) + ord($data[$p+4]));  $a += (ord($data[$p+0]) + (ord($data[$p+1])<<8) + (ord($data[$p+2])<<16) + (ord($data[$p+3])<<24));
   }
    if ($lenpos == 5){
     $b += (ord($data[$p+4]));  $a += (ord($data[$p+0]) + (ord($data[$p+1])<<8) + (ord($data[$p+2])<<16) + (ord($data[$p+3])<<24));
   }
    if ($lenpos == 4){
     $a += (ord($data[$p+0]) + (ord($data[$p+1])<<8) + (ord($data[$p+2])<<16) + (ord($data[$p+3])<<24));
   }
    if ($lenpos == 3){
     $a += (ord($data[$p+0]) + (ord($data[$p+1])<<8) + (ord($data[$p+2])<<16));
   }
    if ($lenpos == 2){
     $a += (ord($data[$p+0]) + (ord($data[$p+1])<<8));
   }
    if ($lenpos == 1){
     $a += ord($data[$p+0]);  $a &= 0xffffffff;  $b &= 0xffffffff;  $c &= 0xffffffff;
   }

    if ($lenpos == 0){
    	 return array($c, $b);
    	 }; 
    last($a, $b, $c); 
    return array($c, $b);
  }

function hashlittle($data, $initval=0){
    $c = hashlittle2($data, $initval, 0); 
    return $c;
};
$hashstr = 'Four score and seven years ago';
$hash = hashlittle2($hashstr, 0xdeadbeef, 0xdeadbeef);
print $hashstr . ": " . $hash[0] . " " . $hash[1] . "<br />";

$hash = hashlittle($hashstr, 0);
print $hashstr . ": " . $hash[0] . " " . $hash[1] . "<br />";
?>