<?php

class FileManager {

    private $repsuesta = NULL;

    public function confirmLine($id_to_delete) {

        $csv_stream = fopen('./uploads/bd.csv', 'r');
        if (!$csv_stream) {
            $motivo = "No se pudo abrir el archivo para leer las solicitudes";
            $this->respuesta["uploadOk"] = 0;
            $this->respuesta["motivo"] = $motivo;
            return $this->respuesta;
        }

        $solicitudes = array();
        $num_fila = 0;
        while (($fila = fgetcsv($csv_stream)) !== FALSE) {
            if (strval($num_fila) === $id_to_delete) {
                #$output = shell_exec('sudo rm ./uploads/' . escapeshellarg($fila[5]));
            } else {
                array_push($solicitudes, $fila);
            }
            $num_fila++;
        }
        fclose($csv_stream);

        $csv_stream = fopen('./uploads/bd.csv', 'w');
        foreach ($solicitudes as $solicitud) {
            fputcsv($csv_stream, $solicitud);
        }
        fclose($csv_stream);

    }

    public function deleteLine($id_to_delete) {

        $csv_stream = fopen('./uploads/bd.csv', 'r');
        if (!$csv_stream) {
            $motivo = "No se pudo abrir el archivo para leer las solicitudes";
            $this->respuesta["uploadOk"] = 0;
            $this->respuesta["motivo"] = $motivo;
            return $this->respuesta;
        }

        $solicitudes = array();
        $num_fila = 0;
        while (($fila = fgetcsv($csv_stream)) !== FALSE) {
            if (strval($num_fila) === $id_to_delete) {
                $output = shell_exec('sudo rm ./uploads/' . escapeshellarg($fila[5]));
            } else {
                array_push($solicitudes, $fila);
            }
            $num_fila++;
        }
        fclose($csv_stream);

        $csv_stream = fopen('./uploads/bd.csv', 'w');
        foreach ($solicitudes as $solicitud) {
            fputcsv($csv_stream, $solicitud);
        }
        fclose($csv_stream);

    }

    public function addKey() {

        $uploadOk = 1;

        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $nombre_app = $_POST['nombre_app'];

        $target_dir = "./uploads/";
        $target_file = $target_dir . basename($nombre . $apellido . "-" . $_FILES["fileToUpload"]["name"]);
        $target_filename = basename($nombre . $apellido . "-" . $_FILES["fileToUpload"]["name"]);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $motivo = "";

        // Verificar el tamaño del archivo
        if ($_FILES["fileToUpload"]["size"] > 10000) {
            $motivo = "El archivo es demasiado grande.";
            $uploadOk = 0;
        }

        // Permitir solo ciertos formatos de archivo
        if ($file_type != "pub") {
            $motivo = "Solo se permiten archivos .pub.";
            $uploadOk = 0;
        }

        // Si $uploadOk está establecido en 0 por un error
        if ($uploadOk == 0) {
            $exito = "Tu archivo no se puede subir.";
        } else { // Si todo está bien, intenta subir el archivo
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $exito = "El archivo ha sido subido.";
                $array_submit = array($nombre, $apellido, $email, $telefono, $nombre_app, $target_filename);

                $csv_stream = fopen('./uploads/bd.csv', 'a');
                if (!$csv_stream) {
                    echo "No se pudo abrir el archivo para guardar los datos";
                    exit;
                }
                fputcsv($csv_stream, $array_submit);
                fclose($csv_stream);
            } else {
                $uploadOk = 0;
                $exito = "Hubo un error al subir " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . ".";
            }
        }

        $this->respuesta["exito"] = $exito;
        $this->respuesta["motivo"] = $motivo;
        $this->respuesta["uploadOk"] = $uploadOk;

        return $this->respuesta;

    }
    
}
?>