<?php

require "config.php";

$conn = new mysqli($servername, $serverusername, $serverpassword, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* Header */
$page_name = '';
$page_favicon = '<link rel="apple-touch-icon" sizes="180x180" href="../assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/img/favicon/site.webmanifest">
    <link rel="shortcut icon" href="../assets/img/favicon/favicon.ico">
    <meta name="apple-mobile-web-app-title" content="Competition Analyzer">
    <meta name="application-name" content="Competition Analyzer">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="../assets/img/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">';

/* Sidenav application */
$my_application_name = '<div class="h-19">
        <i class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden" sidenav-close></i>
          <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap dark:text-white text-slate-700" href="grade.php">
              <img src="../assets/img/favicon/android-chrome-512x512.png" class="inline h-full max-w-full transition-all duration-200 dark:hidden ease-nav-brand max-h-8" alt="main_logo" />
              <img src="../assets/img/favicon/android-chrome-512x512.png" class="hidden h-full max-w-full transition-all duration-200 dark:inline ease-nav-brand max-h-8" alt="main_logo" />
              <span class="ml-1 font-semibold transition-all duration-200
          ease-nav-brand capitalize">Competition analyzer</span>
          </a>
      </div>';

/* Navbar top */
$my_navbar = '';
$url = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$sql = "SELECT texte, type FROM navbar WHERE url LIKE '$url%'";
$result = $conn->query($sql);
//echo $url;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $page_name = $row["texte"];
        if (strcmp($row["type"], "page") == 0) {
            $my_navbar = '<nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all ease-in shadow-none duration-250 rounded-2xl lg:flex-nowrap lg:justify-start" navbar-main navbar-scroll="false">
            <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
              <nav>
                <!-- breadcrumb -->
                <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
                  <li class="text-sm leading-normal">
                    <a class="text-white opacity-50" href="javascript:;">Pages</a>
                  </li>
                  <li class="text-sm pl-2 leading-normal text-white before:float-left before:pr-2 before:text-white before:content-[\'/\']" aria-current="page">'. $row["texte"] .'</li>
                </ol>
                <h6 class="mb-0 font-bold text-white">'. $row["texte"] .'</h6>
              </nav>
    
              <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
                <div class="flex items-center md:ml-auto md:pr-4">
                </div>
                <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
                    <li class="flex items-center px-4">
                        <a href="profile.php" class="block px-0 py-2 font-semibold text-white transition-all ease-nav-brand text-sm">
                            <i class="fa fa-user sm:mr-1" aria-hidden="true"></i>
                            <span class="hidden sm:inline">Profile</span>
                        </a>
                    </li>
                  <li class="flex items-center px-4 xl:hidden">
                    <a href="javascript:;" class="block p-0 text-sm text-white transition-all ease-nav-brand" sidenav-trigger>
                      <div class="w-4.5 overflow-hidden">
                        <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-white transition-all"></i>
                        <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-white transition-all"></i>
                        <i class="ease relative block h-0.5 rounded-sm bg-white transition-all"></i>
                      </div>
                    </a>
                  </li>
    
                  <!-- notifications -->
    
                  <li class="relative flex items-center pr-2">
                    <p class="hidden transform-dropdown-show"></p>
                    <a href="javascript:;" class="block p-0 text-base text-white transition-all ease-nav-brand" dropdown-trigger aria-expanded="false">
                      <i class="cursor-pointer fa fa-bell"></i>
                    </a>
    
                    <ul dropdown-menu class="text-sm transform-dropdown before:font-awesome before:leading-default before:duration-350 before:ease lg:shadow-3xl duration-250 min-w-44 before:sm:right-8 before:text-5.5 pointer-events-none absolute right-0 top-0 z-50 origin-top list-none rounded-lg border-0 border-solid border-transparent dark:shadow-dark-xl dark:bg-slate-850 bg-white bg-clip-padding px-2 py-4 text-left text-slate-500 opacity-0 transition-all before:absolute before:right-2 before:left-auto before:top-0 before:z-50 before:inline-block before:font-normal before:text-white before:antialiased before:transition-all before:content-[\'\f0d8\'] sm:-mr-6 lg:absolute lg:right-0 lg:left-auto lg:mt-2 lg:block lg:cursor-pointer">
                      <!-- add show class on dropdown open js -->
                      <li class="relative mb-2">
                        <a class="ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 lg:transition-colors" href="javascript:;">
                          <div class="flex py-1">
                            <div class="inline-flex items-center justify-center my-auto mr-4 text-white transition-all duration-200 ease-in-out text-sm bg-gradient-to-tl from-slate-600 to-slate-300 h-9 w-9 rounded-xl">
                              <i class="fa fa-envelope" aria-hidden="true"></i>
                            </div>
                            <div class="flex flex-col justify-center">
                              <h6 class="mb-1 font-normal leading-normal dark:text-white text-sm">Aucun message</h6>
                              <p class="mb-0 leading-tight dark:text-white dark:opacity-60 text-xs text-slate-400">
                                <i class="mr-1 fa fa-clock" aria-hidden="true"></i>
                                N/A
                              </p>
                            </div>
                          </div>
                        </a>
                      </li>
                      <!--<li class="relative mb-2">
                        <a class="dark:hover:bg-slate-900 ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors" href="javascript:;">
                          <div class="flex py-1">
                            <div class="my-auto">
                              <img src="../assets/img/team-2.jpg" class="inline-flex items-center justify-center mr-4 text-sm text-white h-9 w-9 max-w-none rounded-xl" />
                            </div>
                            <div class="flex flex-col justify-center">
                              <h6 class="mb-1 text-sm font-normal leading-normal dark:text-white"><span class="font-semibold">New message</span> from Laur</h6>
                              <p class="mb-0 text-xs leading-tight text-slate-400 dark:text-white/80">
                                <i class="mr-1 fa fa-clock"></i>
                                13 minutes ago
                              </p>
                            </div>
                          </div>
                        </a>
                      </li>
    
                      <li class="relative mb-2">
                        <a class="dark:hover:bg-slate-900 ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg px-4 transition-colors duration-300 hover:bg-gray-200 hover:text-slate-700" href="javascript:;">
                          <div class="flex py-1">
                            <div class="my-auto">
                              <img src="../assets/img/small-logos/logo-spotify.svg" class="inline-flex items-center justify-center mr-4 text-sm text-white bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 h-9 w-9 max-w-none rounded-xl" />
                            </div>
                            <div class="flex flex-col justify-center">
                              <h6 class="mb-1 text-sm font-normal leading-normal dark:text-white"><span class="font-semibold">New album</span> by Travis Scott</h6>
                              <p class="mb-0 text-xs leading-tight text-slate-400 dark:text-white/80">
                                <i class="mr-1 fa fa-clock"></i>
                                1 day
                              </p>
                            </div>
                          </div>
                        </a>
                      </li>
    
                      <li class="relative">
                        <a class="dark:hover:bg-slate-900 ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg px-4 transition-colors duration-300 hover:bg-gray-200 hover:text-slate-700" href="javascript:;">
                          <div class="flex py-1">
                            <div class="inline-flex items-center justify-center my-auto mr-4 text-sm text-white transition-all duration-200 ease-nav-brand bg-gradient-to-tl from-slate-600 to-slate-300 h-9 w-9 rounded-xl">
                              <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>credit-card</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                  <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                      <g transform="translate(453.000000, 454.000000)">
                                        <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                        <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                      </g>
                                    </g>
                                  </g>
                                </g>
                              </svg>
                            </div>
                            <div class="flex flex-col justify-center">
                              <h6 class="mb-1 text-sm font-normal leading-normal dark:text-white">Payment successfully completed</h6>
                              <p class="mb-0 text-xs leading-tight text-slate-400 dark:text-white/80">
                                <i class="mr-1 fa fa-clock"></i>
                                2 days
                              </p>
                            </div>
                          </div>
                        </a>
                      </li>-->
                    </ul>
                  </li>
                </ul>
              </div>
            </div>
          </nav>';
        } else {
            $my_navbar = '<nav class="absolute z-20 flex flex-wrap items-center justify-between w-full px-6 py-2 -mt-56 text-white transition-all ease-in shadow-none duration-250 lg:flex-nowrap lg:justify-start" navbar-profile navbar-scroll="true">
        <div class="flex items-center justify-between w-full px-6 py-1 mx-auto flex-wrap-inherit">
          <nav>
             <!-- breadcrumb -->
            <ol class="flex flex-wrap pt-1 pl-2 pr-4 mr-12 bg-transparent rounded-lg sm:mr-16">
              <li class="leading-normal text-sm">
                <a class="opacity-50" href="javascript:;">Pages</a>
              </li>
              <li class="text-sm pl-2 leading-normal before:float-left before:pr-2 before:content-[\'/\']" aria-current="page">'. $row["texte"] .'</li>
            </ol>
            <h6 class="mb-2 ml-2 font-bold text-white dark:text-white">'. $row["texte"] .'</h6>
          </nav>

          <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
            <div class="flex items-center md:ml-auto md:pr-4">
              <div class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease">
              </div>
            </div>
            <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
              <li class="flex items-center px-4">
                <a href="profile.php" class="block px-0 py-2 font-semibold text-white transition-all ease-in-out text-sm">
                  <i class="fa fa-user sm:mr-1" aria-hidden="true"></i>
                  <span class="hidden sm:inline">Profile</span>
                </a>
              </li>
              <li class="flex items-center pl-4 xl:hidden">
                <a href="javascript:;" class="block p-0 text-white transition-all ease-in-out text-sm" sidenav-trigger>
                  <div class="w-4.5 overflow-hidden">
                    <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-white transition-all"></i>
                    <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-white transition-all"></i>
                    <i class="ease relative block h-0.5 rounded-sm bg-white transition-all"></i>
                  </div>
                </a>
              </li>
              
              <!-- notifications -->
              
              <li class="relative flex items-center pr-2">
                <p class="hidden dark:text-white dark:opacity-60 transform-dropdown-show"></p>
                <a dropdown-trigger href="javascript:;" class="block p-0 text-white transition-all text-sm ease-nav-brand" aria-expanded="false">
                  <i class="cursor-pointer fa fa-bell" aria-hidden="true"></i>
                </a>

                <ul dropdown-menu class="text-sm transform-dropdown before:font-awesome before:leading-default before:duration-350 before:ease lg:shadow-3xl duration-250 min-w-44 before:sm:right-8 before:text-5.5 pointer-events-none absolute right-0 top-0 z-50 origin-top list-none rounded-lg border-0 border-solid border-transparent bg-white bg-clip-padding px-2 py-4 text-left text-slate-500 opacity-0 transition-all before:absolute before:right-2 before:left-auto before:top-0 before:z-50 before:inline-block before:font-normal before:text-white before:antialiased before:transition-all before:content-[\'\f0d8\'] sm:-mr-6 lg:absolute lg:right-0 lg:left-auto lg:mt-2 lg:block lg:cursor-pointer">
                   <!-- add show class on dropdown open js -->
                   <li class="relative mb-2">
                    <a class="ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 lg:transition-colors" href="javascript:;">
                      <div class="flex py-1">
                        <div class="inline-flex items-center justify-center my-auto mr-4 text-white transition-all duration-200 ease-in-out text-sm bg-gradient-to-tl from-slate-600 to-slate-300 h-9 w-9 rounded-xl">
                          <i class="fa fa-envelope" aria-hidden="true"></i>
                        </div>
                        <div class="flex flex-col justify-center">
                          <h6 class="mb-1 font-normal leading-normal dark:text-white text-sm">Aucun message</h6>
                          <p class="mb-0 leading-tight dark:text-white dark:opacity-60 text-xs text-slate-400">
                            <i class="mr-1 fa fa-clock" aria-hidden="true"></i>
                            N/A
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!--<li class="relative mb-2">
                    <a class="ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 lg:transition-colors" href="javascript:;">
                      <div class="flex py-1">
                        <div class="my-auto">
                          <img src="../assets/img/team-2.jpg" class="inline-flex items-center justify-center mr-4 text-white text-sm h-9 w-9 max-w-none rounded-xl" />
                        </div>
                        <div class="flex flex-col justify-center">
                          <h6 class="mb-1 font-normal leading-normal dark:text-white text-sm"><span class="font-semibold">New message</span> from Laur</h6>
                          <p class="mb-0 leading-tight dark:text-white dark:opacity-60 text-xs text-slate-400">
                            <i class="mr-1 fa fa-clock" aria-hidden="true"></i>
                            13 minutes ago
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>

                  <li class="relative mb-2">
                    <a class="ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg px-4 duration-300 lg:transition-colors" href="javascript:;">
                      <div class="flex py-1">
                        <div class="my-auto">
                          <img src="../assets/img/small-logos/logo-spotify.svg" class="inline-flex items-center justify-center mr-4 text-white text-sm bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 h-9 w-9 max-w-none rounded-xl" />
                        </div>
                        <div class="flex flex-col justify-center">
                          <h6 class="mb-1 font-normal leading-normal dark:text-white text-sm"><span class="font-semibold">New album</span> by Travis Scott</h6>
                          <p class="mb-0 leading-tight dark:text-white dark:opacity-60 text-xs text-slate-400">
                            <i class="mr-1 fa fa-clock" aria-hidden="true"></i>
                            1 day
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>

                  <li class="relative">
                    <a class="ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg px-4 duration-300 lg:transition-colors" href="javascript:;">
                      <div class="flex py-1">
                        <div class="inline-flex items-center justify-center my-auto mr-4 text-white transition-all duration-200 ease-in-out text-sm bg-gradient-to-tl from-slate-600 to-slate-300 h-9 w-9 rounded-xl">
                          <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>credit-card</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                              <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                <g transform="translate(1716.000000, 291.000000)">
                                  <g transform="translate(453.000000, 454.000000)">
                                    <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                    <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                  </g>
                                </g>
                              </g>
                            </g>
                          </svg>
                        </div>
                        <div class="flex flex-col justify-center">
                          <h6 class="mb-1 font-normal leading-normal dark:text-white text-sm">Payment successfully completed</h6>
                          <p class="mb-0 leading-tight dark:text-white dark:opacity-60 text-xs text-slate-400">
                            <i class="mr-1 fa fa-clock" aria-hidden="true"></i>
                            2 days
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>-->
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>';
        }
    }
}
$conn->close();

