<?php

// declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
// require_once "../Classes/Dbh.php";
// require_once "../Classes/Api.php";
require_once "../src/Model.php";
require_once "../src/Controller.php";
require_once "../Classes/GraphQL.php";

$graphql = new GraphQL();
$graphql->handle();
