<?php
include('config.php');

if (isset($_POST['Phone'])) {
    $data = $_POST['Phone'];

    // Sanitize user input
    $data['userPhnNo'] = filter_var($data['userPhnNo'], FILTER_SANITIZE_NUMBER_INT);
    // Sanitize other input fields if necessary

    try {
        $statement = $db->prepare("INSERT INTO cf_user(userPhnNo, userName, userAddress, userAreaCode, userCarrier, userLatitude, userLongitude, userCountryCallingCode) 
                                   VALUES(:userPhnNo, :userName, :userAddress, :userAreaCode, :userCarrier, :userLatitude, :userLongitude, :userCountryCallingCode)");
        $result = $statement->execute($data);

        if ($result) {
            header("Location: /" . urlencode($data['userPhnNo']));
        } else {
            header("Location: /" . urlencode($data['userPhnNo']) . "&status=1");
        }
    } catch (PDOException $e) {
        // Log the error or handle it in a way suitable for your application
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: /");
}
