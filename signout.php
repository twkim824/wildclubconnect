<?php

include_once 'database_session/session.php';

session_destroy();
header('location: index.php');
?>