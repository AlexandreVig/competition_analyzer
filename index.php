<!--
=========================================================
* Argon Dashboard 2 Tailwind - v1.0.1
=========================================================
* Product Page: https://www.creative-tim.com/product/argon-dashboard-tailwind
* Copyright 2022 Creative Tim (https://www.creative-tim.com)
* Coded by www.creative-tim.com
=========================================================
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<?php
if (isset($_COOKIE["id"]) && isset($_COOKIE["name"])) {
    header('Location: pages/grade.php');
} else {
    header('Location: pages/login.php');
}
?>