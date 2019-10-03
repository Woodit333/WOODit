
<?php
    session_start();
    //On edit exsits date
    if(isset($_POST['edit-user'])) {
        require 'dbh.inc.php';
    
        $name = $_POST['name'];
        $email = $_POST['email'];
        $passsword =  $_POST['password'];
        $mobile = $_POST['mobile'];

        $location_id = $_POST['location-id'];
        $address = $_POST['location-input'];
        $maps_url = 'http://geocoder.api.here.com/6.2/geocode.json?locationid={0}&jsonattributes=1&gen=9&app_id=bzonpeNIWqYBXzfcQhLK&app_code=i4NaaZIjpiCRyvDWg4BNPQ';
        $maps_url = str_replace("{0}",$location_id, $maps_url);
        $maps_json = file_get_contents($maps_url);
        $maps_array = json_decode($maps_json, true);
        $latitude = $maps_array['response']['view'][0]['result'][0]['location']['mapView']['topLeft']['latitude'];
        $longitude = $maps_array['response']['view'][0]['result'][0]['location']['mapView']['topLeft']['longitude'];
    
        $errors = array();
        
        if($stmt = $conn->prepare(
            "UPDATE users
             SET
            `name` = ?,
            `email` = ?,
            `password` = ?,
            `mobile` = ?,
            `address` = ?,
            `address_id` = ?,
            `latitude` = ?,
            `longitude` = ?,
            `modified_date` = ?,
            WHERE `id` = ?")){
            //Bind i -> for int col & s for string col
            $stmt->bind_param("sssisssssi", 
                    $name, $email, $passsword,
                    $mobile, 
                    $location, $location_id, $latitude, $longitude, now(),
                    $_SESSION['user']['id']);
            if (!$stmt->execute()) {
                array_push($errors, "שגיאה: " . mysqli_error($conn));
            }
        } else {
            array_push($errors, "שגיאה: " . mysqli_error($conn));
        }

        if (count($errors) == 0) {
            $_SESSION['register'] = true;
            header('Location: ../index.php');
        } else {
            $_SESSION['errors'] = $errors;
            header('Location: ../register.php');
        }
    }
?>


