<?php
//efetua logoff
session_start();
session_destroy();
// header("Location: index.php");

?>
<script>window.location.href = 'index.php';</script>