<?php
    $url_input = $_POST['url_input'];
    
    $reversed = strrev($url_input);
    $extension = "";
    for ($i=0; $i < strlen($url_input); $i++) { 
        if($reversed[$i] != '.'){
            $extension = $extension . $reversed[$i];
        }
        if($reversed[$i] == '.'){
            break;
        }
    }
    $extension = strrev($extension);
    $extension = '.' . $extension;

    shell_exec("python image-saver.py " . $url_input . " holder img");

    $result = shell_exec("python -m scripts.label_image --graph=tf_files/retrained_graph.pb --image=tf_files/training_images/holder/img" . $extension);
    
    if($result == ""){
        echo "We are sorry, but we could not identify what that image is as of right now. Please fill the image category below:
            <form action=\"correction.php\" method=\"POST\">
                <input type=\"text\" name=\"correction\">
                <input type=\"submit\">
            </form>";
    } else {
        echo "Label: " . $result . "<br>";

        echo "Is the label true for the image below?
            <form action=\"correction.php\" method=\"POST\">
                <input type=\"radio\" name=\"rb\" value=\"Yes\">Yes<br>
                <input type=\"radio\" name=\"rb\" value=\"No\">No, the right label is 
                <input type=\"text\" name=\"correction\"><br>
                <input type=\"submit\">
            </form>
            <img src=\"".$url_input."\" style=\"max-width=100px; max-height=100px;\"/>";
    }
?>