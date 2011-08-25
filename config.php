<?php

/*
 * **********
 * * Config *
 * **********
 * input_length:    - max length of input string. If empty, post_max_size in php.ini is used. In bytes.
 *                  - 524288 recommended - 500K
 *                  - no quotes.
 * 
 * handle_ch:       - Should we handle Ch diagraph? Boolean.
 *                  - set TRUE for Chamorro, Czech, Slovak, Polish, Igbo, Quechua, Guarani, Welsh, Cornish, Breton and Belarusian Łacinka
 * 
 * show_exec_time:  - Show how long did it took to generate page
 *                  - Boolean: TRUE/FALSE
 */

$config = array(
    'input_length' => 524288, //Do not escape by quotes. TODO: Make this safe.
    'handle_ch' => TRUE,
    'show_gen_time' => TRUE,
);

/*
 * Config end
 */

?>
