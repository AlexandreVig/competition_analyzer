<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="apple-touch-icon" sizes="76x76"
          href="../assets/img/apple-icon.png"/>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png"/>
    <title>Argon Dashboard 2 Tailwind by Creative Tim</title>
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
            header('Location: dashboard.php');
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
                    header('Location: dashboard.php');
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
                                            <h5>Enregistrez-vous avec</h5>
                                        </div>
                                        <div class="flex flex-wrap px-3 -mx-3 sm:px-6 xl:px-12">
                                            <div class="w-3/12 max-w-full px-1 ml-auto flex-0">
                                                <a class="inline-block w-full px-5 py-2.5 mb-4 font-bold text-center text-gray-200 uppercase align-middle transition-all bg-transparent border border-gray-200 border-solid rounded-lg shadow-none cursor-pointer hover:-translate-y-px leading-pro text-xs ease-in tracking-tight-rem bg-150 bg-x-25 hover:bg-transparent hover:opacity-75" href="javascript:;">
                                                    <svg width="24px" height="32px" viewBox="0 0 64 64" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink32">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <g transform="translate(3.000000, 3.000000)" fill-rule="nonzero">
                                                                <circle fill="#3C5A9A" cx="29.5091719" cy="29.4927506" r="29.4882047"></circle>
                                                                <path d="M39.0974944,9.05587273 L32.5651312,9.05587273 C28.6886088,9.05587273 24.3768224,10.6862851 24.3768224,16.3054653 C24.395747,18.2634019 24.3768224,20.1385313 24.3768224,22.2488655 L19.8922122,22.2488655 L19.8922122,29.3852113 L24.5156022,29.3852113 L24.5156022,49.9295284 L33.0113092,49.9295284 L33.0113092,29.2496356 L38.6187742,29.2496356 L39.1261316,22.2288395 L32.8649196,22.2288395 C32.8649196,22.2288395 32.8789377,19.1056932 32.8649196,18.1987181 C32.8649196,15.9781412 35.1755132,16.1053059 35.3144932,16.1053059 C36.4140178,16.1053059 38.5518876,16.1085101 39.1006986,16.1053059 L39.1006986,9.05587273 L39.0974944,9.05587273 L39.0974944,9.05587273 Z" fill="#FFFFFF"></path>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="w-3/12 max-w-full px-1 flex-0">
                                                <a class="inline-block w-full px-5 py-2.5 mb-4 font-bold text-center text-gray-200 uppercase align-middle transition-all bg-transparent border border-gray-200 border-solid rounded-lg shadow-none cursor-pointer hover:-translate-y-px leading-pro text-xs ease-in tracking-tight-rem bg-150 bg-x-25 hover:bg-transparent hover:opacity-75" href="javascript:;">
                                                    <svg width="24px" height="32px" viewBox="0 0 64 64" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <g transform="translate(7.000000, 0.564551)" fill="#000000" fill-rule="nonzero">
                                                                <path d="M40.9233048,32.8428307 C41.0078713,42.0741676 48.9124247,45.146088 49,45.1851909 C48.9331634,45.4017274 47.7369821,49.5628653 44.835501,53.8610269 C42.3271952,57.5771105 39.7241148,61.2793611 35.6233362,61.356042 C31.5939073,61.431307 30.2982233,58.9340578 25.6914424,58.9340578 C21.0860585,58.9340578 19.6464932,61.27947 15.8321878,61.4314159 C11.8738936,61.5833617 8.85958554,57.4131833 6.33064852,53.7107148 C1.16284874,46.1373849 -2.78641926,32.3103122 2.51645059,22.9768066 C5.15080028,18.3417501 9.85858819,15.4066355 14.9684701,15.3313705 C18.8554146,15.2562145 22.5241194,17.9820905 24.9003639,17.9820905 C27.275104,17.9820905 31.733383,14.7039812 36.4203248,15.1854154 C38.3824403,15.2681959 43.8902255,15.9888223 47.4267616,21.2362369 C47.1417927,21.4153043 40.8549638,25.1251794 40.9233048,32.8428307 M33.3504628,10.1750144 C35.4519466,7.59650964 36.8663676,4.00699306 36.4804992,0.435448578 C33.4513624,0.558856931 29.7884601,2.48154382 27.6157341,5.05863265 C25.6685547,7.34076135 23.9632549,10.9934525 24.4233742,14.4943068 C27.7996959,14.7590956 31.2488715,12.7551531 33.3504628,10.1750144"></path>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="w-3/12 max-w-full px-1 mr-auto flex-0">
                                                <a class="inline-block w-full px-5 py-2.5 mb-4 font-bold text-center text-gray-200 uppercase align-middle transition-all bg-transparent border border-gray-200 border-solid rounded-lg shadow-none cursor-pointer hover:-translate-y-px leading-pro text-xs ease-in tracking-tight-rem bg-150 bg-x-25 hover:bg-transparent hover:opacity-75" href="javascript:;">
                                                    <svg width="24px" height="32px" viewBox="0 0 64 64" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <g transform="translate(3.000000, 2.000000)" fill-rule="nonzero">
                                                                <path d="M57.8123233,30.1515267 C57.8123233,27.7263183 57.6155321,25.9565533 57.1896408,24.1212666 L29.4960833,24.1212666 L29.4960833,35.0674653 L45.7515771,35.0674653 C45.4239683,37.7877475 43.6542033,41.8844383 39.7213169,44.6372555 L39.6661883,45.0037254 L48.4223791,51.7870338 L49.0290201,51.8475849 C54.6004021,46.7020943 57.8123233,39.1313952 57.8123233,30.1515267" fill="#4285F4"></path>
                                                                <path d="M29.4960833,58.9921667 C37.4599129,58.9921667 44.1456164,56.3701671 49.0290201,51.8475849 L39.7213169,44.6372555 C37.2305867,46.3742596 33.887622,47.5868638 29.4960833,47.5868638 C21.6960582,47.5868638 15.0758763,42.4415991 12.7159637,35.3297782 L12.3700541,35.3591501 L3.26524241,42.4054492 L3.14617358,42.736447 C7.9965904,52.3717589 17.959737,58.9921667 29.4960833,58.9921667" fill="#34A853"></path>
                                                                <path d="M12.7159637,35.3297782 C12.0932812,33.4944915 11.7329116,31.5279353 11.7329116,29.4960833 C11.7329116,27.4640054 12.0932812,25.4976752 12.6832029,23.6623884 L12.6667095,23.2715173 L3.44779955,16.1120237 L3.14617358,16.2554937 C1.14708246,20.2539019 0,24.7439491 0,29.4960833 C0,34.2482175 1.14708246,38.7380388 3.14617358,42.736447 L12.7159637,35.3297782" fill="#FBBC05"></path>
                                                                <path d="M29.4960833,11.4050769 C35.0347044,11.4050769 38.7707997,13.7975244 40.9011602,15.7968415 L49.2255853,7.66898166 C44.1130815,2.91684746 37.4599129,0 29.4960833,0 C17.959737,0 7.9965904,6.62018183 3.14617358,16.2554937 L12.6832029,23.6623884 C15.0758763,16.5505675 21.6960582,11.4050769 29.4960833,11.4050769" fill="#EB4335"></path>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="relative w-full max-w-full px-3 mt-2 text-center shrink-0">
                                                <p class="z-20 inline px-4
                                                mb-2 font-semibold leading-normal bg-white text-sm text-slate-400">ou</p>
                                            </div>
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
                                                       checked
                                                       class="w-4.8 h-4.8
                                                       ease -ml-7 rounded-1.4 checked:bg-gradient-to-tl checked:from-blue-500 checked:to-violet-500 after:text-xxs after:font-awesome after:duration-250 after:ease-in-out duration-250 relative float-left mt-1 cursor-pointer appearance-none border border-solid border-slate-200 bg-white bg-contain bg-center bg-no-repeat align-top transition-all after:absolute after:flex after:h-full after:w-full after:items-center after:justify-center after:text-white after:opacity-0 after:transition-all after:content-['\f00c'] checked:border-0 checked:border-transparent checked:bg-transparent checked:after:opacity-100">
                                                <label class="mb-2 ml-1
                                                font-normal cursor-pointer
                                                text-sm text-slate-700"
                                                       for="flexCheckDefault"> J'accepte les <a href="javascript:;" class="font-bold text-slate-700">Termes et Conditions</a> </label>
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
                                    <span class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-blue-500 to-violet-500 opacity-60"></span>
                                    <h4 class="z-20 mt-12 font-bold text-white">
                                        "Attention is the new currency"</h4>
                                    <p class="z-20 text-white ">The more
                                        effortless the writing looks, the more
                                        effort the writer actually put into the
                                        process.</p>
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
                                    <span class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-blue-500 to-violet-500 opacity-60"></span>
                                    <h4 class="z-20 mt-12 font-bold text-white">
                                        "Attention is the new currency"</h4>
                                    <p class="z-20 text-white ">The more
                                        effortless the writing looks, the more
                                        effort the writer actually put into the
                                        process.</p>
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
                   class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12"> Team </a>
                <a href="javascript:;" target="_blank"
                   class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12">
                    Products </a>
                <a href="javascript:;" target="_blank"
                   class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12"> Blog </a>
                <a href="javascript:;" target="_blank"
                   class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12">
                    Pricing </a>
            </div>
            <div class="flex-shrink-0 w-full max-w-full mx-auto mt-2 mb-6 text-center lg:flex-0 lg:w-8/12">
                <a href="javascript:;" target="_blank"
                   class="mr-6 text-slate-400">
                    <span class="text-lg fab fa-dribbble"></span>
                </a>
                <a href="javascript:;" target="_blank"
                   class="mr-6 text-slate-400">
                    <span class="text-lg fab fa-twitter"></span>
                </a>
                <a href="javascript:;" target="_blank"
                   class="mr-6 text-slate-400">
                    <span class="text-lg fab fa-instagram"></span>
                </a>
                <a href="javascript:;" target="_blank"
                   class="mr-6 text-slate-400">
                    <span class="text-lg fab fa-pinterest"></span>
                </a>
                <a href="javascript:;" target="_blank"
                   class="mr-6 text-slate-400">
                    <span class="text-lg fab fa-github"></span>
                </a>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3">
            <div class="w-8/12 max-w-full px-3 mx-auto mt-1 text-center flex-0">
                <p class="mb-0 text-slate-400">
                    Copyright ©
                    <script>
                        document.write(new Date().getFullYear());
                    </script>
                    Argon Dashboard 2 by Creative Tim.
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