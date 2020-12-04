<?php


$data = json_decode(file_get_contents("php://input"));

echo json_encode($data);

echo var_dump($_GET);

echo $_SERVER['PATH_INFO'];

echo var_dump($_SERVER);

if ($_SERVER['REQUEST_METHOD']=='POST'){
    echo "a post request was made";
}


if ($_SERVER['REQUEST_METHOD']=='PATCH'){
    echo "a patch request was made";
}


if ($_SERVER['REQUEST_METHOD']=='DELETE'){
    echo "a del request was made";
}


if ($_SERVER['REQUEST_METHOD']=='GET'){
    echo "a get request was made";
}

?>