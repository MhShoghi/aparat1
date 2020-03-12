<?php

/**
 * Add +98 at first mobile number
 * @param string $mobile Phone number
 * @return string
 */
function to_valid_mobile_number(string $mobile){
    return $mobile = '+98' . substr($mobile,-10,10);
}
