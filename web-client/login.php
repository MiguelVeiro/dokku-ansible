<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <title>Dokku - Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container-fluid p-0 m-0 vh-100">
        <section class="h-100" style="background-color: #10204c;">
            <div class="container py-5">
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="col col-xl-6">
                        <div class="d-flex mb-3">
                            <img class="me-3" src="https://avatars3.githubusercontent.com/u/13455795?v=3&s=400" alt="" width="55">
                            <span class="align-self-center pt-2 h2 text-white">Dokku</span>
                        </div>
                        <div class="card mt-5" style="border-radius: 1rem;">
                            <div class="row g-0">
                                <div class="d-flex align-items-center">
                                    <div class="card-body p-4 p-lg-5 text-black">
                                        <form action="" method="post">
                                            <div class="d-flex align-items-center mb-3 pb-1">
                                                <i class="h1 bi bi-person-bounding-box m-0 pe-3"></i>
                                                <span class="h1 fw-bold mb-0">Identificarse</span>
                                            </div>
                                            <div data-mdb-input-init class="form-outline mt-4 mb-2">
                                                <input type="text" name="username" id="username" class="form-control form-control-lg" />
                                                <label class="form-label">Usuario</label>
                                            </div>
                                            <div data-mdb-input-init class="form-outline mb-2">
                                                <input type="password" name="password" id="password" class="form-control form-control-lg" />
                                                <label class="form-label">Contraseña</label>
                                            </div>
                                            <div class="">
                                                <button class="btn btn-primary btn-lg px-3 me-2" type="submit" name="login">
                                                    <i class="bi bi-box-arrow-in-right pe-2"></i>
                                                    Iniciar sesión
                                                </button>
                                                o
                                                <button class="btn btn-outline-primary btn-lg px-3 ms-2" type="submit" name="invitado">
                                                    Entrar como invitado
                                                    <i class="bi bi-arrow-right ps-2"></i>
                                                </button>
                                            </div>
                                        </form>
                                        <?php
                                            session_start();
                                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                                $username = $_POST['username'];
                                                $password = $_POST['password'];

                                                if ( isset($_POST['invitado']) ) {
                                                    header('Location: index.php');
                                                    exit;
                                                }

                                                if ($username === 'admin' && $password === 'admin') {
                                                    $_SESSION['loggedin'] = true;
                                                    $_SESSION['username'] = $username;
                                                    header('Location: index.php');
                                                    exit;
                                                } else {
                                                    echo "Credenciales incorrectas.";
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
