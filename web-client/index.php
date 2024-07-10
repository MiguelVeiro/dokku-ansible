<?php
include_once "./class/FormProcessor.php";
include_once "./class/SessionManager.php";

$formProcessor = new FormProcessor();
$sessionManager = new SessionManager();

$sessionManager->manageSession();
$username = $sessionManager->username;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <title>Panel Dokku</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body style="background-color: #10204c;">
    <div class="container-fluid p-0 m-0 vh-100">
        <?php

            $respuesta = $formProcessor->processRequest();

            if (!is_null($respuesta)) {
            
                $exito = $respuesta["exito"];
                $motivo = $respuesta["motivo"];
                $uploadOk = $respuesta["uploadOk"];

            }
            
        ?>
        <section class="">
            <div class="container py-2">
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="col col-xl-10">
                        <div class="d-flex mt-1">
                            <img class="me-3" src="https://avatars3.githubusercontent.com/u/13455795?v=3&s=400" alt="" width="55">
                            <span class="align-self-center pt-2 h2 text-white">Dokku</span>
                        </div>
                        <div class="mb-2">
                            <form class="form-inline" action="" method="post">
                                <span class="text-white"><i class="bi bi-person-fill m-1"></i><?php echo $username; ?></span>
                                <button type="submit" name="logout" class="btn btn-tertiary text-danger pt-1">
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </form>
                        </div>
                        <?php
                        if ( isset($uploadOk) && $uploadOk == 0 ) {
                        ?>
                            <div class="card mb-4" style="border-radius: 1rem; background-color: #e06666;">
                                <div class="row g-0">
                                    <div class="d-flex align-items-center">
                                        <div class="card-body text-black">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-cloud-slash-fill me-3"></i>
                                                <?php
                                                    echo $exito . " " . $motivo;
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                            } else if ( isset($uploadOk) && $uploadOk == 1 ) {
                        ?>
                                <div class="card mb-4" style="border-radius: 1rem; background-color: #9ae18a;">
                                    <div class="row g-0">
                                        <div class="d-flex align-items-center">
                                            <div class="card-body text-black">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-cloud-check-fill me-3"></i>
                                                    <?php
                                                        echo $exito . " " . $motivo;
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                        }

                        if ( $username === 'admin' ) {
                        ?>
                            <div class="card mb-4" style="border-radius: 1rem;"> <!-- Claves SSH actuales -->
                                <div class="row g-0">
                                    <div class="d-flex align-items-center">
                                        <div class="card-body p-4 p-lg-5 text-black">
                                            <div class="d-flex align-items-center">
                                                <i class="h1 bi bi-key m-0 pe-3"></i>
                                                <span class="h1 fw-bold mb-0">Claves SSH actuales</span>
                                            </div>
                                            <div class="d-flex align-items-center pb-1">
                                                <pre class="m-0" style="white-space: pre-line">
                                                    <?php
                                                    // Ejecutar el comando 'dokku ssh-keys:list' y mostrar su salida
                                                    $output = shell_exec('sudo dokku ssh-keys:list');
                                                    $lines = explode("\n", $output);
                                                    for ( $i = 0; $i < count($lines); $i++) {
                                                        $filtered_line = strstr($lines[$i], 'SSHCOMMAND_ALLOWED_KEYS', true);
                                                        echo "\n" . $filtered_line;
                                                    }
                                                    ?>
                                                </pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-4" style="border-radius: 1rem;"> <!-- Solicitudes pendientes -->
                                <div class="row g-0">
                                    <div class="d-flex align-items-center">
                                        <div class="card-body p-4 p-lg-5 text-black">
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="h1 bi bi-plus-square-dotted m-0 pe-3"></i>
                                                <span class="h1 fw-bold mb-2">Solicitudes pendientes</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <table class="table table-hover align-middle m-0">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Nombre</th>
                                                            <th scope="col">Aplicación</th>
                                                            <th scope="col">E-mail</th>
                                                            <th scope="col">Teléfono</th>
                                                            <th scope="col">Clave pública</th>
                                                            <th scope="col"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $csv_stream = fopen('./uploads/bd.csv', 'r');
                                                            if (!$csv_stream) { echo "No se pudo abrir el archivo para leer los datos"; }
                                                            else {
                                                                $num_fila = 0;
                                                                while( ($fila = fgetcsv($csv_stream)) != FALSE ) {
                                                        ?>
                                                                    <tr>
                                                                        <td><?php echo $fila[0] . " " . $fila[1]; ?></td>
                                                                        <td><?php echo $fila[4]; ?></td>
                                                                        <td><?php echo $fila[2]; ?></td>
                                                                        <td><?php echo $fila[3]; ?></td>
                                                                        <td><?php echo $fila[5]; ?></td>
                                                                        <td>
                                                                            <form class="d-inline"
                                                                                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                                                                method="post" enctype="multipart/form-data"
                                                                            >
                                                                                <input type="hidden" name="nombre_usuario" value="<?php echo $fila[0] . $fila[1]; ?>" />
                                                                                <input type="hidden" name="nombre_app" value="<?php echo $fila[4]; ?>" />
                                                                                <input type="hidden" name="clave_pub" value="<?php echo $fila[5]; ?>" />
                                                                                <input type="hidden" name="id_to_confirm" value="<?php echo $num_fila; ?>" />
                                                                                <button type="submit" class="btn btn-success btn-sm"
                                                                                    name="confirm"
                                                                                >
                                                                                    <i class="bi bi-check-lg"></i>
                                                                                </button>
                                                                            </form>
                                                                            <form class="d-inline"
                                                                                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                                                                method="post" enctype="multipart/form-data"
                                                                            >
                                                                                <input type="hidden" name="id-to-discard" value="<?php echo $num_fila; ?>" />
                                                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                                    name="discard"
                                                                                >
                                                                                    <i class="bi bi-x"></i>
                                                                                </button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                        <?php
                                                                    $num_fila++;
                                                                }
                                                                fclose($csv_stream);
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="card mb-4" style="border-radius: 1rem;">
                            <div class="row g-0">
                                <div class="d-flex align-items-center">
                                    <div class="card-body p-4 p-lg-5 text-black">
                                        <div class="d-flex align-items-center mb-3 pb-1">
                                            <i class="h1 bi bi-box2-heart m-0 pe-3"></i>
                                            <span class="h1 fw-bold mb-0">Añade una petición de despliegue</span>
                                        </div>
                                        <form
                                            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                            method="post"
                                            enctype="multipart/form-data"
                                        >
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label for="nombre">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" pattern="[a-zA-Z]+" title="Solo se permiten letras" required>
                                            </div>
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label for="apellido">Apellido</label>
                                                <input type="text" class="form-control" id="apellido" name="apellido" pattern="[a-zA-Z]+" title="Solo se permiten letras" required>
                                            </div>
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label for="email">Correo electrónico</label>
                                                <input type="email" class="form-control" id="email" name="email" required>
                                            </div>
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label for="telefono">Teléfono</label>
                                                <input type="telefono" class="form-control" id="telefono" name="telefono" pattern="[0-9]+" required>
                                            </div>
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label for="fileToUpload">Indica aquí tu clave pública (.pub)</label>
                                                <br>
                                                <input type="file" class="form-control-file" id="fileToUpload" name="fileToUpload">
                                                <br>
                                                <small id="fileHelp" class="form-text text-muted">Formato: nombre_dispositivo.pub</small>
                                            </div>
                                            <div data-mdb-input-init class="form-outline mb-4">
                                                <label for="nombre_app">¿Qué nombre va a tener tu aplicación?</label>
                                                <input type="nombre_app" class="form-control" id="nombre_app" name="nombre_app" pattern="[a-z0-9]+" title="Solo se permiten letras minúsculas o números" required>
                                            </div>
                                            <div class="">
                                                <button type="submit" class="btn btn-primary btn-lg" name="submit">
                                                    <i class="bi bi-cloud-upload-fill pe-1"></i>
                                                    Enviar datos y crear petición
                                                </button>
                                            </div>
                                        </form>
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
