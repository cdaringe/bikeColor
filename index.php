<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/normalize.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
        <script src="js/fabric_cd.js"></script>
        <script src="js/json2.js"></script>
        <script src="js/ralColors.js" type="text/javascript"></script>
        <script src="js/colorPicker/iColorPicker_CD.js" type="text/javascript"></script>
        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>

        <style type="text/css">
          canvas { border: 1px solid black; }
        </style>
    </head>

<?php
    $debug = false;
    $bike_imgs = 'bike_imgs';
    //require('../plugins/kint/Kint.class.php');
    function echoln($arg){ echo "$arg\n"; }
?>

    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <div class="header-container">
            <header class="wrapper clearfix">
                <h1 class="title">Bicycle Color Selector</h1>
                <nav>
                    <ul>
                        <li><a href="http://www.ingliscycles.com/">Retrotec</a></li>
                        <li><a href="http://www.ralcolor.com/">RAL Colors</a></li>
                        <li><a href="http://www.cdaringe.net">cdaringe.net</a></li>
                    </ul>
                </nav>
            </header>
        </div>

        <div class="main-container">
            <div class="main wrapper clearfix">
                <article>
                    <header>
                        <h1>Preview</h1>
                        <!--<p>Lorem ipsum</p>-->

                        <canvas height="480" width="640" id="c"></canvas>

                    </header>
                    <!--
                    <section>
                        <h2>article section h2</h2>
                        <p>Lorem ipsum</p>
                    </section>
                    <section>
                        <h2>article section h2</h2>
                        <p>Lorem ipsum</p>
                    </section>
                    <footer>
                        <h3>article footer h3</h3>
                        <p>Lorem ipsum</p>
                    </footer>
                    -->
                </article>

                <aside style="z-index:1000;">
                    <h3>Color Toggler</h3>
                    <p>Adjust the following settings to paint your bicycle!  The following colors are sourced from the RAL pallette.</p>
                    <div style="margin:auto;padding:20px;display:inline">Select Bike: <select id="select_bike"></select></div>
                    <div id='toggler'></div>
                    <div style="margin:auto;width:100px;padding:20px"><a href="#"  onclick="SaveScheme()" class="bigButton">Save</a></div>
                    <div style="margin:auto;width:150px;padding:20px">
                        <span id="saves" style="vertical-align:text-bottom"></span>
                    </div>
                </aside>

            </div> <!-- #main -->
        </div> <!-- #main-container -->

        <div class="footer-container">
            <footer class="wrapper">
                <h3><a href="http://www.cdaringe.net" >.: CD :. </a></h3>
            </footer>
        </div>

        <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>-->

        <script src="js/main.js"></script>

        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src='//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>

        <script>

            <?php
                function BuildBikeJSDataFunction(){
                    global $debug;
                    global $bike_imgs;
                    $dirSubSiteRoot = '/bikeColor/';
                    $dirBikeFromRoot = '/img/bike/';
                    $pwd = getcwd();
                    //All bike files belong in root + img/bike/
                    echoln('function GetBikeImages(){');
                    echoln('//'.$pwd.'/img/bike/');
                    if(is_dir($pwd.'/img/bike/')) $pwd .= $dirBikeFromRoot;
                    else{
                        echo "Error!  Bike stuff not found!\n";
                        return -1;
                    }
                    $currFiles = scandir($pwd,1);
                    if($debug) echoln("// bike dir folders: ".json_encode($currFiles));
                    $allBikeDirs = array();
                    
                    //Get all bike dirs
                    $i=0;
                    foreach ($currFiles as $folderName) {
                        if(strpos($folderName, '.')===FALSE) $allBikeDirs[$folderName] = array();  //{directory: array of image filenames}
                    }
                    
                    //build JS img directory data
                    if($allBikeDirs) echoln('var '.$bike_imgs.' = {};');

                    if($debug) echoln("// folders: ".json_encode($allBikeDirs));

                    //Get bike components
                    foreach ($allBikeDirs as $bikeDirIdx => &$bike_dir){
                        //grab all files in bike directory
                        $tmp = scandir($pwd.$bikeDirIdx);
                        $s = array();
                        $p = array();
                        foreach ($tmp as $img) {
                            $name = preg_replace("/\\.[^.\\s]{3,4}$/", "", $img);
                            $caption = ucfirst(str_replace("_", " ", $name));
                            //keep only pngs and svgs
                            if (strpos($img, '.png')){
                                $bike_dir[] = $img;
                                $p[] = array('filename' => $img, 'caption' => $caption, 'name' => $name, 'path' => $dirSubSiteRoot.$dirBikeFromRoot.$bikeDirIdx.'/'.$img);
                            }
                            elseif (strpos($img, '.svg')) {
                                $bike_dir[] = $img;
                                $s[] = array('filename' => $img, 'caption' => $caption, 'name' => $name, 'path' =>  $dirSubSiteRoot.$dirBikeFromRoot.$bikeDirIdx.'/'.$img);
                            }
                        }

                        if($debug){echoln("// svgs: ".json_encode($s)); echoln("// pngs: ".json_encode($p));}

                        //If images were present in the dir, build img sets
                        if($bike_dir){
                            if($p){
                                echo 'pngs_'.$bikeDirIdx.' = ';//prep pngs array
                                echoln(json_encode($p).';'); 
                            }
                            if($s){
                                echo 'svgs_'.$bikeDirIdx.' = '; //prep imgs array
                                echoln(json_encode($s).';');
                            }
                            echoln('var imgs_'.$bikeDirIdx.' = {svgs: svgs_'.$bikeDirIdx.', pngs: pngs_'.$bikeDirIdx.'};');
                            echoln('$("#select_bike").append( $(new Option( "'.ucfirst(str_replace("_", " ", $bikeDirIdx)).'", "'.$bikeDirIdx.'")).attr("id","option_'.$bikeDirIdx.'") );');
                        }
                        echoln($bike_imgs.'.'.$bikeDirIdx.' = imgs_'.$bikeDirIdx.';');
                    }
                    echoln("return $bike_imgs; }");
                }
                BuildBikeJSDataFunction();
            ?>

        var canvas = new fabric.Canvas('c');
        var bike_imgs = GetBikeImages();

          $(function(){
            //Build intial canvas, default to twin-style
            //Setup bike type changing binding
            $('#select_bike').change(function(){
                LoadNewBike();
            });

            //Initialize canvas
            for (var key in bike_imgs) {
              if (bike_imgs.hasOwnProperty(key)) {
                var first_img_set = bike_imgs[key];
                break;
              }
            }
            RenderAllBikeImgs(first_img_set,canvas); //adds images, svgs auto-generate selectors
        });



        function LoadNewBike(colors){
            colors = colors || null;
            $("#toggler > *").remove();
            PurgeCanvas(canvas);
            $(document).ready( RenderAllBikeImgs(eval('bike_imgs.'+$("#select_bike").val() ) ,canvas,colors) ); //adds images, svgs auto-generate selectors
        }



        function RenderAllBikeImgs(img_lib,cv,colors){
            //render svgs (must be added first!)
            if(colors) var comps_to_color = Object.keys(colors).sort();
            for (var i = img_lib.svgs.length - 1; i >= 0; i--) {
                if(colors) $(document).ready( addSvg( GetImageCaptionMatch(img_lib.svgs,comps_to_color[i]), cv, colors[comps_to_color[i]] ) ); //DoAfterAll to ensure that alphabetical adds are honored
                else $(document).ready( addSvg(img_lib.svgs[i], cv) );
            };
            //render pngs
            for (var i = img_lib.pngs.length - 1; i >= 0; i--) {
                addImg(img_lib.pngs[i],cv);
            };
            cv.renderAll();
        }



        function GetImageCaptionMatch(groupImgData,caption){
            for(var i=0;i<groupImgData.length;i++){
                if(groupImgData[i].caption == caption) return groupImgData[i];
            }
            return null;
        }


        function addSvg(imgData,cv,color){
            var complete = false;
            color = color || ralColors[ Math.floor((ralColors.length/3) * Math.random()) * 3 +1 ];
            fabric.loadSVGFromURL(imgData.path, function(objects, options){
                var im = new fabric.util.groupSVGElements(objects, options);
                im.set({
                  top:cv.height/2,
                  left:cv.width/2,
                  id:'svg_'+imgData.name
                });
                im.set('selectable', false);
                cv.add(im);
                StrokeSVG(im, color ); //initial color
                $(document).ready( addToggler(im, imgData.caption, 'toggler', cv, color) ); //add color picker
            });
        }



        function addImg(imgData,cv){
            fabric.Image.fromURL(imgData.path, function(oImg) {
              oImg.top = cv.height/2;
              oImg.left = cv.width/2;
              oImg.set('selectable', false);
            cv.add(oImg);
            });
        }



        //addToggler -  Adds a fabric svg image color selector, bound to the image
        function addToggler(simg, caption, parent, cv, initial_color_optional){
            $("#"+parent).append( $(document.createElement("span")).text(caption+':  ').css({"font-variant":"small-caps","font-size":"small","float":"left","text-align":"right","width":"85px"}) );
            var input_color = $(document.createElement("input") );
            input_color.attr({"id":'input_'+simg.id,
                                "name":'input_'+simg.id,
                                "caption":caption,
                                "type":'text',
                                "class":'iColorPicker'
                            });

            input_color.change(function(){ //bind color changing
                StrokeSVG(simg, $(this).attr('value') );
                cv.renderAll();
            });

            if(initial_color_optional) input_color.val(initial_color_optional);
            input_color.css({"display":"inline","size":"7","width":"90px"});
            
            input_color.each(function(i){
                var imageUrl='img/color.png';
                    if(i==0){
                        //add color picker frame
                        $("[id=\""+parent+"\"]").append(
                            $(document.createElement("div"))
                                .attr("id","iColorPicker")
                                .css({'display':'none'})
                                .html('<table class="pickerTable" id="pickerTable0"><thead id="hexSection0"> <tr>   <td style="background:#BEBD7F" hx="BEBD7F"></td><td style="background:#C2B078" hx="C2B078"></td>    <td style="background:#C6A664" hx="C6A664"></td>    <td style="background:#E5BE01" hx="E5BE01"></td>    <td style="background:#CDA434" hx="CDA434"></td>    <td style="background:#A98307" hx="A98307"></td>    <td style="background:#E4A010" hx="E4A010"></td>    <td style="background:#DC9D00" hx="DC9D00"></td>    <td style="background:#8A6642" hx="8A6642"></td>    <td style="background:#C7B446" hx="C7B446"></td>    <td style="background:#EAE6CA" hx="EAE6CA"></td>    <td style="background:#E1CC4F" hx="E1CC4F"></td>    <td style="background:#E6D690" hx="E6D690"></td>    <td style="background:#EDFF21" hx="EDFF21"></td>    <td style="background:#F5D033" hx="F5D033"></td>    <td style="background:#F8F32B" hx="F8F32B"></td></tr><tr>   <td style="background:#9E9764" hx="9E9764"></td>    <td style="background:#999950" hx="999950"></td>    <td style="background:#F3DA0B" hx="F3DA0B"></td>    <td style="background:#FAD201" hx="FAD201"></td>    <td style="background:#AEA04B" hx="AEA04B"></td>    <td style="background:#FFFF00" hx="FFFF00"></td>    <td style="background:#9D9101" hx="9D9101"></td>    <td style="background:#F4A900" hx="F4A900"></td>    <td style="background:#D6AE01" hx="D6AE01"></td>    <td style="background:#F3A505" hx="F3A505"></td>    <td style="background:#EFA94A" hx="EFA94A"></td>    <td style="background:#6A5D4D" hx="6A5D4D"></td>    <td style="background:#705335" hx="705335"></td>    <td style="background:#F39F18" hx="F39F18"></td>    <td style="background:#ED760E" hx="ED760E"></td>    <td style="background:#C93C20" hx="C93C20"></td></tr><tr>   <td style="background:#CB2821" hx="CB2821"></td>    <td style="background:#FF7514" hx="FF7514"></td>    <td style="background:#F44611" hx="F44611"></td>    <td style="background:#FF2301" hx="FF2301"></td>    <td style="background:#FFA420" hx="FFA420"></td>    <td style="background:#F75E25" hx="F75E25"></td>    <td style="background:#F54021" hx="F54021"></td>    <td style="background:#D84B20" hx="D84B20"></td>    <td style="background:#EC7C26" hx="EC7C26"></td>    <td style="background:#E55137" hx="E55137"></td>    <td style="background:#C35831" hx="C35831"></td>    <td style="background:#AF2B1E" hx="AF2B1E"></td>    <td style="background:#A52019" hx="A52019"></td>    <td style="background:#A2231D" hx="A2231D"></td>    <td style="background:#9B111E" hx="9B111E"></td>    <td style="background:#75151E" hx="75151E"></td></tr><tr>   <td style="background:#5E2129" hx="5E2129"></td>    <td style="background:#412227" hx="412227"></td>    <td style="background:#642424" hx="642424"></td>    <td style="background:#781F19" hx="781F19"></td>    <td style="background:#C1876B" hx="C1876B"></td>    <td style="background:#A12312" hx="A12312"></td>    <td style="background:#D36E70" hx="D36E70"></td>    <td style="background:#EA899A" hx="EA899A"></td>    <td style="background:#B32821" hx="B32821"></td>    <td style="background:#E63244" hx="E63244"></td>    <td style="background:#D53032" hx="D53032"></td>    <td style="background:#CC0605" hx="CC0605"></td>    <td style="background:#D95030" hx="D95030"></td>    <td style="background:#F80000" hx="F80000"></td>    <td style="background:#FE0000" hx="FE0000"></td>    <td style="background:#C51D34" hx="C51D34"></td></tr><tr>   <td style="background:#CB3234" hx="CB3234"></td>    <td style="background:#B32428" hx="B32428"></td>    <td style="background:#721422" hx="721422"></td>    <td style="background:#B44C43" hx="B44C43"></td>    <td style="background:#6D3F5B" hx="6D3F5B"></td>    <td style="background:#922B3E" hx="922B3E"></td>    <td style="background:#DE4C8A" hx="DE4C8A"></td>    <td style="background:#641C34" hx="641C34"></td>    <td style="background:#6C4675" hx="6C4675"></td>    <td style="background:#A03472" hx="A03472"></td>    <td style="background:#4A192C" hx="4A192C"></td>    <td style="background:#924E7D" hx="924E7D"></td>    <td style="background:#A18594" hx="A18594"></td>    <td style="background:#CF3476" hx="CF3476"></td>    <td style="background:#8673A1" hx="8673A1"></td>    <td style="background:#6C6874" hx="6C6874"></td></tr><tr>   <td style="background:#354D73" hx="354D73"></td>    <td style="background:#1F3438" hx="1F3438"></td>    <td style="background:#20214F" hx="20214F"></td>    <td style="background:#1D1E33" hx="1D1E33"></td>    <td style="background:#18171C" hx="18171C"></td>    <td style="background:#1E2460" hx="1E2460"></td>    <td style="background:#3E5F8A" hx="3E5F8A"></td>    <td style="background:#26252D" hx="26252D"></td>    <td style="background:#025669" hx="025669"></td>    <td style="background:#0E294B" hx="0E294B"></td>    <td style="background:#231A24" hx="231A24"></td>    <td style="background:#3B83BD" hx="3B83BD"></td>    <td style="background:#1E213D" hx="1E213D"></td>    <td style="background:#606E8C" hx="606E8C"></td>    <td style="background:#2271B3" hx="2271B3"></td>    <td style="background:#063971" hx="063971"></td></tr><tr>   <td style="background:#3F888F" hx="3F888F"></td>    <td style="background:#1B5583" hx="1B5583"></td>    <td style="background:#1D334A" hx="1D334A"></td>    <td style="background:#256D7B" hx="256D7B"></td>    <td style="background:#252850" hx="252850"></td>    <td style="background:#49678D" hx="49678D"></td>    <td style="background:#5D9B9B" hx="5D9B9B"></td>    <td style="background:#2A6478" hx="2A6478"></td>    <td style="background:#102C54" hx="102C54"></td>    <td style="background:#316650" hx="316650"></td>    <td style="background:#287233" hx="287233"></td>    <td style="background:#2D572C" hx="2D572C"></td>    <td style="background:#424632" hx="424632"></td>    <td style="background:#1F3A3D" hx="1F3A3D"></td>    <td style="background:#2F4538" hx="2F4538"></td>    <td style="background:#3E3B32" hx="3E3B32"></td></tr><tr>   <td style="background:#343B29" hx="343B29"></td>    <td style="background:#39352A" hx="39352A"></td>    <td style="background:#31372B" hx="31372B"></td>    <td style="background:#35682D" hx="35682D"></td>    <td style="background:#587246" hx="587246"></td>    <td style="background:#343E40" hx="343E40"></td>    <td style="background:#6C7156" hx="6C7156"></td>    <td style="background:#47402E" hx="47402E"></td>    <td style="background:#3B3C36" hx="3B3C36"></td>    <td style="background:#1E5945" hx="1E5945"></td>    <td style="background:#4C9141" hx="4C9141"></td>    <td style="background:#57A639" hx="57A639"></td>    <td style="background:#BDECB6" hx="BDECB6"></td>    <td style="background:#2E3A23" hx="2E3A23"></td>    <td style="background:#89AC76" hx="89AC76"></td>    <td style="background:#25221B" hx="25221B"></td></tr><tr>   <td style="background:#308446" hx="308446"></td>    <td style="background:#3D642D" hx="3D642D"></td>    <td style="background:#015D52" hx="015D52"></td>    <td style="background:#84C3BE" hx="84C3BE"></td>    <td style="background:#2C5545" hx="2C5545"></td>    <td style="background:#20603D" hx="20603D"></td>    <td style="background:#317F43" hx="317F43"></td>    <td style="background:#497E76" hx="497E76"></td>    <td style="background:#7FB5B5" hx="7FB5B5"></td>    <td style="background:#1C542D" hx="1C542D"></td>    <td style="background:#193737" hx="193737"></td>    <td style="background:#008F39" hx="008F39"></td>    <td style="background:#00BB2D" hx="00BB2D"></td>    <td style="background:#78858B" hx="78858B"></td>    <td style="background:#8A9597" hx="8A9597"></td>    <td style="background:#7E7B52" hx="7E7B52"></td></tr><tr>   <td style="background:#6C7059" hx="6C7059"></td>    <td style="background:#969992" hx="969992"></td>    <td style="background:#646B63" hx="646B63"></td>    <td style="background:#6D6552" hx="6D6552"></td>    <td style="background:#6A5F31" hx="6A5F31"></td>    <td style="background:#4D5645" hx="4D5645"></td>    <td style="background:#4C514A" hx="4C514A"></td>    <td style="background:#434B4D" hx="434B4D"></td>    <td style="background:#4E5754" hx="4E5754"></td>    <td style="background:#464531" hx="464531"></td>    <td style="background:#434750" hx="434750"></td>    <td style="background:#293133" hx="293133"></td>    <td style="background:#23282B" hx="23282B"></td>    <td style="background:#332F2C" hx="332F2C"></td>    <td style="background:#686C5E" hx="686C5E"></td>    <td style="background:#474A51" hx="474A51"></td></tr><tr>   <td style="background:#2F353B" hx="2F353B"></td>    <td style="background:#8B8C7A" hx="8B8C7A"></td>    <td style="background:#474B4E" hx="474B4E"></td>    <td style="background:#B8B799" hx="B8B799"></td>    <td style="background:#7D8471" hx="7D8471"></td>    <td style="background:#8F8B66" hx="8F8B66"></td>    <td style="background:#D7D7D7" hx="D7D7D7"></td>    <td style="background:#7F7679" hx="7F7679"></td>    <td style="background:#7D7F7D" hx="7D7F7D"></td>    <td style="background:#B5B8B1" hx="B5B8B1"></td>    <td style="background:#6C6960" hx="6C6960"></td>    <td style="background:#9DA1AA" hx="9DA1AA"></td>    <td style="background:#8D948D" hx="8D948D"></td>    <td style="background:#4E5452" hx="4E5452"></td>    <td style="background:#CAC4B0" hx="CAC4B0"></td>    <td style="background:#909090" hx="909090"></td></tr><tr>   <td style="background:#82898F" hx="82898F"></td>    <td style="background:#D0D0D0" hx="D0D0D0"></td>    <td style="background:#898176" hx="898176"></td>    <td style="background:#826C34" hx="826C34"></td>    <td style="background:#955F20" hx="955F20"></td>    <td style="background:#6C3B2A" hx="6C3B2A"></td>    <td style="background:#734222" hx="734222"></td>    <td style="background:#8E402A" hx="8E402A"></td>    <td style="background:#59351F" hx="59351F"></td>    <td style="background:#6F4F28" hx="6F4F28"></td>    <td style="background:#5B3A29" hx="5B3A29"></td>    <td style="background:#592321" hx="592321"></td>    <td style="background:#382C1E" hx="382C1E"></td>    <td style="background:#633A34" hx="633A34"></td>    <td style="background:#4C2F27" hx="4C2F27"></td>    <td style="background:#45322E" hx="45322E"></td></tr><tr>   <td style="background:#403A3A" hx="403A3A"></td>    <td style="background:#212121" hx="212121"></td>    <td style="background:#A65E2E" hx="A65E2E"></td>    <td style="background:#79553D" hx="79553D"></td>    <td style="background:#755C48" hx="755C48"></td>    <td style="background:#4E3B31" hx="4E3B31"></td>    <td style="background:#763C28" hx="763C28"></td>    <td style="background:#FDF4E3" hx="FDF4E3"></td>    <td style="background:#E7EBDA" hx="E7EBDA"></td>    <td style="background:#F4F4F4" hx="F4F4F4"></td>    <td style="background:#282828" hx="282828"></td>    <td style="background:#0A0A0A" hx="0A0A0A"></td>    <td style="background:#A5A5A5" hx="A5A5A5"></td>    <td style="background:#8F8F8F" hx="8F8F8F"></td>    <td style="background:#FFFFFF" hx="FFFFFF"></td>    <td style="background:#1C1C1C" hx="1C1C1C"></td></tr><tr>   <td style="background:#F6F6F6" hx="F6F6F6"></td>    <td style="background:#1E1E1E" hx="1E1E1E"></td>    <td style="background:#D7D7D7" hx="D7D7D7"></td>    <td style="background:#9C9C9C" hx="9C9C9C"></td>    <td style="background:#828282" hx="828282"></td><td id="dummy_6" style="background:#828282" hx="#828282"></td><td id="dummy_7" style="background:#828282" hx="#828282"></td><td id="dummy_8" style="background:#828282" hx="#828282"></td><td id="dummy_9" style="background:#828282" hx="#828282"></td><td id="dummy_10" style="background:#828282" hx="#828282"></td><td id="dummy_11" style="background:#828282" hx="#828282"></td><td id="dummy_12" style="background:#828282" hx="#828282"></td><td id="dummy_13" style="background:#828282" hx="#828282"></td><td id="dummy_14" style="background:#828282" hx="#828282"></td><td id="dummy_15" style="background:#828282" hx="#828282"></td><td id="dummy_16" style="background:#828282" hx="#828282"></td></tr> </thead><tbody><tr><td style="border:1px solid #000;background:#fff;cursor:pointer;height:60px;-moz-background-clip:-moz-initial;-moz-background-origin:-moz-initial;-moz-background-inline-policy:-moz-initial;" colspan="16" align="center" id="colorPreview"><span style="color:#000;border:1px solid rgb(0, 0, 0);padding:5px;background-color:#fff;font:11px Arial, Helvetica, sans-serif;"></span></td></tr></tbody></table><style>#iColorPicker input{margin:2px}</style>')
                        );
                        //add color picker button div
                        $("[id=\""+parent+"\"]").append(
                            $(document.createElement("div"))
                                .css({'display':'inline'})
                                .attr("id","iColorPickerBg").click(function(){
                                    $("#iColorPickerBg").hide();
                                    $("#iColorPicker")
                                        .fadeOut()
                                    })
                                );
                        $('table.pickerTable td').css({'width':'12px','height':'14px','border':'1px solid #000','cursor':'pointer'});
                        $('#iColorPicker table.pickerTable').css({'border-collapse':'collapse'});
                        $('#iColorPicker').css({'border':'1px solid #ccc','background':'#333','padding':'5px','color':'#fff','z-index':9999})
                    }
                    $('#colorPreview').css({'height':'50px'});

                    //Open picker window and set input box background
                    $(this).css("backgroundColor",$(this).val())
                        .after('<a href="javascript:void(null)" id="icp_'+$(this).attr("id")+'" onclick="iColorShow(\''+$(this).attr("id")+'\',\'icp_'+$(this).attr("id")+'\')"><img src="'+imageUrl+'" style="border:0;margin:0 0 0 3px" align="absmiddle" ></a>');
                
                $("[id=\""+parent+"\"]").append(input_color).append('<a href="javascript:void(null)" id="icp_'+$(this).attr("id")+'" onclick="iColorShow(\''+$(this).attr("id")+'\',\'icp_'+$(this).attr("id")+'\')"><img src="'+imageUrl+'" style="border:0;margin:0 0 0 3px" align="absmiddle" ></a><br/>');

            });
            return true;
        }



        //StrokeSVG - repaints a fabric image svg, simg to be a color, 'color'. Hex, or generic (i.e. 'red') 
        function StrokeSVG(simg, color){
            if (simg.isSameColor && simg.isSameColor() || !simg.paths) {
              simg.setFill(color);
            }
            if (simg.paths) {
              for (var i = 0; i < simg.paths.length; i++) {
                simg.paths[i].setFill(color);
              }
            }
        }



        function SaveScheme(){
            var full_str = $("#select_bike").val() + ",";
            $("#toggler > :input").each(function(){ full_str += $(this).val() + ","; })
            full_str = full_str.slice(0, - 1); //remove final ,

            var scheme = {"bike" : $("#select_bike").val() };
            var colors = {};
            $("#toggler > :input").each(function(){
                var cap = $(this).attr("caption");
                colors[cap] = $(this).val();
            });
            scheme["colors"] = colors;

            //create mini canvas
            var width=175;
            var canvas_title = "save_" + $("#saves > div").length;
            //add save button
                //create div to house save, add save string
                    //grab screenshot of current canvas
            $("#saves").append(
                $(document.createElement("div")).attr({"class":'saveBox',"vertical-align":"text-bottom"})
                    .attr({"id":"div_"+canvas_title,"name":JSON.stringify(scheme)})
                    .append(
                        $(document.createElement("canvas")).attr({
                            "class":"mini_canvas",
                            "id":canvas_title,
                            "height":0.75*width,
                            "width":width
                        })
                    )
                    .append($(document.createElement("span")).text("Load  -  \t").attr({
                        "onclick":"LoadSave('div_"+canvas_title+"')",
                        "class":"deleteLink"
                    }))
                    .append($(document.createElement("span")).text("Delete").attr({
                        "onclick":"DeleteSave('div_"+canvas_title+"')",
                        "class":"deleteLink"
                    }))
            );
            fCanvas = new fabric.Canvas(canvas_title);

            fabric.loadSVGFromString(canvas.toSVG(), function(objects, options){
                var im = new fabric.util.groupSVGElements(objects, options);
                im.set({
                  top:fCanvas.height/2,
                  left:fCanvas.width/2,
                  scaleY:0.75*width/480,
                  scaleX:width/640
                });
                im.set('selectable', false);
                fCanvas.add(im).renderAll();
            });
        }



        function DeleteSave(id){
            $("#"+id).remove();
        }



        function LoadSave(id){
            var scheme = eval('(' + $('#'+id).attr('name')  + ')');
            $("#select_bike").val(scheme["bike"]);
            LoadNewBike(scheme["colors"]); 
        }



        function PurgeCanvas(canvas){
            var obs = canvas.getObjects();
            for(var i=0; i < obs.length;i++){
                canvas.remove(obs[i]);
            }
        }

    </script>
    </body>
</html>
