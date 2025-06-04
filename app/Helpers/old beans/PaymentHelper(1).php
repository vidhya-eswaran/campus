<?php // Code within app\Helpers\Helper.php
use Carbon\Carbon;
if (! function_exists('randomId')) 
{
    function randomId($min = null, $max =null, $str = null ,$usedIds =null)
    {
        if($min &&  $max && $str){
            
            do {
                $now = Carbon::now();
                $usedNumbers = $usedIds ? $usedIds : [];   // Array to store used numbers
                $randomNumber =  mt_rand($min, $max);
                $prefix = $str;  // The string you want to add
                $randomNumberWithPrefix = $prefix . $randomNumber;
        
            } while (in_array($randomNumberWithPrefix, $usedNumbers));

            return  $randomNumberWithPrefix;
        }
        else{
            // Generate a random ID based on the current date and time
            $now = Carbon::now();
            $milliseconds = $now->micro / 1000; // Retrieve microseconds and convert to milliseconds
            $id = intval($now->format('YmdHis') . sprintf('%03d', $milliseconds)); // Append milliseconds
            return $id;
        }
        
    }
}

function numberToRomanRepresentation($number) {
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}

