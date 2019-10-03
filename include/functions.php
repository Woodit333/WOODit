<?php
function getGUID()
{
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((float) microtime() * 10000); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
        return $uuid;
    }
}

function getErrorLabels()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $display_error = '';
    if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
        $display_errors_array = array();
        $errors = $_SESSION['errors'];
        foreach ($errors as $error) {
            $display_error = '<div class="alert alert-danger" role="alert">{0}</div>';
            $display_error = str_replace("{0}", $error, $display_error);
            array_push($display_errors_array, $display_error);
        }
        $display_error = implode("", $display_errors_array);
        unset($_SESSION['errors']);
    }
    return $display_error;
}

function base64_to_image($base64_string, $output_file) {
    // open the output file for writing
    $ifp = fopen( $output_file, 'wb' ); 

    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode( ',', $base64_string );

    // we could add validation here with ensuring count( $data ) > 1
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );

    // clean up the file resource
    fclose( $ifp ); 

    return $output_file; 
}

function array_remove_object(&$array, $value, $prop)
{
    return array_filter($array, function($a) use($value, $prop) {
        return $a->$prop !== $value;
    });
}