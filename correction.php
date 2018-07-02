<?php
    ini_set('max_execution_time', 3000); 
    $rb = $_POST['rb'];
    $correction = $_POST['correction'];
    $correction = strtolower($correction);

    if($rb == "No"){
        shell_exec("python correction.py " . $correction);

        for ($i=0; $i < strlen($correction); $i++) { 
            if($correction[$i] == ' '){
                $correction[$i] = '-';
            }
        }

        $increment = 0;
        for($startIndex=1; $startIndex <= 91; $startIndex += 10){
            $search_link = file_get_contents("https://www.googleapis.com/customsearch/v1?key=AIzaSyBlK6AOSPS3Ee9Cyo0eweO3TNtp8AJK3KE&cx=009095537961394434779:9wu87vtldtc&q=" . $correction . "&searchType=image&start=" . $startIndex);
            $search_result = json_decode($search_link, true);
            $count = $search_result['queries']['request'][0]['count'];
            for ($i=0; $i < $count; $i++) { 
                $link = $search_result['items'][$i]['link'];
                shell_exec("python image-saver.py " .  $link . " " . $correction . " img". ($i + $increment));
            }
            $increment += 10;
        }
        
        shell_exec('python -m scripts.retrain --bottleneck_dir=tf_files/bottlenecks --model_dir=tf_files/models/ --summaries_dir=tf_files/training_summaries/"mobilenet_0.50_224" --output_graph=tf_files/retrained_graph.pb --output_labels=tf_files/retrained_labels.txt --architecture="mobilenet_0.50_224" --image_dir=tf_files/training_images');
        
        echo "Thank you for your response. Please click <a href=\"index.html\">here</a> to go to index.html.";
    } else {
        echo file_get_contents("index.html");
    }
?>
