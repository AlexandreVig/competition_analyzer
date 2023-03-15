<!DOCTYPE html>
<?php
require "config.php";
require "asset.php";
?>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
	<?php echo $page_favicon ?>    
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Competition Analyzer</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"
          rel="stylesheet"/>
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js"
            crossorigin="anonymous"></script>
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet"/>
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet"/>
    <!-- Main Styling -->
    <link href="../assets/css/argon-dashboard-tailwind.css?v=1.0.1"
          rel="stylesheet"/>
    <!-- Sweetalert -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="m-0 font-sans antialiased font-normal bg-white text-start text-base leading-default text-slate-500">
<?php
require "config.php";

if (isset($_GET["deconnect"])) {
    setcookie("name", null, -1);
    setcookie("id", null, -1);
}

if (isset($_COOKIE["id"]) && isset($_COOKIE["name"])) {
    header('Location: grade.php');
}

$curPageName = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
$mode = $name = $email = $password = $accept_terms = $remember_me = "";

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mode = test_input($_POST["mode"]);
    $email = strtolower(test_input($_POST["email"]));
    $password = test_input($_POST["password"]);
    // Create connection
    $conn = new mysqli($servername, $serverusername, $serverpassword, $dbname);
    // Check connection
    if ($conn->connect_error) {
        header('Location: '.$curPageName.'?error&'.$mode);
        exit();
    }
    if (strcmp($mode, "register") == 0) {
        $name = test_input($_POST["name"]);
        // Verify if user already exist
        $sql = "SELECT id FROM user WHERE email='$email'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            header('Location: '.$curPageName.'?error&'.$mode);
            $conn->close();
            exit();
        }
        $password = password_hash($password, PASSWORD_BCRYPT);
        $session_id = md5($email);
        $sql = "INSERT INTO user (session_id, name, email, password) VALUES ('$session_id', '$name', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            setcookie("name", $name);
            setcookie("id", md5($email));
            header('Location: grade.php');
        } else {
            header('Location: '.$curPageName.'?error&'.$mode);
            $conn->close();
            exit();
        }
    } else {
        $remember_me = $_POST["remember-me"];
        // Connect user
        $sql = "SELECT session_id, name, email, password FROM user WHERE email='$email'";
        $result = $conn->query($sql);
        // Check if user exist
        if ($result->num_rows == 1) {
            while($row = $result->fetch_assoc()) {
                // Connect and set cookies
                if (password_verify($password, $row["password"])) {
                    setcookie("name", $row["name"], ($remember_me ? time()+60*60*24*30 : 0));
                    setcookie("id", $row["session_id"], ($remember_me ? time()
                        +60*60*24*30 : 0));
                    header('Location: grade.php');
                } else {
                    header('Location: '.$curPageName.'?error&'.$mode);
                    $conn->close();
                    exit();
                }
            }
        } else {
            header('Location: '.$curPageName.'?error&'.$mode);
            $conn->close();
            exit();
        }
    }
    $conn->close();
} else {
    ?>
    <div class="container top-0 z-sticky">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 flex-0">

                <nav class="absolute top-0 left-0 right-0 z-30 flex flex-wrap items-center px-4 py-2 m-6 mb-0 lg:flex-nowrap lg:justify-start">
                    <div class="flex items-center justify-between w-full p-0 px-6 mx-auto flex-wrap-inherit">
                        <a class="block m-0 text-xl whitespace-nowrap dark:text-white text-slate-700" href="grade.php">
                            <img src="../assets/img/favicon/android-chrome-512x512.png" class="inline h-full max-w-full transition-all duration-200 dark:hidden ease-nav-brand max-h-8" alt="main_logo" />
                            <img src="../assets/img/favicon/android-chrome-512x512.png" class="hidden h-full max-w-full transition-all duration-200 dark:inline ease-nav-brand max-h-8" alt="main_logo" />
                            <span class="ml-1 font-semibold transition-all duration-200
                                        ease-nav-brand capitalize">Competition analyzer</span>
                        </a>
                        <div class="shadow-sm rounded-xl bg-white/80 backdrop-blur-2xl backdrop-saturate-200 px-4 py-2">
                            <div class="block m-0 text-xl dark:text-white text-slate-700">
                                <h4 class="text-xl m-0">Évaluez la réputation en ligne de votre établissement et de vos concurrents.</h4>
                            </div>
                        </div>

                    </div>
                </nav>
            </div>
        </div>
    </div>
    <main class="mt-0 transition-all duration-200 ease-in-out">
        <section>
            <div class="relative flex items-center min-h-screen p-0 overflow-hidden bg-center bg-cover">
                <div class="container z-1">
                    <?php if (isset($_GET["register"])) { ?>
                        <?php if (isset($_GET["error"])) {?>
                        <script>
                            Swal.fire({
                                icon: "error",
                                title: "Oups...",
                                html: "Quelque chose s'est mal passé !" +
                                    "<br>Vous avez peut-être déjà un compte.",
                                timer: 8000
                            })
                        </script>
                        <?php } ?>
                        <div class="flex flex-wrap -mx-3">
                            <div class="flex flex-col w-full max-w-full px-3 mx-auto lg:mx-0 shrink-0 md:flex-0 md:w-7/12 lg:w-5/12 xl:w-4/12">
                                <div class="relative flex flex-col min-w-0 break-words bg-transparent border-0 shadow-none lg:py4 dark:bg-gray-950 rounded-2xl bg-clip-border">
                                    <div class="p-6 pb-0 mb-0">
                                        <div class="p-6 mb-0 text-center bg-white border-b-0 rounded-t-2xl">
                                            <h5>Enregistrez-vous</h5>
                                        </div>
                                    </div>
                                    <div class="flex-auto p-6">
                                        <form role="form" action="<?php echo $curPageName ?>" method="post">
                                            <div class="mb-4">
                                                <input type="text"
                                                       placeholder="Nom"
                                                       name="name"
                                                       required
                                                       class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none"/>
                                            </div>
                                            <div class="mb-4">
                                                <input type="email"
                                                       placeholder="Email"
                                                       name="email"
                                                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                                                       required
                                                       class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none"/>
                                            </div>
                                            <div class="mb-4">
                                                <input type="password"
                                                       placeholder="Mot de passe"
                                                       name="password"
                                                       required
                                                       class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none"/>
                                            </div>
                                            <div class="min-h-6 pl-7 mb-0.5 block">
                                                <input required
                                                       type="checkbox"
                                                       name="accept-terms"
                                                       class="w-4.8 h-4.8
                                                       ease -ml-7 rounded-1.4 checked:bg-gradient-to-tl checked:from-blue-500 checked:to-violet-500 after:text-xxs after:font-awesome after:duration-250 after:ease-in-out duration-250 relative float-left mt-1 cursor-pointer appearance-none border border-solid border-slate-200 bg-white bg-contain bg-center bg-no-repeat align-top transition-all after:absolute after:flex after:h-full after:w-full after:items-center after:justify-center after:text-white after:opacity-0 after:transition-all after:content-['\f00c'] checked:border-0 checked:border-transparent checked:bg-transparent checked:after:opacity-100">
                                                <label class="mb-2 ml-1
                                                font-normal cursor-pointer
                                                text-sm text-slate-700"
                                                       for="flexCheckDefault"> J'accepte les <a href="javascript:;" target="_blank" class="font-bold text-slate-700">Termes et Conditions générales d'utilisation</a> </label>
                                            </div>
                                            <input type="hidden" name="mode"
                                                   value="register">
                                            <div class="text-center">
                                                <button type="submit"
                                                        class="inline-block w-full px-16 py-3.5 mt-6 mb-0 font-bold leading-normal text-center text-white align-middle transition-all bg-blue-500 border-0 rounded-lg cursor-pointer hover:-translate-y-px active:opacity-85 hover:shadow-xs text-sm ease-in tracking-tight-rem shadow-md bg-150 bg-x-25">
                                                    S'enregistrer
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="border-black/12.5 rounded-b-2xl border-t-0 border-solid p-6 text-center pt-0 px-1 sm:px-6">
                                        <p class="mx-auto mb-6 leading-normal text-sm">
                                            Vous avez déjà un compte? <a
                                                    href="<?php echo $curPageName ?>"
                                                    class="font-semibold text-transparent bg-clip-text bg-gradient-to-tl from-blue-500 to-violet-500">Se connecter</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="absolute top-0 right-0 flex-col justify-center hidden w-6/12 h-full max-w-full px-3 pr-0 my-auto text-center flex-0 lg:flex">
                                <div class="relative flex flex-col justify-center h-full bg-cover px-24 m-4 overflow-hidden bg-[url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signin-ill.jpg')] rounded-xl ">
                                    <span class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-blue-500 opacity-60"></span>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <?php if (isset($_GET["error"])) {?>
                            <script>
                                Swal.fire({
                                    icon: "error",
                                    title: "Oups...",
                                    html: "Quelque chose s'est mal passé !" +
                                        "<br>Vos identifiants sont peut-être incorrectes.",
                                    timer: 8000
                                })
                            </script>
                        <?php } ?>
                        <div class="flex flex-wrap -mx-3">
                            <div class="flex flex-col w-full max-w-full px-3 mx-auto lg:mx-0 shrink-0 md:flex-0 md:w-7/12 lg:w-5/12 xl:w-4/12">
                                <div class="relative flex flex-col min-w-0 break-words bg-transparent border-0 shadow-none lg:py4 dark:bg-gray-950 rounded-2xl bg-clip-border">
                                    <div class="p-6 pb-0 mb-0">
                                        <h4 class="font-bold">Connectez-vous</h4>
                                        <p class="mb-0">Entrez votre adresse email et votre mot de passe pour vous connecter</p>
                                    </div>
                                    <div class="flex-auto p-6">
                                        <form role="form" action="<?php echo $curPageName ?>" method="post">
                                            <div class="mb-4">
                                                <input type="email"
                                                       placeholder="Email"
                                                       name="email"
                                                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                                                       required
                                                       class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none"/>
                                            </div>
                                            <div class="mb-4">
                                                <input type="password"
                                                       placeholder="Mot de passe"
                                                       name="password"
                                                       required
                                                       class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none"/>
                                            </div>
                                            <div class="flex items-center pl-12 mb-0.5 text-left min-h-6">
                                                <input id="rememberMe"
                                                       name="remember-me"
                                                       class="mt-0.5 rounded-10 duration-250 ease-in-out after:rounded-circle after:shadow-2xl after:duration-250 checked:after:translate-x-5.3 h-5 relative float-left -ml-12 w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-zinc-700/10 bg-none bg-contain bg-left bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-blue-500/95 checked:bg-blue-500/95 checked:bg-none checked:bg-right"
                                                       type="checkbox"/>
                                                <label class="ml-2 font-normal cursor-pointer select-none text-sm text-slate-700"
                                                       for="rememberMe">Se
                                                    souvenir de moi
                                                </label>
                                            </div>
                                            <input type="hidden" name="mode"
                                                   value="login">
                                            <div class="text-center">
                                                <button type="submit"
                                                        class="inline-block w-full px-16 py-3.5 mt-6 mb-0 font-bold leading-normal text-center text-white align-middle transition-all bg-blue-500 border-0 rounded-lg cursor-pointer hover:-translate-y-px active:opacity-85 hover:shadow-xs text-sm ease-in tracking-tight-rem shadow-md bg-150 bg-x-25">
                                                    Se connecter
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="border-black/12.5 rounded-b-2xl border-t-0 border-solid p-6 text-center pt-0 px-1 sm:px-6">
                                        <p class="mx-auto mb-6 leading-normal text-sm">
                                            Vous n'avez pas de compte? <a
                                                    href="<?php echo
                                                    $curPageName ?>?register"
                                                    class="font-semibold
                                                    text-transparent
                                                    bg-clip-text
                                                    bg-gradient-to-tl
                                                    from-blue-500
                                                    to-violet-500">S'enregistrer</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="absolute top-0 right-0 flex-col justify-center hidden w-6/12 h-full max-w-full px-3 pr-0 my-auto text-center flex-0 lg:flex">
                                <div class="relative flex flex-col justify-center h-full bg-cover px-24 m-4 overflow-hidden bg-[url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signin-ill.jpg')] rounded-xl ">
                                    <span class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-blue-500 opacity-60"></span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    </main>
<?php } ?>
<footer class="py-12">
    <div class="container">
        <div class="flex flex-wrap -mx-3">
            <div class="flex-shrink-0 w-full max-w-full mx-auto mb-6 text-center lg:flex-0 lg:w-8/12">
                <a href="javascript:;" target="_blank"
                   class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12">
                    Company </a>
                <a href="javascript:;" target="_blank"
                   class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12"> About
                    Us </a>
                <a href="javascript:;" target="_blank"
                   class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12">
                    Pricing </a>
            </div>
            <div class="flex-shrink-0 w-full max-w-full mx-auto mt-2 mb-6 text-center lg:flex-0 lg:w-8/12">
                <a href="https://alexvig.ovh/" target="_blank"
                   class="mr-6 text-slate-400">
                    <span class="text-lg fa fa-globe"></span>
                </a>
                <a href="https://github.com/AlexandreVig" target="_blank"
                   class="mr-6 text-slate-400">
                    <span class="text-lg fab fa-github"></span>
                </a>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3">
            <div class="w-8/12 max-w-full px-3 mx-auto mt-1 text-center flex-0">
                <p class="mb-0 text-slate-400">
                    Copyright © <?php echo date("Y"); ?>
                    Competiton Analyzer by AlexVig.
                </p>
            </div>
        </div>
    </div>
</footer>
</body>
<!-- plugin for scrollbar  -->
<script src="../assets/js/plugins/perfect-scrollbar.min.js" async></script>
<!-- main script file  -->
<script src="../assets/js/argon-dashboard-tailwind.js?v=1.0.1" async></script>
</html>
