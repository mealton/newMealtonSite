<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 09.03.2020
 * Time: 12:09
 * @var $tag_category string
 * @var $content string
 */
?>
<div class="public-item">
    <?php
    switch ($tag_category) {
        case ('image'):
            echo "<img src='" . str_replace('preview', 'fullsize', $content) . "' class='public-image'>";
            break;
        case ('video'):
            echo "
            <div data-id='plyr-" . ++$i . "'></div>
            <div id='plyr" . $i . "' data-plyr-provider='youtube' data-plyr-embed-id='" . end(explode("/", $content)) . "'></div>
                    <script>
                        const Plyr$i = new Plyr('#plyr$i');                        
                        Plyr$i.on('play', function () {
                            document.title = \"" . addslashes(main_Controller::get_youtube_title(end(explode("/", $content)))) . "\";
                        })
                        Plyr$i.on('pause', function(){
                            document.title = title;
                        });
                        Plyr$i.on('ended', function(){                            
                            if(typeof  Plyr" . ($i + 1) . " === undefined)                               
                              return false;                              
                                setTimeout(function(){                                   
                                    if(Plyr$i.fullscreen.active)
                                        Plyr$i.fullscreen.exit();
                                    setTimeout(function(){                                       
                                        $('html, body').animate({scrollTop:$('[data-id=\"plyr-" . ($i + 1) . "\"]').offset().top - 120}, 1500); 
                                        Plyr" . ($i + 1) . ".play(); 
                                     }, 500);                             
                                }, 600);  
                        });
                    </script>";
            break;
        case ('description'):
            echo "<h6 class='image-description' style='$style'><i>$content</i></h6class>";
            break;
        case ('subtitle'):
            echo "<h3 class='public-subtitle'  style='$style'>$content</h3>";
            break;
        default:
            echo "<p style='$style'>$content</p>";
    }
    ?>
</div>




