<?php
/* * * begin the session ** */
session_start();

if (!isset($_SESSION['user_id'])) {
    $message = 'You must be logged in to access this page';
} else {
    try {
        /*         * * connect to database ** */
        /*         * * mysql hostname ** */
        $mysql_hostname = 'localhost';

        /*         * * mysql username ** */
        $mysql_username = 'root';

        /*         * * mysql password ** */
        $mysql_password = '';

        /*         * * database name ** */
        $mysql_dbname = 'phpro_auto';


        /*         * * select the users name from the database ** */
        $dbh = new PDO("mysql:host=$mysql_hostname;dbname=$mysql_dbname", $mysql_username, $mysql_password);
        /*         * * $message = a message saying we have connected ** */

        /*         * * set the error mode to excptions ** */
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /*         * * prepare the insert ** */
        $stmt = $dbh->prepare("SELECT phpro_username FROM phpro_users 
        WHERE phpro_user_id = :phpro_user_id");

        /*         * * bind the parameters ** */
        $stmt->bindParam(':phpro_user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        /*         * * execute the prepared statement ** */
        $stmt->execute();

        /*         * * check for a result ** */
        $phpro_username = $stmt->fetchColumn();
        $_SESSION['user_name'] = $phpro_username;

        /*         * * if we have no something is wrong ** */
    } catch (Exception $e) {
        
    }
}
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script src="raphael-2.1.2.js"></script>
        <script src="jquery-1.11.3.min.js"></script>
        <script src="jquery.liteuploader.js"></script>
        <script src="raphael.free_transform.js"></script>
        <script src="FileSaver.min.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="jquery.ui.datepicker-es.js"></script>
        <script src="warningMsg.js"></script>


        <style>
            #divPageMain {   /* min-width: 960px; */
                margin: 54px 20px 30px 20px;
                padding-top: 20px;
                position: relative;
                min-height: 500px;}

            #divPageMain.mainSC {
                margin: 54px 0px 0px 0px;
                padding: 0px 0px 0px 0px;
            }

            #divPageMenuSC {
                float: left;
                width: 200px;
            }

            #divPageMenuSC > .title {
                width: 193px;
                padding: 20px;
                background-color: #7200BE;
                border-right: 3px solid #520386;
                border-top: 3px solid #520386;
                border-bottom: 1px solid #520386;
            }

            #divPageMenuSC > .title > a {
                font-size: 25px;
                color: #f5f5f5;
                text-shadow: 0px 0px 15px #344900;
            }

            #divPageMenuSC > .title div {
                font-size: 14px;
                color: #E5F0C9;
                margin-top: 5px;
            }

            #divPageMain.mainSC > .main {
                margin-left: 236px;
                background-color: #e7e7e7;
                min-height: 668px;
                padding: 30px;
                font-size: 14px;
                color: #666;
                box-shadow: 0px 0px 15px #999;
            }


            //.canvas { width:850px; left:120px;height:500px;position: relative;}
            .canvas {width:850px; height:500px;border:3px solid grey; position: relative; border-radius:6px; top:50%;}


            text {
                -webkit-touch-callout: none;
                -webkit-user-select: none;
                -khtml-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                cursor: pointer;
                //user-select: none;
            }

            html {
                -webkit-font-smoothing: antialiased;
            }

            html, body {
                margin: 0px;
                padding: 0px;
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 10pt;
                min-height: 100%;
            }

            /*div {
                display: block;
            }*/

            #divPageHeader {
                position: fixed;
                top: 0px;
                width: 100%;
                z-index: 100;
            }

            #divPageHeader.totalWidth #divPageHeaderInside {
                width: 100%;
            }

            #divPageHeader #divPageHeaderInside {
                width: 960px;
                margin: 0px auto;
                box-shadow: 0px 0px 10px black;
                height: 54px;
                background-color: #333;
            }

            #divPageHeader #divPageHeaderInside #divHeaderLogo {
                width: 120px;
                height: 46px;
                padding: 8px 5px 0px 7px;
                background-color: #262;
                margin-right: 10px;
            }

            #divPageHeader #divPageHeaderInside > a {
                height: 44px;
                font-size: 17px;
                cursor: pointer;
                color: #ccc;
                text-decoration: none;
                transition: background-color 0.8s ease;
            }

            a:visited {
                color: #777;
                text-decoration: none;
            }

            a:link {
                color: #777;
                text-decoration: none;
            }

            .left {
                float: left;
            }

            .right {
                float: right;
            }

            a {
                color: #777;
                text-decoration: none;
                cursor: pointer;
            }

            #divPageHeader #divPageHeaderInside #divHeaderLogo .img {
                width: 121px;
                height: 38px;
                background-image: url(logo.png);
                background-position: 0px 0px;
            }






        </style>

        <style>

            #panelHolder {
                width:235px;
                height:600px;
                position:absolute;
                background-color: #e7e7e7;
                overflow-x:hidden;
                overflow-y: scroll;
            }

            #imgHolder {
                width:220px;
                height:200px;
                position:relative;
                //display:inline-block;
                overflow:hidden;
                border-style: none none solid none;
                border-width: 1px;
            }

            #imgHolder > img {
                position:absolute;
                //top:50%;
                width:170px;
                height: 150px;
                //min-height:100%;
                //display:block;
                left: 10px;
                top: 30px;
                border-style: solid;
                border-width: 1px;
                cursor: pointer;
                //-webkit-transform: translate(-50%, -50%);
                //min-width:100%;
            }

            #imgHolder > div {
                position:absolute;
                //top:50%;
                //min-height:100%;
                //display:block;
                left: 20px;
                top: 10px;
                text-decoration-color: #000

                    //-webkit-transform: translate(-50%, -50%);
                    //min-width:100%;
            }

            #divControls {
                margin-bottom: 5px;
                padding: 5px;
                background-color: white;
                border: 1px solid #ddd;
                height: 34px;
            }

            #divControls > input {
                background-color: #888;
                color: white;
                height: 34px;
            }

            #divControls > * {
                display: inline-block;
            }

            input[type=button], input[type=submit], button, a.button {
                background-color: #fafafa;
                border: 1px solid #ddd;
                padding: 9px;
                border-radius: 3px;
                display: inline-block;
                position: relative;
                color: #999;
                cursor: pointer;
                box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05);
            }

            input, select {
                border: 1px solid #ccc;
                padding: 4px;
                outline: none;
                margin: 0px;
            }

            input[type="button"], input[type="submit"], input[type="reset"], input[type="file"]::-webkit-file-upload-button, button {
                padding: 1px 6px;
            }

            input[type="button"], input[type="submit"], input[type="reset"], input[type="file"]::-webkit-file-upload-button, button {
                align-items: flex-start;
                text-align: center;
                cursor: default;
                color: buttontext;
                padding: 2px 6px 3px;
                border: 2px outset buttonface;
                border-image-source: initial;
                border-image-slice: initial;
                border-image-width: initial;
                border-image-outset: initial;
                border-image-repeat: initial;
                background-color: buttonface;
                box-sizing: border-box;
            }

            input[type="button"], input[type="submit"], input[type="reset"] {
                -webkit-appearance: push-button;
                -webkit-user-select: none;
                white-space: pre;
            }

            input, input[type="password"], input[type="search"] {
                -webkit-appearance: textfield;
                padding: 1px;
                background-color: white;
                border: 2px inset;
                border-image-source: initial;
                border-image-slice: initial;
                border-image-width: initial;
                border-image-outset: initial;
                border-image-repeat: initial;
                -webkit-rtl-ordering: logical;
                -webkit-user-select: text;
                cursor: auto;
            }

            input, textarea, keygen, select, button {
                margin: 0em;
                font: normal normal normal 13.3333330154419px/normal Arial;
                text-rendering: auto;
                color: initial;
                letter-spacing: normal;
                word-spacing: normal;
                text-transform: none;
                text-indent: 0px;
                text-shadow: none;
                display: inline-block;
                text-align: start;
            }

            input, textarea, keygen, select, button, meter, progress {
                -webkit-writing-mode: horizontal-tb;
            }

            #divControls #divDate {
                color: #555;
            }

            #divControls #divDate #inCalendar {
                font-size: 20px;
                width: 130px;
                padding: 5px 10px 4px 10px;
                text-align: center;
                margin: 0px 10px;
                color: white;
                background-color: #777;
            }

            #divControls #divDate #divDay {
                margin: 5px 10px 5px 0px;
                font-size: 20px;
                width: 120px;
                text-align: center;
            }

            #divControls select {
                height: 30px;
                margin: 2px 5px;
            }

            input, select {
                border: 1px solid #ccc;
                padding: 4px;
                outline: none;
                margin: 0px;
            }

            select[size="0"], select[size="1"] {
                border-radius: 0px;
                border-color: rgb(169, 169, 169);
            }

            select:not(:-internal-list-box) {
                overflow: visible;
            }

            select {
                -webkit-appearance: menulist;
                box-sizing: border-box;
                align-items: center;
                border: 1px solid;
                border-image-source: initial;
                border-image-slice: initial;
                border-image-width: initial;
                border-image-outset: initial;
                border-image-repeat: initial;
                white-space: pre;
                -webkit-rtl-ordering: logical;
                color: black;
                background-color: white;
                cursor: default;
            }

            keygen, select {
                border-radius: 5px;
            }

            input, textarea, keygen, select, button {
                margin: 0em;
                font: normal normal normal 13.3333330154419px/normal Arial;
                text-rendering: auto;
                color: initial;
                letter-spacing: normal;
                word-spacing: normal;
                text-transform: none;
                text-indent: 0px;
                text-shadow: none;
                display: inline-block;
                text-align: start;
            }

            #divPageHeader .noLogged {
                color: #bbb;
                padding: 14px;
                font-size: 18px;
                display: inline-block;
                float: right;
            }

            #divPageHeader .noLogged a {
                color: white;
            }

        </style>

        <style>
            #divFooter {
                min-height: 200px;
                background-color: #333;
                border-top: 6px dashed #E9EBEC;
                //margin-top: 50px;
            }

            #timeTables{
                visibility: hidden;
                clear: both;
                // margin-top: 100px;
                background: radial-gradient(#00FF00 , #e7e7e7,#e7e7e7);
                padding-top: 10px;
                //border: 1px #000 solid;
            }

            #timeTables  td {
                border: 1px black;
                border-style: double;
                border-radius: 10px 5px; 
                background: #00FF00;
                text-align: center;
                padding: 2px;
            }

            #canvas1 {
                float: left;
            }

            #contentInfo {
                border: 1px black;
                border-style: double;
                float: left;
                margin-left: 15px;
                padding: 15px;
                max-width: 250px;
                //min-width: 200px;

                //border-radius: 10px 5px; 
            }

            td > a > div{
                color: #000;
            }






        </style>

        <style>

            //.main {min-width:600px;}
            .main.fullscreen {margin-left:0px!important}


        </style>

        <title>Gestión de espacio - Administración</title>


    </head>
    <body>
        <div id="divPageHeader" class="totalWidth">
            <div id="divPageHeaderInside">
                <a id="divHeaderLogo" class="left" href="/">
                    <div class="img"></div>
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="noLogged">Hola <?php echo $_SESSION['user_name'] ?>, <a href="#" onclick="logout();">Cerrar sesión</a></div>
                <?php else: ?>
                    <div class="noLogged">¿Administrador?, <a href="login.php">Iniciar sesión</a></div>
                <?php endif; ?>
            </div>

        </div>


        <div id="divPageMain" class="mainSC">

            <div id="divPageMenuSC">
                <div class="title">
                    <a href="/">Universidad de Jaén</a>
                    <div>Jaén (Jaén)</div>
                </div>
                <div id="panelHolder"></div>
            </div>

            <div class="main">
                <div id="divControls">
                    <input type="button" value="Hoy" id="btToday" class="left">
                    <input type="button" value="<<" id="btPrev2" class="left">
                    <input type="button" value="<" id="btPrev" class="left">
                    <div id="divDate" class="left">
                        <input id="inCalendar" class="left" type="text" readonly="readonly">
                        <div class="left" id="divDay">Miércoles</div>
                    </div>
                    <input type="button" value=">" id="btNext" class="left">
                    <input type="button" value=">>" id="btNext2" class="left">
                    <select id="selGroups" onchange="refreshData()">
                        <option value="08:00">08:00</option>
                        <option value="08:30">08:30</option>
                        <option value="09:00">09:00</option>
                        <option value="09:30">09:30</option>
                        <option value="10:00">10:00</option>
                        <option value="10:30">10:30</option>
                        <option value="11:00">11:00</option>
                        <option value="11:30">11:30</option>
                        <option value="12:00">12:00</option>
                        <option value="12:30">12:30</option>
                        <option value="13:00">13:00</option>
                        <option value="13:30">13:30</option>
                        <option value="14:00">14:00</option>
                        <option value="14:30">14:30</option>
                        <option value="15:00">15:00</option>
                        <option value="15:30">15:30</option>
                        <option value="16:00">16:00</option>
                        <option value="16:30">16:30</option>
                        <option value="17:00">17:00</option>
                        <option value="17:30">17:30</option>
                        <option value="18:00">18:00</option>
                        <option value="18:30">18:30</option>
                        <option value="19:00">19:00</option>
                        <option value="19:30">19:30</option>
                        <option value="20:00">20:00</option>
                        <option value="20:30">20:30</option>
                        <option value="21:00">21:00</option>
                        <option value="21:30">21:30</option>
                        <option value="22:00">22:00</option>
                    </select>

                </div>

                <!--<p>Date: <input type="text" id="inCalendar" readonly="readonly"></p><select id="selTimeend"><option value="07:00">07:00</option><option value="07:30">07:30</option><option value="08:00">08:00</option><option value="08:30">08:30</option><option value="09:00">09:00</option><option value="09:30">09:30</option><option value="10:00">10:00</option><option value="10:30">10:30</option><option value="11:00">11:00</option><option value="11:30">11:30</option><option value="12:00">12:00</option><option value="12:30">12:30</option><option value="13:00">13:00</option><option value="13:30">13:30</option><option value="14:00">14:00</option><option value="14:30">14:30</option><option value="15:00">15:00</option><option value="15:30">15:30</option><option value="16:00">16:00</option><option value="16:30">16:30</option><option value="17:00">17:00</option><option value="17:30">17:30</option><option value="18:00">18:00</option><option value="18:30">18:30</option><option value="19:00">19:00</option><option value="19:30">19:30</option><option value="20:00">20:00</option><option value="20:30">20:30</option><option value="21:00">21:00</option><option value="21:30">21:30</option><option value="22:00">22:00</option><option value="22:30">22:30</option><option value="23:00">23:00</option><option value="23:30">23:30</option><option value="00:00">00:00</option><option value="00:30">00:30</option><option value="01:00">01:00</option><option value="01:30">01:30</option><option value="02:00">02:00</option><option value="02:30">02:30</option></select>
                -->
                <div>
                    <div class="canvas" id="canvas1"></div>
                    <div id="contentInfo">
                        <div id="info"></div>
                        <input style="clear: both; margin: 15px;" type="button" value="Reservar">

                    </div>

                </div>

                <div id="timeTables">
                    <table style="width:100%">
                        <tr>
                            <td id="08:00"><a><div style="height:100%;width:100%">08:00</div></a></td>
                            <td id="10:00"><a><div style="height:100%;width:100%">10:00</div></a></td>
                            <td id="12:00"><a><div style="height:100%;width:100%">12:00</div></a></td>
                            <td id="14:00"><a><div style="height:100%;width:100%">14:00</div></a></td>
                            <td id="16:00"><a><div style="height:100%;width:100%">16:00</div></a></td>
                            <td id="18:00"><a><div style="height:100%;width:100%">18:00</div></a></td>
                            <td id="20:00"><a><div style="height:100%;width:100%">20:00</div></a></td>
                            <td id="22:00"><a><div style="height:100%;width:100%">22:00</div></a></td>
                        </tr>
                        <tr>
                            <td id="08:30"><a><div style="height:100%;width:100%">08:30</div></a></td>
                            <td id="10:30"><a><div style="height:100%;width:100%">10:30</div></a></td>
                            <td id="12:30"><a><div style="height:100%;width:100%">12:30</div></a></td>
                            <td id="14:30"><a><div style="height:100%;width:100%">14:30</div></a></td>
                            <td id="16:30"><a><div style="height:100%;width:100%">16:30</div></a></td>
                            <td id="18:30"><a><div style="height:100%;width:100%">18:30</div></a></td>
                            <td id="20:30"><a><div style="height:100%;width:100%">20:30</div></a></td>
                        </tr>
                        <tr>
                            <td id="09:00"><a><div style="height:100%;width:100%">09:00</div></a></td>
                            <td id="11:00"><a><div style="height:100%;width:100%">11:00</div></a></td>
                            <td id="13:00"><a><div style="height:100%;width:100%">13:00</div></a></td>
                            <td id="15:00"><a><div style="height:100%;width:100%">15:00</div></a></td>
                            <td id="17:00"><a><div style="height:100%;width:100%">17:00</div></a></td>
                            <td id="19:00"><a><div style="height:100%;width:100%">19:00</div></a></td>
                            <td id="21:00"><a><div style="height:100%;width:100%">21:00</div></a></td>
                        </tr>
                        <tr>
                            <td id="09:30"><a><div style="height:100%;width:100%">09:30</div></a></td>
                            <td id="11:30"><a><div style="height:100%;width:100%">11:30</div></a></td>
                            <td id="13:30"><a><div style="height:100%;width:100%">13:30</div></a></td>
                            <td id="15:30"><a><div style="height:100%;width:100%">15:30</div></a></td>
                            <td id="17:30"><a><div style="height:100%;width:100%">17:30</div></a></td>
                            <td id="19:30"><a><div style="height:100%;width:100%">19:30</div></a></td>
                            <td id="21:30"><a><div style="height:100%;width:100%">21:30</div></a></td>
                        </tr>
                    </table>
                </div>

            </div>

            <div id="divFooter">

            </div>

        </div>



        <script>

            function logout() {
                console.log("Hi!");
                $.get("logout.php");
                parent.window.location.reload();
                return false;
            }

            var id = 0;
            function rfc3986EncodeURIComponent(str) {
                return encodeURIComponent(str).replace(/[!'()*]/g, escape);
            }

            /*Cargar los datos del archivo de texto*/
            {
                $.get("./Base.txt", function (v) {
                    var data = JSON.parse(v);
                    var lastPlant;
                    //console.log(data);
                    for (var i = 0; i < data.length; i++) {
                        for (var j = 0; j < data[i].length; j++)
                            if (data[i][j].id === "image") {
                                $('#panelHolder').append('<div id="imgHolder"><div>' + data[i][j].name + '</div><img onclick="changeImg(this,' + id + ',\'' + rfc3986EncodeURIComponent(data[i][j].name) + '\')" src="' + data[i][j].url + '" /></div>');
                                lastPlant = data[i][j].name;
                            } else if (data[i][j].id === "object") {
                                var obj = {form: paper.path(data[i][j].formPath).attr(JSON.parse(data[i][j].formAttr)),
                                    text: paper.text(data[i][j].textX, data[i][j].textY, data[i][j].textPath).attr(JSON.parse(data[i][j].textAttr)),
                                    //state: paper.rect(data[i][j].stateX, data[i][j].stateY, 15, 15),
                                    state: paper.circle(data[i][j].stateX + 7.5, data[i][j].stateY + 7.5, 7.5),
                                    plant: lastPlant,
                                    node: data[i][j].node
                                };
                                glowable(obj);
                                objects.push(obj);
                            }
                        toHide(objects);
                        objectsArray.push({
                            id: id,
                            objects: objects
                        });
                        id++;
                        objects = [];
                    }

                });
            }

            /*Gestor de fechas*/

            {
                var image;
                var currentId = -1;
                var currentImageName;
                var objectsArray = [];
                var objects = [];
                var canvas = document.getElementById("canvas1");
                var c = {
                    width: canvas.offsetWidth,
                    height: canvas.offsetHeight
                };
                //console.log(c);
                var paper = Raphael(canvas, c.width, c.height);
                var weekday = new Array(7);
                weekday[0] = "Lunes";
                weekday[1] = "Martes";
                weekday[2] = "Miércoles";
                weekday[3] = "Jueves";
                weekday[4] = "Viernes";
                weekday[5] = "Sábado";
                weekday[6] = "Domingo";
                var actualDate;
                var minDate;
                var maxDate;
                $('#btPrev2').click(function () {
                    var temp;
                    temp = $("#inCalendar").datepicker('getDate');
                    temp.setDate(temp.getDate() - 7);
                    if (temp.valueOf() > minDate.valueOf()) {
                        loadFields(temp);
                    }
                });
                $('#btPrev').click(function () {
                    var temp;
                    temp = $("#inCalendar").datepicker('getDate');
                    temp.setDate(temp.getDate() - 1);
                    if (temp.valueOf() > minDate.valueOf()) {
                        loadFields(temp);
                    }
                });
                $('#btToday').click(function () {
                    $("#inCalendar").datepicker('setDate', new Date());
                    $('#divDay').html(weekday[($("#inCalendar").datepicker('getDate').getUTCDay()) ]);
                    refreshData();
                });
                $('#btNext').click(function () {
                    //console.log("HijoPuta");
                    var temp;
                    temp = $("#inCalendar").datepicker('getDate');
                    temp.setDate(temp.getDate() + 1);
                    console.log(temp.valueOf() + " " + maxDate.valueOf());
                    if (temp.valueOf() < maxDate.valueOf()) {
                        loadFields(temp);
                    }
                });
                $('#btNext2').click(function () {
                    var temp;
                    temp = $("#inCalendar").datepicker('getDate');
                    temp.setDate(temp.getDate() + 7);
                    if (temp.valueOf() < maxDate.valueOf()) {
                        loadFields(temp);
                    }
                });
                function loadFields(date) {
                    $("#inCalendar").datepicker('setDate', date);
                    $('#divDay').html(weekday[date.getUTCDay()]);
                    refreshData();
                    //console.log(weekday[date.getUTCDay()]);
                    //console.log(weekday[(actualDate.getUTCDay()-1)%7]);
                    //console.log(actualDate);
                }

                $(function () {
                    $("#inCalendar").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: "dd-mm-yy",
                        minDate: 0,
                        maxDate: "+21D",
                        onSelect: function (dateText, inst) {
                            //var dateAsString = dateText; //the first parameter of this function
                            //actualDate = $(this).datepicker('getDate'); //the getDate method
                            //console.log(actualDate);
                            $('#divDay').html(weekday[($(this).datepicker('getDate').getUTCDay())]);
                            refreshData();
                            //console.log(weekday[actualDate.getUTCDay()]);
                            //loadFields();
                        }
                    });
                    $("#inCalendar").datepicker('setDate', new Date());
                    minDate = new Date();
                    maxDate = new Date();
                    minDate.setDate(minDate.getDate() - 1);
                    maxDate.setDate(maxDate.getDate() + 21);
                    //console.log("Max: " + maxDate.valueOf());
                    /*var hi = $("#inCalendar").datepicker('getDate') + 22;
                     var test = new Date();
                     test.setDate(test.getDate() + 22);
                     console.log(test);*/
                    $('#divDay').html(weekday[($("#inCalendar").datepicker('getDate').getUTCDay()) ]);
                    //loadFields();
                });
            }

            /*Funciones de controles de elementos*/
            {
                function getOriginalWidthOfImg(img_element) {
                    //console.log(img_element);
                    var t = new Image();
                    t.src = (img_element.getAttribute ? img_element.getAttribute("src") : false) || img_element.src;
                    return t.naturalWidth;
                }

                function getOriginalHeightOfImg(img_element) {
                    var t = new Image();
                    t.src = (img_element.getAttribute ? img_element.getAttribute("src") : false) || img_element.src;
                    return t.naturalHeight;
                }

                function toFront(array) {
                    for (var i = 0; i < array.length; i++) {
                        array[i].form.toFront();
                        array[i].text.toFront();
                        array[i].state.toFront();
                    }
                }

                function toShow(array) {
                    for (var i = 0; i < array.length; i++) {
                        array[i].form.show();
                        array[i].text.show();
                        array[i].state.show();
                    }
                }

                function hideExceptId(id) {
                    for (var i = 0; i < objectsArray.length; i++) {
                        if (objectsArray[i].id !== id) {
                            toHide(objectsArray[i].objects);
                        }
                    }
                }

                function toHide(array) {
                    for (var i = 0; i < array.length; i++) {
                        array[i].form.hide();
                        array[i].text.hide();
                        array[i].state.hide();
                    }
                }
            }




            function refreshData() {
                var e = document.getElementById("selGroups");
                var d = $("#inCalendar").datepicker('getDate');
                var date = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate() + " " + e.options[e.selectedIndex].value + ":00";
                console.log("Date: " + date);
                console.log(currentImageName);
                var save = currentImageName;
                if (lastObj)
                    clickOnSite(lastObj);
                //document.getElementById("timeTables").style.visibility = 'hidden';
                $.ajax({
                    url: 'mysqlphp.php',
                    type: 'POST',
                    data: {var1: date, var2: decodeURIComponent(currentImageName)},
                    success: function (data) {
                        console.log(data);
                        var dataParsed = JSON.parse(data);
                        for (var j = 0; j < objects.length; j++) {
                            objects[j].state.attr({'fill': '#00FF00'});
                        }

                        console.log(dataParsed);
                        for (var i = 0; i < dataParsed.length; i++) {
                            for (var j = 0; j < objects.length; j++) {
                                if (save === currentImageName)
                                    if (dataParsed[i].node === String(objects[j].node)) {
                                        console.log(dataParsed[i].state);
                                        switch (dataParsed[i].state) {
                                            case 'Reserved':
                                                objects[j].state.attr({'fill': '#FF0000'});
                                                break;
                                            case 'Revising':
                                                objects[j].state.attr({'fill': '#FFD700'});
                                                break;
                                            case 'Bloqued':
                                                objects[j].state.attr({'fill': '#696969'});
                                                break;
                                        }

                                    }
                            }
                        }



                        /*for (var i = 0; i < objects.length; i++) {
                         //console.log("Objeto: " + objects[i].text.attr('text') + " de: " + objects[i].plant);
                         }*/
                        console.log('success');
                    }
                });
                for (var i = 0; i < objects.length; i++) {
                    //console.log("Objeto: " + objects[i].text.attr('text') + " de: " + objects[i].plant);
                }




            }

            function changeImg(element, id, name) {
                //console.log("Entrance");
                if (currentId === id) {
                    refreshData();
                    return false;
                }

                if (image)
                    image.remove();
                currentImageName = name;
                var width = getOriginalWidthOfImg(element);
                var height = getOriginalHeightOfImg(element);
                canvas.style.width = width;
                canvas.style.height = height;
                console.log(canvas);
                document.getElementById("timeTables").style.width = width;
                document.getElementById("timeTables").style.visibility = 'hidden';
                //$("#timeTables").style.width = width;
                paper.setSize(width, height);
                c = {
                    width: canvas.offsetWidth,
                    height: canvas.offsetHeight,
                    center: {x: canvas.offsetWidth / 2, y: canvas.offsetHeight / 2}
                };

                document.getElementById('contentInfo').style.width = window.innerWidth - c.width - 365;

                console.log(window.innerWidth + " " + c.width + " " + 250);
                //console.log(c);
                image = paper.image(element.src, 0, 0, width, height);
                /*for (var i = 0; i < objectsArray.length; i++) {
                 if (objectsArray[i].id === currentId) {
                 objectsArray[i].objects = objects;
                 }
                 }*/

                for (var i = 0; i < objectsArray.length; i++) {
                    if (objectsArray[i].id === id) {
                        objects = objectsArray[i].objects;
                    }
                }

                //console.log("Objects length: " + objects.length);

                refreshData();
                toFront(objects);
                toShow(objects);
                hideExceptId(id);
                currentId = id;

            }

            function addZero(i) {
                if (i < 10) {
                    i = "0" + i;
                }
                return i;
            }

            function clickOnSite(obj) {
                var d = $("#inCalendar").datepicker('getDate');
                var date = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate() + " 00:00:00";
                document.getElementById("timeTables").style.visibility = 'visible';
                lastObj = obj;
                $.ajax({
                    url: 'mysqlphpNode.php',
                    type: 'POST',
                    data: {var1: date, var2: decodeURIComponent(currentImageName), var3: obj.node},
                    success: function (data) {
                        console.log(decodeURIComponent(currentImageName) + " " + obj.node);
                        //console.log(data);
                        var dataParsed = JSON.parse(data);
                        console.log(dataParsed);
                        var nodeList = document.getElementsByTagName("td");
                        for (var j = 0; j < nodeList.length; j++) {
                            nodeList[j].style.background = "#00FF00";
                            nodeList[j].style.cursor = "pointer";
                            //console.log(nodeList[j].childNodes[0]);
                            var n = nodeList[j].id.split(':');
                            d.setHours(n[0]);
                            d.setMinutes(n[1]);
                            var date2 = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate() + " " + addZero(d.getHours()) + ":" + addZero(d.getMinutes()) + ":00";
                            nodeList[j].childNodes[0].href = "reserve.php?node=" + obj.node + "&site=" + currentImageName + "&date=" + date2;
                            //console.log("reserve.php?hour=" + nodeList[j].id);
                            //nodeList[j].href = "reserve.php?hour=" + nodeList[j].id;
                            /*nodeList[j].onclick = function () {
                             console.log(this.id + " " + obj.node + " " + decodeURIComponent(currentImageName) + " " + date);
                             nodeList[j].href = "reserve.php";
                             //window.location.href('reserve.php');
                             };*/
                            //nodeList[j].attr("href", "reserve.php?hour=" + nodeList[j].attr("id"));
                        }
                        //document.getElementById("14:00").style.background = "#FF0000";


                        //console.log("Aqui pasa algo");
                        for (var i = 0; i < dataParsed.length; i++) {
                            //console.log(dataParsed);
                            //console.log("Moch");
                            console.log("Es el turno de: " + i);
                            var start = new Date(dataParsed[i].startdate * 1000);
                            var endD = new Date(dataParsed[i].enddate * 1000);

                            //console.log((start.getMinutes() <= endD.getMinutes()));

                            while ((start.getHours() < endD.getHours()) || ((start.getHours() === endD.getHours()) && (start.getMinutes() <= endD.getMinutes()))) {
                                //console.log(addZero(start.getHours()) + ":" + addZero(start.getMinutes()));
                                // document.getElementById("15:00").style.background = "#FF0000";
                                switch (dataParsed[i].state) {
                                    //document.getElementById(addZero(start.getHours()) + ":" + addZero(start.getMinutes())).style.background = "#FF0000";
                                    case 'Reserved':
                                        document.getElementById(addZero(start.getHours()) + ":" + addZero(start.getMinutes())).style.background = "#FF0000";
                                        break;
                                    case 'Revising':
                                        document.getElementById(addZero(start.getHours()) + ":" + addZero(start.getMinutes())).style.background = "#FFD700";
                                        break;
                                    case 'Bloqued':
                                        document.getElementById(addZero(start.getHours()) + ":" + addZero(start.getMinutes())).style.background = "#696969";
                                        break;

                                }
                                start = new Date(start.getTime() + 30 * 60000);
                            }
                        }

                        /*console.log("Meh");
                        console.log(dataParsed[(dataParsed.length) - 1].data);

                        var data = JSON.parse(dataParsed[(dataParsed.length) - 1].data);
                        console.log("Mih");

                        //console.log("Data:");
                        //console.log(data);
                        var textHTML = "";
                        textHTML += obj.text.attr("text") + "<br/><br/>";

                        for (var propertyName in data.visible) {
                            textHTML += (propertyName + ": " + data.visible[propertyName] + "<br/>");
                        }*/

                        /*for (var i = 0; i < data.warning.length; i++)
                         textHTML += warningMessage(data.warning[i]);*/


                        /*document.getElementById("info").innerHTML = textHTML;
                        console.log("Moh");*/


                        /*console.log(new Date(dataParsed[i].startdate * 1000).getDate());
                         console.log(new Date(dataParsed[i].enddate * 1000).getDate());*/
                        //;

                    }
                }

                );
            }

            var lastObj;

            var glowable = function (obj) {
                obj.form.click(function () {
                    clickOnSite(obj);
                    console.log(obj.text.attr('text') + ", nodo: " + obj.node + ", planta: " + obj.plant);
                }
                );
                obj.text.click(function () {
                    clickOnSite(obj);
                }
                );
                obj.form.hover(
                        function () {
                            obj.form.attr({'opacity': 0.8});
                            obj.g = obj.form.glow({
                                color: "#F00",
                                width: 10
                            });
                        },
                        function () {
                            obj.form.attr({'opacity': 1});
                            obj.g.remove();
                        });
                obj.text.hover(
                        function () {
                            obj.form.attr({'opacity': 0.8});
                            obj.g = obj.form.glow({
                                color: "#F00",
                                width: 10
                            });
                        },
                        function () {
                            obj.form.attr({'opacity': 1});
                            obj.g.remove();
                        });
            };




        </script>


    </body>
</html>

