<?php

class ShellExecutor {

    public function executeCommand($command) {
        return shell_exec($command);
    }

}

?>