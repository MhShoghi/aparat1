<?php

/**
 * Add +98 at first mobile number
 * @param string $mobile Phone number
 * @return string
 */
if(!function_exists('to_valid_mobile_number')){
    function to_valid_mobile_number(string $mobile){
        return $mobile = '+98' . substr($mobile,-10,10);
    }
}


/**
 * Generate random code for register
 * @return string
 * @throws Exception
 */
if(!function_exists('random_verification_code')){
    function random_verification_code(){
        return random_int(10,90). random_int(10,90) . random_int(10,90);
    }
}

