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
<html>
  <head>
    <meta charset="UTF-8" />
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
    <!-- Brand icon -->
    <link href="../assets/css/brand-icons.min.css" rel="stylesheet">
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <!-- jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <!-- Main Styling -->
    <link href="../assets/css/argon-dashboard-tailwind.css?v=1.0.1" rel="stylesheet" />
    <!--Regular Datatables CSS-->
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <!--Responsive Extension Datatables CSS-->
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
    <!--Responsive Extension Datatables CSS-->
    <link href="../assets/css/data-table.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
      <!-- Sweetalert -->
      <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <style>
          .dataTables_filter {
              display: none;
          }
      </style>
  </head>

  <body class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
    <div class="absolute w-full bg-blue-500 dark:hidden min-h-75"></div>
    <!-- sidenav  -->
    <aside class="fixed inset-y-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-y-auto antialiased transition-transform duration-200 -translate-x-full bg-white border-0 shadow-xl dark:shadow-none dark:bg-slate-850 max-w-64 ease-nav-brand z-990 xl:ml-6 rounded-2xl xl:left-0 xl:translate-x-0" aria-expanded="false">
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
                              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease"><?php echo $row["texte"] ?></span>
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
                    if (explode("?", $row["url"])[0] == basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING'])) {
                        $class = "py-2.7 bg-blue-500/13 dark:text-white dark:opacity-80 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors";
                    } else {
                        $class = "dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors";
                    }
                    ?>
                    <li class="mt-0.5 w-full">
                        <a class="<?php echo $class ?>" href="<?php echo $row["url"] ?>">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                <i class="relative top-0 text-sm leading-normal <?php echo $row["icon"]?>"></i>
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

    <!-- end sidenav -->

    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
      <!-- Navbar -->
        <?php echo $my_navbar ?>
      <!-- end Navbar -->

      <!-- cards -->
      <div class="w-full px-6 py-6 mx-auto">
          <!-- row 1 -->
          <div class="flex flex-wrap -mx-3">
              <!-- card1 -->
              <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                  <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border z-990">
                      <div class="flex-auto p-4">
                          <div class="flex flex-row -mx-3">
                              <div class="flex-none w-4/5 max-w-full px-3">
                                  <div>
                                      <p class="mb-2 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Origine des critiques</p>
                                      <div className="w-4/5">
                                          <select choices-select="" name="choices" id="test_choice">
                                              <option value="Google">Google<i class="brand-icons-google leading-none text-lg relative top-3.5 text-white"></i></option>
                                              <option value="Tripadvisor">Tripadvisor WIP</option>
                                          </select>
                                      </div>
                                  </div>
                              </div>
                              <div id="origine_icon" class="px-3 text-right basis-1/3 absolute right-1 -translate-y-[50%] top-1/2">
                                  <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-red-600 to-orange-600">
                                      <i class="brand-icons-google leading-none text-lg relative top-3.5 text-white"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        <!-- table 1 -->
          <div class="flex flex-wrap mt-6 -mx-3">
              <div class="flex-none w-full max-w-full px-3">
                  <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                      <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex">
                          <h6 class="dark:text-white">Export de critiques</h6>
                          <div class="inline-block px-6 py-2 mb-4 ml-auto w-3/12">
                              <select choices-select="" name="choices" class="option_type">
                                  <?php if (isset($_GET["date"])) { ?>
                                      <option value="date">Recherche par date</option>
                                      <option value="number">Recherche par nombre</option>
                                  <?php } else { ?>
                                      <option value="number">Recherche par nombre</option>
                                      <option value="date">Recherche par date</option>
                                  <?php } ?>
                              </select>
                          </div>
                      </div>
                      <div class="flex-auto px-8 pt-0 pb-2">
                          <div class="p-0">
                              <div class="w-full flex justify-center p-4">
                                  <div class="<?php echo isset($_GET["date"]) ? "w-3/12" : "w-auto" ?> mr-4 z-990" className="w-3/12">
                                      <?php if (isset($_GET["date"])) { ?>
                                          <div data-placement="top" data-target="tooltip_trigger">
                                              <select choices-select="" name="choices" class="option_value">
                                                  <option value="il y a un jour">il y a un jour</option>
                                                  <option value="il y a une semaine">il y a une semaine</option>
                                                  <option value="il y a 3 semaines">il y a 3 semaines</option>
                                                  <option value="il y a un mois">il y a un mois</option>
                                                  <option value="il y a 2 mois">il y a 2 mois</option>
                                                  <option value="il y a un ans">il y a un ans</option>
                                              </select>
                                          </div>
                                          <div class="z-50 hidden px-2 py-1 text-center text-white bg-black rounded-lg max-w-46 text-sm" id="tooltip" role="tooltip" data-target="tooltip">
                                              Date à partir de laquelle la recherche s'arrête
                                              <div id="arrow" class="invisible absolute h-2 w-2 bg-inherit before:visible before:absolute before:h-2 before:w-2 before:rotate-45 before:bg-inherit before:content-['']" data-popper-arrow></div>
                                          </div>
                                      <?php } else { ?>
                                          <input
                                                  type="number"
                                                  class="w-full h-full pl-6 focus:shadow-primary-outline ease leading-5.6 relative -ml-px block flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow"
                                                  id="option_value"
                                                  min="10"
                                                  value="20"
                                                  step="5"
                                                  max="500"
                                                  data-placement="top" data-target="tooltip_trigger"
                                                  placeholder="Nombre de critiques" />
                                          <div class="z-50 hidden px-2 py-1 text-center text-white bg-black rounded-lg max-w-46 text-sm" id="tooltip" role="tooltip" data-target="tooltip">
                                              Nombre de critiques à récupérer
                                              <div id="arrow" class="invisible absolute h-2 w-2 bg-inherit before:visible before:absolute before:h-2 before:w-2 before:rotate-45 before:bg-inherit before:content-['']" data-popper-arrow></div>
                                          </div>
                                      <?php } ?>
                                  </div>
                                  <div class="relative flex flex-wrap items-stretch w-full
                                  transition-all rounded-lg ease">
                                    <span class="text-sm ease leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                                      <i class="fas fa-search" aria-hidden="true"></i>
                                    </span>
                                      <input type="text" id="pac-input" class="pl-9 text-sm
                                        focus:shadow-primary-outline ease w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" placeholder="Type here..." />
                                  </div>
                                  <div class="w-[11%] ml-4">
                                      <button id="send_review" type="button" class="inline-block px-5 py-3 h-full font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-500 to-violet-500 leading-normal text-xs ease-in tracking-tight-rem shadow-xs bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                          <i class="fas fa-paper-plane" aria-hidden="true"></i>&nbsp;&nbsp;Envoyer
                                      </button>
                                  </div>
                              </div>
                              <!-- table -->
                              <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
                                  <div id="spinner" class="absolute hidden w-1/2" style="left: 50%; transform: translateX(-50%);">
                                      <div class="w-full mb-5">
                                          <div class="flex mb-2">
                                              <span id="status-number" class="ml-auto font-semibold leading-normal text-sm">0%</span>
                                          </div>
                                          <div>
                                              <div class="h-0.75 text-xs flex overflow-visible rounded-lg bg-gray-200">
                                                  <div id="status-bar" style="width: 0.1%" class="bg-gradient-to-tl from-blue-500 to-violet-500 transition-width duration-600 ease rounded-1 -mt-0.4 -ml-px flex h-1.5 flex-col justify-center overflow-hidden whitespace-nowrap text-center text-white"></div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <table id="pac-table" class="stripe hover"
                                         style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
                                      <thead>
                                      <tr>
                                          <th data-priority="1">Auteur</th>
                                          <th data-priority="2">Note</th>
                                          <th data-priority="4">Avis</th>
                                          <th data-priority="3">Date</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      </tbody>
                                  </table>
                              </div>
                              <div class="flex flex-wrap mt-0 -mx-3 p-4" id="controlPanel">
                              </div>
                              <!-- end table -->
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        <!-- cards row 2 -->
        <div class="flex flex-wrap mt-6 -mx-3" id="row-2">
          <div class="w-full max-w-full px-3 mt-0 lg:w-7/12 lg:flex-none">
            <div class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
              <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0
              border-solid p-6 pt-4 pb-0 capitalize">
                <div class="flex items-center">
                  <h6 class="dark:text-white">Comparaisons de notes</h6>
                    <button btn-edition type="button" data-toggle="modal"
                            data-target="#create-configuration"
                            class="inline-block px-6 py-2 mb-4
                  ml-auto font-bold leading-normal text-center text-white
                  align-middle transition-all ease-in bg-blue-500 border-0
                  rounded-lg shadow-md cursor-pointer text-xs
                  tracking-tight-rem hover:shadow-xs hover:-translate-y-px
                  active:opacity-85 capitalize"><i class="fas fa-pencil-alt"
                                                   aria-hidden="true">
                        </i>&nbsp;&nbsp;éditer une configuration</button>
                  <button btn-creation type="button" data-toggle="modal"
                           data-target="#create-configuration"
                          class="inline-block px-6 py-2 mb-4
                  ml-6 font-bold leading-normal text-center text-white
                  align-middle transition-all ease-in bg-blue-500 border-0
                  rounded-lg shadow-md cursor-pointer text-xs
                  tracking-tight-rem hover:shadow-xs hover:-translate-y-px
                  active:opacity-85 capitalize"><i class="fas fa-plus"
                                         aria-hidden="true"> </i>&nbsp;&nbsp;nouvelle configuration</button>
                </div>
              </div>
              <div class="flex-auto p-4">
                <!--Card-->
                  <div class="p-4" className="w-4/5">
                      <select choices-select="" name="choices" class="choices">
                          <?php
                          $user_id = $_COOKIE["id"];
                          $sql = "SELECT config_name FROM configuration WHERE user_id='$user_id'";
                          $result = $conn->query($sql);
                          if ($result->num_rows > 0) {
                              while($row = $result->fetch_assoc()) {
                                  echo "<option value=\"".$row["config_name"]."\">".$row["config_name"]."</option>";
                              }
                          }
                          ?>
                      </select>
                  </div>
                <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
                    <div id="spinner" class="absolute hidden" style="left: 50%; transform: translateX(-50%);"><h1><i class="fas fa-spinner fa-pulse"></i></h1></div>
                  <table id="grade-table" class="stripe hover text-center"
                         style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
                    <thead>
                      <tr>
                          <th data-priority="1">Nom</th>
                          <th data-priority="2">Note</th>
                          <th data-priority="3">Nombre d'avis</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
                <!--/Card-->
              </div>
            </div>
          </div>

          <div class="w-full max-w-full px-3 lg:w-5/12 lg:flex-none">
            <div slider class="relative w-full overflow-hidden rounded-2xl">
              <!-- slide 1 -->
              <div slide class="absolute w-full h-full transition-all duration-500">
                  <div class="border-black/12.5 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0
                            flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                      <div class="flex-auto p-4">
                          <div class="py-4 pr-1 mb-4 bg-gradient-to-tl from-zinc-800 to-zinc-700 rounded-xl">
                              <div>
                                  <canvas id="chart-bars-main" class="chart-canvas"
                                          height="250"></canvas>
                              </div>
                          </div>
                          <div class="w-full px-6 mx-auto max-w-screen-2xl rounded-xl">
                              <div class="flex flex-wrap mt-0 -mx-3" id="controlPanel-main">
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- slide 2 -->
                <div slide class="absolute w-full transition-all duration-500">
                    <div class="border-black/12.5 dark:bg-slate-850
                    relative z-20 flex min-w-0 flex-col break-words rounded-2xl
                  border-0 border-solid bg-white bg-clip-border h-full w-full">
                        <div class="border-black/12.5 mb-0 rounded-t-2xl
                      border-b-0 border-solid p-6 pt-4 pb-0 capitalize">
                            <div class="flex items-center">
                                <h6 class="dark:text-white">édition d'une
                                    configuration</h6>
                            </div>
                        </div>
                        <div class="flex-auto p-4">
                            <!--Card-->
                            <div class="p-4" classname="w-4/5">
                                <select choices-select="" name="choices" class="edit-choices">
                                    <?php
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<option value=\"".$row["config_name"]."\">".$row["config_name"]."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <button id="add-edition-row" type="button" data-toggle="modal" class="inline-block px-6 py-2
                  font-bold leading-normal text-center text-white
                  align-middle transition-all ease-in bg-blue-500 border-0
                  rounded-lg shadow-md cursor-pointer text-xs
                  tracking-tight-rem hover:shadow-xs hover:-translate-y-px
                  active:opacity-85"><i class="fas fa-plus" aria-hidden="true"> </i>&nbsp;&nbsp;Ajouter un établissement</button>
                                <button id="delete-conf" type="button" data-toggle="modal" class="inline-block px-6 py-2 ml-6
                  font-bold leading-normal text-center text-white
                  align-middle transition-all ease-in bg-[#f5365c] border-0
                  rounded-lg shadow-md cursor-pointer text-xs
                  tracking-tight-rem hover:shadow-xs hover:-translate-y-px
                  active:opacity-85"><i class="fas fa-trash-alt" aria-hidden="true"> </i>&nbsp;&nbsp;Supprimer cette configuration</button>
                            </div>
                            <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
                                <table id="pac-edition-table" class="stripe hover text-center"
                                       style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
                                    <thead>
                                    <tr>
                                        <th data-priority="1">Nom</th>
                                        <th data-priority="2">Nom d'affichage</th>
                                        <th data-priority="4">&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <!--/Card-->
                            <div class="w-full px-6 mx-auto max-w-screen-2xl rounded-xl">
                                <div class="flex flex-wrap mt-0 -mx-3">
                                    <div class="dt-buttons">
                                        <button id="save-edit" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-500 to-violet-500 leading-normal text-xs ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                            <span><i class="mr-1 text-lg leading-none fas fa-save" aria-hidden="true"></i> Sauvegarder</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

              <!-- slide 3 -->
              <div slide class="absolute w-full transition-all duration-500">
                  <div class="border-black/12.5 dark:bg-slate-850
                  relative z-20 flex min-w-0 flex-col break-words rounded-2xl
                  border-0 border-solid bg-white bg-clip-border h-full w-full">
                      <div class="border-black/12.5 mb-0 rounded-t-2xl
                      border-b-0 border-solid p-6 pt-4 pb-0 capitalize">
                          <div class="flex items-center">
                              <h6 class="dark:text-white">Création d'une
                                  configuration</h6>
                          </div>
                      </div>
                      <div class="flex-auto p-4">
                          <div class="mb-4">
                              <label for="config-name" class="block text-sm
                              font-medium text-black">Entrez le nom
                                  de votre configuration</label>
                              <div class="w-full flex justify-center p-4 pt-2">
                                  <div class="relative flex flex-wrap items-stretch w-full
                                       transition-all rounded-lg ease">
                                      <input type="text" name="config-name" value=""
                                             id="config-name" class="text-sm
                                             px-3 py-2
                                             focus:shadow-primary-outline
                                             ease w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow pac-target-input" placeholder="Nom">
                                  </div>
                              </div>
                              <button id="add-creation-row" type="button" data-toggle="modal" class="inline-block px-6 py-2
                  font-bold leading-normal text-center text-white
                  align-middle transition-all ease-in bg-blue-500 border-0
                  rounded-lg shadow-md cursor-pointer text-xs
                  tracking-tight-rem hover:shadow-xs hover:-translate-y-px
                  active:opacity-85"><i class="fas fa-plus" aria-hidden="true"> </i>&nbsp;&nbsp;Ajouter un établissement</button>
                          </div>
                              <!--Card-->
                              <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
                                  <table id="pac-creation-table" class="stripe hover text-center"
                                         style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
                                      <thead>
                                      <tr>
                                          <th data-priority="1">Nom</th>
                                          <th data-priority="2">Nom d'affichage</th>
                                          <th data-priority="4">&nbsp;</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      </tbody>
                                  </table>
                              </div>
                              <!--/Card-->
                          <div class="w-full px-6 mx-auto max-w-screen-2xl rounded-xl">
                              <div class="flex flex-wrap mt-0 -mx-3">
                                  <div class="dt-buttons">
                                      <button id="save-creation" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-blue-500 to-violet-500 leading-normal text-xs ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                          <span><i class="mr-1 text-lg leading-none fas fa-save" aria-hidden="true"></i> Sauvegarder</span>
                                      </button>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Control buttons -->
              <button btn-graph type="button" data-placement="top" id="button-graph"
                      data-target="tooltip_trigger" class="absolute right-0 hidden
                      m-6 mt-4 inline-block px-5 py-3 font-bold text-center
                      text-white uppercase align-middle transition-all
                      rounded-lg cursor-pointer bg-gradient-to-tl from-blue-500
                      to-violet-500 leading-normal text-xs ease-in tracking-tight-rem
                      shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                <i class="fas fa-chart-line" aria-hidden="true">
                </i>
              </button>

              <div class="z-50 hidden px-2 py-1 text-center text-white bg-black rounded-lg max-w-46 text-sm" id="tooltip" role="tooltip" data-target="tooltip">
                Afficher le graphique et les contrôles
                <div id="arrow" class="invisible absolute h-2 w-2 bg-inherit before:visible before:absolute before:h-2 before:w-2 before:rotate-45 before:bg-inherit before:content-['']" data-popper-arrow></div>
              </div>
            </div>
          </div>
        </div>

        <!-- cards row 3 -->

        <div class="flex flex-wrap mt-6 -mx-3"  id="row-3">
          <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-7/12 lg:flex-none">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
              <div class="p-4 pb-0 mb-0 rounded-t-4">
                <div class="flex justify-between">
                  <h6 class="mb-2 dark:text-white">Comparaisons De Notes
                      (sans configuration)</h6>
                </div>
              </div>
              <div class="overflow-x-auto p-4">
                <div class="w-full flex justify-center p-4">
                  <div class="relative flex flex-wrap items-stretch w-full
                  transition-all rounded-lg ease">
                    <span class="text-sm ease leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                      <i class="fas fa-search" aria-hidden="true"></i>
                    </span>
                    <input type="text" id="pac-input" class="pl-9 text-sm
                    focus:shadow-primary-outline ease w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" placeholder="Type here..." />
                  </div>
                </div>
                <!-- table -->
                  <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
                    <table id="pac-table" class="stripe hover text-center"
                           style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
                      <thead>
                        <tr>
                          <th data-priority="1">Nom</th>
                          <th data-priority="2">Note</th>
                          <th data-priority="3">Nombre d'avis</th>
                          <th data-priority="4">&nbsp;</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                  <!-- end table -->
              </div>
            </div>
          </div>
          <div class="w-full max-w-full px-3 mt-0 lg:w-5/12 lg:flex-none">
            <div class="border-black/12.5 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
              <div class="flex-auto p-4">
                <div class="py-4 pr-1 mb-4 bg-gradient-to-tl from-zinc-800 to-zinc-700 rounded-xl">
                  <div>
                    <canvas id="chart-bars" class="chart-canvas"
                            height="250"></canvas>
                      </div>
                  </div>
                  <div class="w-full px-6 mx-auto max-w-screen-2xl
                  rounded-xl">
                        <div class="flex flex-wrap mt-0 -mx-3" id="controlPanel">
                        </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
        <?php
        echo $my_footer;
        ?>
      </div>
      <!-- end cards -->
    </main>
  </body>
  <!-- plugin for charts  -->
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <!-- plugin for scrollbar  -->
  <script src="../assets/js/plugins/perfect-scrollbar.min.js" async></script>
  <!--Datatables -->
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
  <script src="https://www.creative-tim.com/learning-lab/assets/tailwind-argon-dashboard/choices.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="../assets/js/manual-notation-comparison.js"></script>
  <script src="../assets/js/my-select.js"></script>
  <!-- google maps api -->
  <script async src="https://maps.googleapis.com/maps/api/js?key=api_key&libraries=places&callback=initMap&language=fr">
  </script>
  <!-- main script file  -->
  <script src="../assets/js/argon-dashboard-tailwind.js?v=1.0.1" async></script>
</html>
<?php
$conn->close();
?>
