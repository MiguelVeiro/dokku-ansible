<?php

include_once "FileManager.php";
include_once "ShellExecutor.php";

class FormProcessor {

    private $respuesta = NULL;
    private $fileManager;
    private $shellExecutor;

    public function processRequest() {
        
        $this->fileManager = new ShellExecutor();
        $this->shellExecutor = new FileManager();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['confirm'])) {
                $this->confirmRequest();
            } elseif (isset($_POST['discard'])) {
                $this->discardRequest();
            } elseif (isset($_POST['submit'])) {
                $this->submitRequest();
            }
        }
        return $this->respuesta;
    }

    private function confirmRequest() {
        $id_to_confirm = $_POST['id_to_confirm'];
        $nombre_usuario = $_POST['nombre_usuario'];
        $nombre_app = $_POST['nombre_app'];
        $clave_pub = $_POST['clave_pub'];

        $output = shell_exec('sudo /home/user/add-request.sh ' . escapeshellarg($clave_pub) . ' ' . escapeshellarg($nombre_usuario) . ' ' . escapeshellarg($nombre_app));
        
        $this->fileManager->confirmLine($id_to_confirm);

        $this->respuesta["motivo"] = "";
        $this->respuesta["exito"] = $output;
        $this->respuesta["uploadOk"] = 1;

        return $this->respuesta;
    }

    private function discardRequest() {
        $id_to_discard = $_POST['id-to-discard'];

        $this->respuesta = $this->fileManager->deleteLine($id_to_discard);
        
        if ($this->respuesta != NULL) {
            return $this->respuesta;
        }

        $uploadOk = 1;
        $motivo = "Solicitud denegada con éxito.";

        $this->respuesta["uploadOk"] = $uploadOk;
        $this->respuesta["motivo"] = $motivo;
        $this->respuesta["exito"] = "";

        return $this->respuesta;
    }

    private function submitRequest() {
        $this->respuesta = $this->fileManager->addKey();
        return $this->respuesta;
    }
}

?>