/* Footer */
$my_footer = '<footer class="pt-4">
          <div class="w-full px-6 mx-auto">
            <div class="flex flex-wrap items-center -mx-3 lg:justify-between">
              <div class="w-full max-w-full px-3 mt-0 mb-6 shrink-0 lg:mb-0 lg:w-1/2 lg:flex-none">
                <div class="text-sm leading-normal text-center text-slate-500 lg:text-left">
                  ©
                  ' . date("Y") . "," . '
                  made with <i class="fa fa-heart"></i> by
                  <a href="https://www.alexvig.ovh/" class="font-semibold text-slate-700 dark:text-white" target="_blank">Alexandre Vigneau</a>.
                </div>
              </div>
              <div class="w-full max-w-full px-3 mt-0 shrink-0 lg:w-1/2 lg:flex-none">
                <ul class="flex flex-wrap justify-center pl-0 mb-0 list-none lg:justify-end">
                  <li class="nav-item">
                    <a href="https://www.alexvig.ovh/" class="block px-4 pt-0 pb-1 text-sm font-normal transition-colors ease-in-out text-slate-500" target="_blank">AlexVig</a>
                  </li>
                  <li class="nav-item">
                    <a href="javascript:;" class="block px-4 pt-0 pb-1 text-sm font-normal transition-colors ease-in-out text-slate-500" target="_blank">About Us</a>
                  </li>
                  <li class="nav-item">
                    <a href="javascript:;" class="block px-4 pt-0 pb-1 pr-0 text-sm font-normal transition-colors ease-in-out text-slate-500" target="_blank">License</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>';