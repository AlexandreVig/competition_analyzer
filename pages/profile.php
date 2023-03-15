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
require "config.php";
require "asset.php";

if (!(isset($_COOKIE["id"]) && isset($_COOKIE["name"]))) {
    header('Location: login.php');
}

$conn = new mysqli($servername, $serverusername, $serverpassword, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php echo $page_favicon ?>
    <title><?php echo $page_name ?></title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <!-- Main Styling -->
    <link href="../assets/css/argon-dashboard-tailwind.css?v=1.0.1" rel="stylesheet" />
  </head>
  <body class="m-0 font-sans antialiased font-normal dark:bg-slate-900 text-base leading-default bg-gray-50 text-slate-500">
    <div class="absolute bg-y-50 w-full top-0 bg-[url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg')] min-h-75">
      <span class="absolute top-0 left-0 w-full h-full bg-blue-500 opacity-60"></span>
    </div>

    <aside class="fixed inset-y-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-y-auto antialiased transition-transform duration-200 -translate-x-full bg-white border-0 shadow-xl dark:shadow-none dark:bg-slate-850 xl:ml-6 max-w-64 ease-nav-brand z-990 rounded-2xl xl:left-0 xl:translate-x-0" aria-expanded="false">
        <?php
        echo $my_application_name;
        ?>

      <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />

      <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full">
          <ul class="flex flex-col pl-0 mb-0">
              <?php
              $sql = "SELECT icon, texte, url FROM navbar WHERE type='page' ORDER BY id ASC";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                  // output data of each row
                  while($row = $result->fetch_assoc()) {
                      if (explode("?", $row["url"])[0] == basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING'])) {
                          $class = "py-2.7 bg-blue-500/13 dark:text-white dark:opacity-80 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors";
                      } else {
                          $class = "dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors";
                      }
                      ?>
                      <li class="mt-0.5 w-full">
                          <a class="<?php echo $class ?>" href="<?php echo $row["url"] ?>">
                              <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                  <i class="relative top-0 text-sm leading-normal <?php echo $row["icon"] ?>"></i>
                              </div>
                              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease"><?php echo $row["texte"]?></span>
                          </a>
                      </li>
                      <?php
                  }
              }
              ?>

              <li class="w-full mt-12">
                  <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase dark:text-white opacity-60">Account pages</h6>
              </li>

              <?php
              $sql = "SELECT icon, texte, url FROM navbar WHERE type='user' ORDER BY id ASC";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                  // output data of each row
                  while($row = $result->fetch_assoc()) {
                      if ($row["url"] == basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING'])) {
                          $class = "py-2.7 bg-blue-500/13 dark:text-white dark:opacity-80 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors";
                      } else {
                          $class = "dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors";
                      }
                      ?>
                      <li class="mt-0.5 w-full">
                          <a class="<?php echo $class ?>" href="<?php echo $row["url"] ?>">
                              <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                  <i class="relative top-0 text-sm leading-normal <?php echo $row["icon"] ?>"></i>
                              </div>
                              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease"><?php echo $row["texte"]?></span>
                          </a>
                      </li>
                      <?php
                  }
              }
              ?>
          </ul>
      </div>
    </aside>

    <div class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68">
        <!-- Navbar -->
        <?php echo $my_navbar ?>
        <!-- end Navbar -->

      <div class="relative w-full mx-auto mt-60 ">
        
        <div class="relative flex flex-col flex-auto min-w-0 p-4 mx-6 overflow-hidden break-words bg-white border-0 dark:bg-slate-850 dark:shadow-dark-xl shadow-3xl rounded-2xl bg-clip-border">
          <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-auto max-w-full px-3">
              <div class="relative inline-flex items-center justify-center text-white transition-all duration-200 ease-in-out text-base h-19 w-19 rounded-xl">
                <img src="https://www.gravatar.com/avatar/<?php echo $_COOKIE["id"] ?>?s=200&d=identicon" alt="profile_image" class="w-full shadow-2xl rounded-xl" />
              </div>
            </div>
            <div class="flex-none w-auto max-w-full px-3 my-auto">
              <div class="h-full">
                <h5 class="mb-1 dark:text-white capitalize"><?php echo $_COOKIE["name"] ?></h5>
                <p class="mb-0 font-semibold leading-normal dark:text-white dark:opacity-60 text-sm">Lorem Ipsum</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="w-full p-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
          <div class="w-full max-w-full px-3 shrink-0 md:w-full md:flex-0">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="absolute h-full w-full bg-gray-500/30 rounded-2xl">
                    <h1 class="text-center">WORK IN PROGRESS</h1>
                </div>
              <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
                <div class="flex items-center">
                  <p class="mb-0 dark:text-white/80">Edit Profile</p>
                </div>
              </div>
              <div class="flex-auto p-6">
                <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm">User Information</p>
                <div class="flex flex-wrap -mx-3">
                  <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                    <div class="mb-4">
                      <label for="username" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Username</label>
                      <input type="text" name="username" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    </div>
                  </div>
                  <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                    <div class="mb-4">
                      <label for="email" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Email address</label>
                      <input type="email" name="email" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    </div>
                  </div>
                  <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                    <div class="mb-4">
                      <label for="first name" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">First name</label>
                      <input type="text" name="first name" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    </div>
                  </div>
                  <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                    <div class="mb-4">
                      <label for="last name" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Last name</label>
                      <input type="text" name="last name" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    </div>
                  </div>
                </div>
                  <hr class="h-px mx-0 my-4 bg-transparent border-0 opacity-25 bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent " />

                  <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm">Contact Information</p>
                  <div class="flex flex-wrap -mx-3">
                    <div class="w-full max-w-full px-3 shrink-0 md:w-full md:flex-0">
                      <div class="mb-4">
                        <label for="address" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Address</label>
                        <input type="text" name="address" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                      <div class="mb-4">
                        <label for="city" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">City</label>
                        <input type="text" name="city" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                      <div class="mb-4">
                        <label for="country" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Country</label>
                        <input type="text" name="country" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                    <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                      <div class="mb-4">
                        <label for="postal code" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Postal code</label>
                        <input type="text" name="postal code" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                      </div>
                    </div>
                  </div>
                <!--<hr class="h-px mx-0 my-4 bg-transparent border-0 opacity-25 bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent " />

                <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm">About me</p>
                <div class="flex flex-wrap -mx-3">
                  <div class="w-full max-w-full px-3 shrink-0 md:w-full md:flex-0">
                    <div class="mb-4">
                      <label for="about me" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">About me</label>
                      <input type="text" name="about me" value="A beautiful Dashboard for Bootstrap 5. It is Free and Open Source." class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    </div>
                  </div>
                </div>-->
              </div>
            </div>
          </div>

        </div>
        <?php
        echo $my_footer;
        ?>
      </div>
    </div>
  </body>
  <!-- plugin for scrollbar  -->
  <script src="../assets/js/plugins/perfect-scrollbar.min.js" async></script>
  <!-- main script file  -->
  <script src="../assets/js/argon-dashboard-tailwind.js?v=1.0.1" async></script>
</html>
