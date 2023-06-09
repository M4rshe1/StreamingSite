<?php
/**
 * @var mysqli $mysqli
 */

include_once(explode("StreamingSite", __DIR__)[0] . 'StreamingSite/api/db_connect.php');
if (isset($email)) {
    $stmt = $mysqli->prepare('SELECT tu.id as id, tu.username, tu.admin, tu.name, ap.id as token from tbl_apitoken as ap
        INNER JOIN tbl_users tu on ap.user = tu.id
        WHERE tu.email = ? AND tu.passwordHash = ?');
    $stmt->bind_param('ss', $email, $pass);
    $stmt->execute();
    $result = $stmt->get_result();
//    echo $email. "<br>";
//    echo $pass. "<br>";
//    echo !is_null($result). "<br>";
    $resultsArray = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $resultsArray[] = $row;
    }
    if (isset($resultsArray[0])) {
        $login = true;
        $cookie = true;

        if ($resultsArray[0]['admin'] == 1) {
            $IsAdmin = true;
        } else {
            $IsAdmin = false;
        }


//            echo '<pre>'; print_r($resultsArray); echo '</pre>';
        $name = $resultsArray[0]['name'];
        $username = $resultsArray[0]['username'];
        setcookie("token", $resultsArray[0]['token'], time() + (86400 * 180), "/");
        setcookie("name", $resultsArray[0]['name'], time() + (86400 * 180), "/");
        setcookie("username", $resultsArray[0]['username'], time() + (86400 * 180), "/");

    } else {
        $login = false;

        http_response_code(404);
//            echo 'user not found';
        $error = 404;
    }
} else {
    http_response_code(400);

    $error = 400;
}