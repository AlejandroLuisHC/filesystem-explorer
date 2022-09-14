<?php
    session_start();
    include_once ("new_file_index.php");
    function getFolders($path) {
        $serverPath         = $_SESSION['serverPath'];
        $serverPathOrigin   = $_SESSION['serverPathOrigin'];
        // $depthPath = explode('/', $path);
        // $jumps = str_repeat('../', count($depthPath)-1);
        if(is_dir($path)) {
            if ($handle = opendir($path)) {
                echo '<ul>';
                $files = [];
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry !="..") {
                        array_push($files, $entry);
                    }
                }
                for ($i = 0; $i < count($files); $i++) {
                    $newPathRelative = $path.'/'.$files[$i];
                    $newPath = substr($path.'/'.$files[$i], 1);
                    if(is_dir("$newPathRelative")) {
                        echo '<a href="'.$serverPath.$newPath.'/'.$files[$i].'.php"><li>'. $files[$i] . ' <i class="fa-solid fa-caret-right"></i></li></a>';
                        getFolders($newPathRelative);
                        newIndex($files[$i], "$newPath", $_SESSION['page']);
                    }
                }
                echo '</ul>';
            }
        }
    }
    function getFoldersInner($path) {
        $serverPath         = $_SESSION['serverPath'];
        $serverPathOrigin   = $_SESSION['serverPathOrigin'];
        $jumps              = $_SESSION['jumps'];
        // $depthPath = explode('/', $path);
        // $jumps = str_repeat('../', count($depthPath)-1);
        if(is_dir($path)) {
            if ($handle = opendir($path)) {
                echo '<ul>';
                $files = [];
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry !="..") {
                        array_push($files, $entry);
                    }
                }
                for ($i = 0; $i < count($files); $i++) {
                    $newPathRelative = $path.'/'.$files[$i];
                    $newPath = substr($path.'/'.$files[$i], 1);
                    if(is_dir("$newPathRelative")) {
                        echo '<a href="'.$serverPath.substr($newPath, strlen($jumps) - 1).'/'.$files[$i].'.php"><li>'. $files[$i] . ' <i class="fa-solid fa-caret-right"></i></li></a>';
                        getFoldersInner($newPathRelative);
                        newIndex($files[$i], substr($newPath, strlen($jumps) - 1), $_SESSION['page']);
                    }
                }
                echo '</ul>';
            }
        }
    }
    
    function getFiles($path) {
        $serverPath         = $_SESSION['serverPath'];
        // $depthPath = explode('/', $path);
        // $jumps = str_repeat('../', count($depthPath)-1);
        if(is_dir($path)) {
            if ($handle = opendir($path)) {
                $files = [];
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry !="..") {
                        array_push($files, $entry);
                    }
                }
                for ($i = 0; $i < count($files); $i++) {
                    $thereAreFiles = false;
                    if(!is_dir($path.'/'.$files[$i])) {
                        $thereAreFiles = true;  
                    }
                    if ($thereAreFiles) {
                        echo '<h4 class="files-path">My Files'. substr($path, 6). '</h4>';
                        echo '<div class="files-container">';
                        for ($i = 0; $i < count($files); $i++) {
                            $newPathRelative = $path.'/'.$files[$i];
                            $newPathAdd = substr($path.'/'.$files[$i], 1);
                            if(!is_dir($newPathRelative)) {
                                $infoFile = pathinfo("$serverPath/$newPathAdd");
                                $folderName = pathinfo($path)['filename'];
                                if ($infoFile['basename']  !== "$folderName.php") {
                                    echo '<a class="file-link" target="_blank" href="'.$serverPath.$newPathAdd.'"><div class="found-file">';
                                    echo '<div class="icon-text">';
                                    echo '<img class="file-icon" src="'.$serverPath.'/assets/img/'.$infoFile['extension'].'.png" alt="'.$infoFile['extension'].' logo" width="30px">';
                                    if (strlen($infoFile['filename']) > 13) {
                                        echo '<span>'.substr($infoFile['filename'], 0, 10).'...</span>';
                                    } else {
                                        echo '<span>'.$infoFile['filename'].'</span>';
                                    }
                                    echo '</div>';
                                    echo '<div class="info-file">'; 
                                    echo '<p>File name: '.$infoFile['filename'].'</p>';
                                    echo '<p>Extension: '.$infoFile['extension'].'</p>';
                                    if (filesize($newPathRelative) < 1000000) {
                                        echo '<p>Size: '. round(((filesize($newPathRelative))/1000), 2) .' Kb</p>';
                                    } else {
                                        echo '<p>Size: '. round(((filesize($newPathRelative))/1000000), 2) .' Mb</p>';
                                    }
                                    echo '<p>Created: '.date("D d M Y g:i a", filectime($newPathRelative)).'</p>';
                                    echo '<p>Modified: '.date("D d M Y g:i a", filemtime($newPathRelative)).'</p>';
                                    echo '</div>'; 
                                    echo '</div></a>';
                                }
                            } 
                        }
                        echo '</div>';
                    }
                }
                
                for ($i = 0; $i < count($files); $i++) {
                    if(is_dir($path.'/'.$files[$i])) {
                        getFiles($path.'/'.$files[$i]);
                    }
                }
            }
        }
    }
    
    function getFilesInner($path) {
        $serverPath         = $_SESSION['serverPath'];
        $jumps              = $_SESSION['jumps'];
        // $depthPath = explode('/', $path);
        // $jumps = str_repeat('../', count($depthPath)-1);
        if(is_dir($path)) {
            if ($handle = opendir($path)) {
                $files = [];
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry !="..") {
                        array_push($files, $entry);
                    }
                }
                for ($i = 0; $i < count($files); $i++) {
                    $thereAreFiles = false;
                    if(!is_dir($path.'/'.$files[$i])) {
                        $thereAreFiles = true;  
                    }
                    if ($thereAreFiles) {
                        echo '<h4 class="files-path">My Files'. substr($path, 6). '</h4>';
                        echo '<div class="files-container">';
                        for ($i = 0; $i < count($files); $i++) {
                            $newPathRelative = $path.'/'.$files[$i];
                            $newPathAdd = substr($path.'/'.$files[$i], 1);
                            if(!is_dir($newPathRelative)) {
                                $infoFile = pathinfo("$serverPath/$newPathAdd");
                                $folderName = pathinfo($path)['filename'];
                                if ($infoFile['basename']  !== "$folderName.php") {
                                    echo '<a class="file-link" target="_blank" href="'.$serverPath.substr($newPathAdd, strlen($jumps) - 1).'"><div class="found-file">';
                                    echo '<div class="icon-text">';
                                    echo '<img class="file-icon" src="'.$serverPath.'/assets/img/'.$infoFile['extension'].'.png" alt="'.$infoFile['extension'].' logo" width="30px">';
                                    if (strlen($infoFile['filename']) > 13) {
                                        echo '<span>'.substr($infoFile['filename'], 0, 10).'...</span>';
                                    } else {
                                        echo '<span>'.$infoFile['filename'].'</span>';
                                    }
                                    echo '</div>';
                                        echo '<div class="info-file">'; 
                                        echo '<p>File name: '.$infoFile['filename'].'</p>';
                                        echo '<p>Extension: '.$infoFile['extension'].'</p>';
                                        if (filesize($newPathRelative) < 1000000) {
                                            echo '<p>Size: '. round(((filesize($newPathRelative))/1000), 2) .' Kb</p>';
                                        } else {
                                            echo '<p>Size: '. round(((filesize($newPathRelative))/1000000), 2) .' Mb</p>';
                                        }
                                        echo '<p>Created: '.date("D d M Y g:i a", filectime($newPathRelative)).'</p>';
                                        echo '<p>Modified: '.date("D d M Y g:i a", filemtime($newPathRelative)).'</p>';
                                        echo '</div>'; 
                                    echo '</div></a>';
                                }
                            } 
                        }
                        echo '</div>';
                    }
                }

                for ($i = 0; $i < count($files); $i++) {
                    if(is_dir($path.'/'.$files[$i])) {
                        getFilesInner($path.'/'.$files[$i]);
                    }
                }
            }
        }
    }
