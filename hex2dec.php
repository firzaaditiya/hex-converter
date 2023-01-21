<?php

const FIRST_INDEX = 0;
const HEXADECIMAL_FORMAT = 16;
const MAX_STRING_HEXCODE = 16;
const A_value = 10;
const B_value = 11;
const C_value = 12;
const D_value = 13;
const E_value = 14;
const F_value = 15;

function hexadecimal2decimal(string $hexcode) : int
{
    // replace 0x or 0X to empty
    $hex = strtolower(preg_replace("/0x/i", "", $hexcode));

    // validation hex string length
    if (strlen($hex) > MAX_STRING_HEXCODE) {
        throw new Exception(message: "Error. Hexadecimal code maximum length is 16");
    }

    // maximum validation of the hexadecimal code that can be converted to decimal 
    $pattern = "/(FFFFFFFFFFFFFFFF)|(EFFFFFFFFFFFFFFF)|(DFFFFFFFFFFFFFFF)|(CFFFFFFFFFFFFFFF)|(BFFFFFFFFFFFFFFF)|(AFFFFFFFFFFFFFFF)|(9FFFFFFFFFFFFFFF)|(8FFFFFFFFFFFFFFF)/i";
    if ((bool)preg_match($pattern, $hex) == true) {
        throw new Exception(message: "Error. Max value to convert hexadecimal to decimal is 0x7FFFFFFFFFFFFFFF");
    }

    // validation char hexcode
    for ($i = FIRST_INDEX; $i < strlen($hex); $i++) {
        if ((bool)preg_match_all("/0|1|2|3|4|5|6|7|8|9|a|b|c|d|e|f/i", $hex[$i]) == false) {
            throw new Exception(message: "Error with input string. Check to ensure that the input contains valid hex characters.");
        }
    }

    $tempHex = array();
    // replace A-F to decimal
    for ($i = strlen($hex) - 1; $i >= FIRST_INDEX; $i--) {
        if ($hex[$i] == "f") {
            $tempHex[] = F_value;
        } else if ($hex[$i] == "e") {
            $tempHex[] = E_value;
        } else if ($hex[$i] == "d") {
            $tempHex[] = D_value;
        } else if ($hex[$i] == "c") {
            $tempHex[] = C_value;
        } else if ($hex[$i] == "b") {
            $tempHex[] = B_value;
        } else if ($hex[$i] == "a") {
            $tempHex[] = A_value;
        } else {
            // (int) means casting string to int
            $tempHex[] = (int)$hex[$i];
        }
    }

    $decimal = null;
    // convert to decimal
    for ($i = sizeof($tempHex) - 1; $i >= FIRST_INDEX; $i--) {
        $decimal += $tempHex[$i] * (HEXADECIMAL_FORMAT ** $i);
    }

    return $decimal;
}

// example implementations
try {
    // with 0x
    echo hexadecimal2decimal(hexcode: "0x7fffffffffffffff") . PHP_EOL;

    // without 0x and in case insensitive
    echo hexadecimal2decimal(hexcode: "FfF") . PHP_EOL;
} catch(Exception $exception) {
    echo "Error : " . $exception->getMessage() . PHP_EOL;
}