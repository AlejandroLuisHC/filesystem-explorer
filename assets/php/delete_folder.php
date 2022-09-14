
<?php 
    session_start();

    deleteFolder();

    function deleteFolder() {
        $dirPath = substr($_SESSION['path'], 3);

        if (!is_readable($dirPath)) {
            rmdir($dirPath);
            header ('Location: ../../index.php');
        } else {
            deleteInnerFiles($dirPath);
            rmdir($dirPath);
            header ('Location: ../../index.php');
        } 
    }

    function deleteInnerFiles($path) {
        if(is_dir($path)) {
            if ($handle = opendir($path)) {
                $files = [];
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry !="..") {
                        array_push($files, $entry);
                    }
                }
                for ($i = 0; $i < count($files); $i++) {
                    if(!is_dir($path.'/'.$files[$i])) {
                        unlink($path.'/'.$files[$i]);
                    } else {
                        if (!is_readable($path.'/'.$files[$i])) {
                            rmdir($path.'/'.$files[$i]);
                        } else {
                            deleteInnerFiles($path.'/'.$files[$i]);
                            rmdir($path.'/'.$files[$i]);
                        }
                    }
                }
            }
        }        
    }