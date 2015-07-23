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

//$usuario = $_GET['node'];
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

            #infoHolder {
                width:205px;
                height:600px;
                position:absolute;
                background-color: #99b4ff;
                border-width: 1px;
                overflow-x:hidden;
                overflow-y: hidden;
                padding: 15px;

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

            #infoSite {
                font-size: 18px;
                color: #001b66;
                text-overflow: ellipsis;
                //text-shadow: 0px 0px 15px #344900;
            }

            p {
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 10pt;
                color: #555555;
                text-align: justify; 
            }

            input[type="checkbox"] {
                -webkit-appearance: checkbox;
                box-sizing: border-box;
            }




        </style>

        <style>

            //.main {min-width:600px;}
            .main.fullscreen {margin-left:0px!important}


        </style>

        <title>Gestión de espacio - Administración</title>


    </head>

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

    <body>
        <div id="divPageMain" class="mainSC">

            <div id="divPageMenuSC">
                <div class="title">
                    <a href="/">Universidad de Jaén</a>
                    <div>Jaén (Jaén)</div>
                </div>
                <div id="infoHolder"><div>Está reservando para: </div><div id="infoSite">info</div></div>
            </div>

            <div class="main">
                <p align="center" style="text-align: left"><b><font size="3">DATOS DEL SOLICITANTE:</font></b></p>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  name="FrontPage_Form1" language="JavaScript">
                    <input type="hidden" id="startdate" name="startdate">
                    <input type="hidden" id="enddate" name="enddate">
                    <input type="hidden" id="site" name="site">


                    <p align="center" style="text-align: left"><b>Nombre y apellidos</b>:
                        <br/><input type="text" name="solicitante" id="solicitante" size="57" style="text-transform: uppercase"></p>
                    <p align="center" style="text-align: left"><b>Edificio:</b>&nbsp;<!--webbot bot="Validation" b-value-required="TRUE" b-disallow-first-item="TRUE" --><select size="1" name="edificio" id="edificio">
                            <option selected>-</option>
                            <option>A1</option>
                            <option>A2</option>
                            <option>A3</option>
                            <option>A4</option>
                            <option>B1</option>
                            <option>B2</option>
                            <option>B3</option>
                            <option>B4</option>
                            <option>B5</option>
                            <option>C1</option>
                            <option>C2</option>
                            <option>C3</option>
                            <option>C4</option>
                            <option>C5</option>
                            <option>C6</option>
                            <option>D1</option>
                            <option>D2</option>
                            <option>D3</option>
                            <option>Magisterio</option>
                            <option>A</option>
                            <option>B</option>
                        </select>
                        <b>&nbsp;&nbsp; Dependencia:</b>&nbsp;<!--webbot bot="Validation" b-value-required="TRUE" i-minimum-length="1" --><input type="text" name="dependencia" id="dependencia" size="7">&nbsp;&nbsp;&nbsp;&nbsp;
                        <b>&nbsp;&nbsp; Sector: </b>
                        <!--webbot bot="Validation" b-value-required="TRUE" b-disallow-first-item="TRUE" --><select size="1" name="sector" id="sector">
                            <option selected>-</option>
                            <option>PDI</option>
                            <option>PAS</option></select></p>
                    <p align="center" style="text-align: left"><b>Servicio:
                            <select size="1" name="Servicio/Unidad">
                                <option selected>Indique Órgano de Gobierno/Servicio</option>
                                <option>Consejo Social</option>
                                <option>Vicerrectorado de Enseñanzas de Grado, Postgrado y Formación Permanente</option>
                                <option>Vicerrectorado de Investigación</option>
                                <option>Vicerrectorado de Proyección de la Cultura, Deportes y Responsabilidad Social</option>
                                <option>Vicerrectorado de Profesorado y Ordenación Académica</option>
                                <option>Vicerrectorado de Estudiantes</option>
                                <option>Vicerrectorado de Tecnologías de la Información y la Comunicación e Infraestructuras</option>
                                <option>Vicerrectorado de Internacionalización</option>
                                <option>Vicerrectorado de Relaciones con la Sociedad e Inserción Laboral</option>
                                <option>Gerencia</option>
                                <option>Secretaria General</option>
                                <option>Gabinete del Rector</option>
                                <option>Servicio de Asuntos Económicos</option>
                                <option>Servicio de Gestión Académica</option>
                                <option>Servicio de Atención y Ayudas al Estudiante</option>
                                <option>Servicio de Investigación</option>
                                <option>Unidad de Servicios Técnicos de Investigación</option>
                                <option>Negociados Apoyo a Departamentos y a Institutos y Centros de Investigación</option>
                                <option>Técnicos Laboratorios de Departamentos y de Institutos y Centros de   Investigaci</option>
                                <option>Servicio de Personal y Organización Docente</option>
                                <option>Unidad de Conserjerías</option>
                                <option>Servicio de Control Interno</option>
                                <option>Servicio de Contabilidad y Presupuestos</option>
                                <option>Servicio de Contratación y Patrimonio
                                </option>
                                <option>Servicio de Obras, Mantenimiento y Vigilancia de las Instalaciones</option>
                                <option>Servicio de Planificación y Evaluación
                                </option>
                                <option>Servicio de Información y Asuntos Generales</option>
                                <option>Unidad de Actividades Culturales
                                </option>
                                <option>Sección de Publicaciones</option>
                                <option>Servicio de Informática</option>
                                <option>Centro de Instrumentación Científico-Técnica</option>
                                <option>Servicio de Biblioteca</option>
                                <option>Servicio de Archivo General</option>
                                <option>Servicio de Deportes</option>
                                <option>Prevención y Riesgos Laborales</option>
                                <option>Consejo de Estudiantes</option>
                                <option>Sección Sindical CCOO</option>
                                <option>Seccón Sindical UGT</option>
                                <option>Sección Sindical CSIF</option>
                                <option>Otros (Indique en observaciones detalle)
                                </option>
                            </select>&nbsp;</b></p>
                    <p align="center" style="text-align: left"><b>Centro/Departamento:</b>&nbsp;<select size="1" name="Departamento">
                            <option selected>Indique su Centro/Departamento</option>
                            <option>Facultad de Ciencias Experimentales</option>
                            <option>Facultad de Ciencas Sociales y Juridicas</option>
                            <option>Facultad de Humanidades y Ciencias de la Educación
                            </option>
                            <option>Facultad Ciencias de la Salud</option>
                            <option>Facultad Trabajo Social</option>
                            <option>Escuela Politécnica Superior</option>
                            <option>Escuela Politécnica Superior (Linares)</option>
                            <option>Centro Andaluz de Arqueología Ibérica</option>
                            <option>Centros de Estudios Avanzados en lenguas Modernas
                            </option>
                            <option>Antropología, Geografía e Historia</option>
                            <option>Biología Animal, Vegetal y Ecología</option>
                            <option>Biología Experimental</option>
                            <option>Ciencias de la Salud</option>
                            <option>Derecho Civil, Derecho Financiero y Tributario
                            </option>
                            <option>Derecho Penal, Filosofía del Derecho, Filosofía Moral y Filosofía
                            </option>
                            <option>Derecho Público</option>
                            <option>Derecho Público y Común Europeo</option>
                            <option>Derecho Público y Derecho Privado Especial</option>
                            <option>Didáctica de la Expresión Musical, Plástica y Corporal
                            </option>
                            <option>Didáctica de las Ciencias</option>
                            <option>Economía</option>
                            <option>Economía Financiera y Contabilidad</option>
                            <option>Estadística e Investigación Operativa</option>
                            <option>Filología Española</option>
                            <option>Filología Inglesa</option>
                            <option>Enfermería</option>
                            <option>Física</option>
                            <option>Geología</option>
                            <option>Informática</option>
                            <option>Ingeniería Cartográfica, Geodésica y Fotogrametría
                            </option>
                            <option>Ingeniería Eléctrica</option>
                            <option>Ingeniería Electrónica y Automática</option>
                            <option>Ingeniería de Telecomunicación</option>
                            <option>Ingeniería Gráfica, Diseño y Proyectos</option>
                            <option>Ingeniería Mecánica y Minera</option>
                            <option>Ingeniería Química, Ambiental y de los Materiales
                            </option>
                            <option>Lenguas y Culturas Mediterráneas</option>
                            <option>Matemáticas</option>
                            <option>Org. de Empresas Márketing y Sociología</option>
                            <option>Patrimonio Histórico</option>
                            <option>Pedagogía</option>
                            <option>Psicología</option>
                            <option>Química Física y Analítica</option>
                            <option>Química Inorgánica y Orgánica</option></select></p>
                    <p><b>Dirección e-mail:</b>&nbsp;<!--webbot bot="Validation" b-value-required="TRUE" i-minimum-length="13" --><input type="text" name="email" id="email" size="25" value="@ujaen.es"><br>
                        <SPAN style="color:red; font-size:9px">Por favor, para realizar la solicitud indique su 
                            <u>correo electrónico personal de la Universidad de Jaén</u>.</span>
                    </p>
                    <p align="center" style="text-align: left"><b>Teléfono: </b>
                        <!--webbot bot="Validation" s-data-type="Number" s-number-separators=".," b-value-required="TRUE" i-minimum-length="4" --><input type="text" onkeypress="return soloNumeros(event)" name="telefono" id="telefono" size="9">
                        <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Móvil:</b>&nbsp;<!--webbot bot="Validation" s-data-type="Number" s-number-separators=".," --><input type="text" onkeypress="return soloNumeros(event)" name="movil" id="movil" size="9"></p>
                    <p align="center"><font size="3"><b>ESPACIOS Y SERVICIOS SOLICITADOS:</b></font></p>
                    <p align="center" style="text-align: left">
                        <b>Espacio solicitado: 
                            <font size="3">
                            <input id="espacio2" type="text" readonly></font>
                            <input id="espacio" name="espacio" type="hidden" value=""></font>
                        </b>
                    </p>
                    <p align="center" style="text-align: left"><b>Fecha (s) de celebración:&nbsp;
                            <br/>
                            <input type="text" name="fecha" id="fecha" size="6" disabled><input type="text" name="hora_inicio" id="hora_inicio" size="1" disabled></b></p>
                    <!--<p align="center" style="text-align: left"><b>Horario (s) de Inicio Reserva:&nbsp;
                            <br/>
                            <input type="text" name="hora_inicio" id="hora_inicio" size="88"></b></p>-->
                    <p align="center" style="text-align: left"><b>Horario (s) de Finalización Reserva:&nbsp;
                            <br/>
                            <input type="text" name="fecha2" id="fecha2" size="6" disabled>                    
                            <select name="hora_finalizacion" id="hora_finalizacion">
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
                            </select><!--<input type="text" name="hora_finalizacion" id="hora_finalizacion" size="1">--></b></p>

                    <p align="center" style="text-align: left"><b>Horario (s) de Inicio Acto:&nbsp;
                            <br/>
                            <select name="hora_acto" id="hora_acto" onChange="hora_acto_validar(this.selectedIndex)">
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
                            </select><!--<input type="text" name="hora_finalizacion" id="hora_finalizacion" size="1">--></b></p>

                    <p align="center" style="text-align: left">
                    <U><I><FONT size=2><B><A href="http://www10.ujaen.es/node/9104/download/SALAS%20DE%20JUNTAS1.pdf">Consulte aquí el aforo de las distintos espacios</A></B></FONT></I></U>
                    </p>
                    <p align="center" style="text-align: left"><b>Evento a celebrar
                            <br/><textarea rows="3" name="evento" id="evento" cols="106"></textarea></b></p>

                    <p align="center" style="text-align: left">
                    <DIV id="d_capacidad" style="text-align:center"></DIV>
                    <DIV id="d_normas"></DIV>
                    </p>
                    <p align="center" style="text-align: left"><b>
                            <DIV id="d_txt"><font size="2">Necesidades para la celebración del evento</font>:</DIV>
                            <DIV id="d_sin_necesidades"><input type="checkbox" name="sin_necesidades" onclick="Sin_Nece_Click()" value="ON">Sin necesidades</DIV>
                            <DIV id="d_num_personas_mesa_presidencial" style="display: none">Número de personas en la mesa presidencial&nbsp; 
                                &nbsp;<!--webbot bot="Validation" s-data-type="Number" s-number-separators=".," --><input type="text" onkeypress="return soloNumeros(event)" name="num_personas_mesa_presidencial" id="num_personas_mesa_presidencial" onchange="Desactiva_Sin_Nece()" size="2"></DIV>
                            <DIV id="d_videoconferencia"><input type="checkbox" name="videoconferencia" onclick="Desactiva_Sin_Nece()" value="ON"><span style=color:red;>Videoconferencia&nbsp;</span></b><span style=color:red;font-size:11px;> (Le recordamos que al seleccionar esta casilla se iniciará el proceso para la puesta en funcionamiento de este recurso por la Unidad de Medios Audiovisuales)</span></DIV>
                        <b>
                            <DIV id="d_traduccion_simultanea"><input type="checkbox" name="traduccion_simultanea" onclick="Desactiva_Sin_Nece()" value="ON"><span style=color:red;>Traducción Simultánea&nbsp;</span></b><span style=color:red;></span><span style=color:red;font-size:11px;> (Le recordamos que al seleccionar esta casilla se iniciará el proceso para la puesta en funcionamiento de este recurso por la Unidad de Medios Audiovisuales)</span></DIV>
                        <b>
                            <DIV id="d_canon"><input type="checkbox" name="canon" onclick="Desactiva_Sin_Nece()" value="ON">Cañón</DIV>
                            <DIV id="d_CPU"><input type="checkbox" name="CPU" onclick="Desactiva_Sin_Nece()" value="ON">CPU</DIV>
                            <DIV id="d_megafonia"><input type="checkbox" name="megafonia" onclick="Desactiva_Sin_Nece()" value="ON">Megafonía</DIV>
                            <DIV id="d_megafonia_debate" style="display: none"><input type="checkbox" name="megafonia_debate" onclick="Desactiva_Sin_Nece()" value="ON">Megafonía de debate</DIV>
                            <DIV id="d_audio_pc"><input type="checkbox" name="audio_pc" onclick="Desactiva_Sin_Nece()" value="ON">Audio PC</DIV>
                            <DIV id="d_cable_red"><input type="checkbox" name="cable_red" onclick="Desactiva_Sin_Nece()" value="ON">Cable de red</DIV>
                            <DIV id="d_cable_portatil"><input type="checkbox" name="cable_portatil" onclick="Desactiva_Sin_Nece()" value="ON">Cable para portátil</DIV>
                            <DIV id="d_altavoces"><input type="checkbox" name="altavoces" onclick="Desactiva_Sin_Nece()" value="ON">Altavoces</DIV>
                            <DIV id="d_micro_inalambrico"><input type="checkbox" name="micro_inalambrico" onclick="Desactiva_Sin_Nece()" value="ON">Micro inalámbrico</DIV>
                            <DIV id="d_atril"><input type="checkbox" name="atril" onclick="Desactiva_Sin_Nece()" value="ON">Atril</DIV>
                            <DIV id="d_tv"><input type="checkbox" name="tv" onclick="Desactiva_Sin_Nece()" value="ON">TV</DIV>
                            <DIV id="d_conexion_cuadro_electrico"><input type="checkbox" name="conexion_cuadro_electrico" onclick="Desactiva_Sin_Nece()" value="ON">Conexión cuadro eléctrico</DIV>
                            <DIV id="d_agua_caliente_camerinos"><input type="checkbox" name="agua_caliente_camerinos" onclick="Desactiva_Sin_Nece()" value="ON">Agua caliente camerinos</DIV>
                            <DIV id="d_botellas">Agua&nbsp; nº de botellas&nbsp;&nbsp;<!--webbot bot="Validation" s-data-type="Number" s-number-separators=".," --><input type="text" onkeypress="return soloNumeros(event)" name="botellas" id="botellas" onchange="Desactiva_Sin_Nece()" size="2"></DIV>
                        </b></p>
                    <p align="center" style="text-align: left"><b>Observaciones:</b><br/>
                        <textarea rows="3" name="observaciones" cols="106"></textarea></p>
                    <p style="text-align: center">
                        <input type="submit" value="Enviar" name="Enviar"><input type="reset" value="Restablecer" name="B2"></p>
                        <!--<input type="submit" value="Enviar" name="Enviar" onclick="return Valida()"><input type="reset" value="Restablecer" name="B2"></p>-->
                    </p>
                </form>

                <div id="test">


                </div>
            </div>


            <div id="divFooter">
                <table>
                    <?php
                    $nombre = $edificio = $dependencia = $sector = $servicio = $departamento = $email = $telefono = $movil = "";
                    $espacio = $startdate = $enddate = $eventdate = $event = $observaciones = $num_per_mesa_presi = $botellas = $site ="";
                    $error = "";

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {

                        if (empty($_POST["solicitante"])) {
                            $error = $error . ", El nombre es necesario";
                        } else {
                            $nombre = test_input($_POST["solicitante"]);
                            if (!preg_match("/^[a-zA-Z ]*$/", $nombre)) {
                                $error = $error . ", Solo se admiten letras y espacios en el nombre";
                            }
                            if (strlen($nombre) > 70) {
                                $error = $error . ", El nombre es demasiado largo";
                            }
                        }

                        $arrayEdificio = ["A1", "A2", "A3", "A4", "B1", "B2", "B3", "B4", "B5", "C1", "C2", "C3", "C4", "C5", "C6", "D1", "D2", "D3", "Magisterio", "A", "B"];
                        if ($_POST["edificio"] === "-") {
                            $error = $error . ", El campo edificio es obligatorio";
                        } else {
                            $edificio = test_input($_POST["edificio"]);
                            if (!in_array($edificio, $arrayEdificio)) {
                                $error = $error . ", El edificio seleccionado no existe";
                            }
                        }

                        if (empty($_POST["dependencia"])) {
                            $error = $error . ", El campo dependencia es obligatorio";
                        } else {
                            $dependencia = test_input($_POST["dependencia"]);
                            if (!preg_match("/[0-9][0-9][0-9]/", $dependencia)) {
                                $error = $error . ", La dependencia es incorrecta";
                            }
                        }

                        $arrayEdificio = ["A1", "A2", "A3", "A4", "B1", "B2", "B3", "B4", "B5", "C1", "C2", "C3", "C4", "C5", "C6", "D1", "D2", "D3", "Magisterio", "A", "B"];
                        if ($_POST["edificio"] === "-") {
                            $error = $error . ", El campo edificio es obligatorio";
                        } else {
                            $edificio = test_input($_POST["edificio"]);
                            if (!in_array($edificio, $arrayEdificio)) {
                                $error = $error . ", El edificio seleccionado no existe";
                            }
                        }

                        $arraySector = ["PDI", "PAS"];
                        if ($_POST["sector"] === "-") {
                            $error = $error . ", El campo sector es obligatorio";
                        } else {
                            $sector = test_input($_POST["sector"]);
                            if (!in_array($sector, $arraySector)) {
                                $error = $error . ", El sector seleccionado no existe";
                            }
                        }

                        if ($_POST["Servicio/Unidad"] === "Indique Órgano de Gobierno/Servicio") {
                            $servicio = "Sin especificar";
                        } else {
                            $servicio = test_input($_POST["Servicio/Unidad"]);
                        }

                        if ($_POST["Departamento"] === "Indique su Centro/Departamento") {
                            $departamento = "Sin especificar";
                        } else {
                            $departamento = test_input($_POST["Departamento"]);
                        }

                        if (empty($_POST["email"])) {
                            $error = error . ", Es necesario introducir el email";
                        } else {
                            $email = test_input($_POST["email"]);
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $error = $error . ", Formato de email incorrecto";
                            }
                            if (!strpos($email, "ujaen.es")) {
                                $error = $error . ", El correo facilitado no es de la Ujaen";
                            }
                        }

                        if (empty($_POST["telefono"])) {
                            $error = $error . ", Es necesario introducir un teléfono";
                        } else {
                            $telefono = test_input($_POST["telefono"]);
                            if (!preg_match("/[+]?[0-9]{7,15}/", $telefono)) {
                                $error = $error . ", Introduzca un número de teléfono correcto";
                            }
                        }

                        if (empty($_POST["movil"])) {
                            $movil = "Sin especificar";
                        } else {
                            $movil = test_input($_POST["movil"]);
                            if (!preg_match("/[+]?[0-9]{7,15}/", $movil)) {
                                $error = $error . ", Introduzca un número de móvil correcto";
                            }
                        }

                        $arrayEspacios = ["Aula Magna C1", "Sala Institucional B1", "Salón de Grados A3", "Sala de Grados D1", "Salón de Grados Pascual Rivas (Edificio Magisterio)", "Sala de Juntas A3", "Sala de Juntas/Prensa B1", "Sala de Juntas B3", "Sala de Juntas D1", "Sala de Juntas D2", "Sala de Juntas D3", "Sala de Juntas C4", "Sala de Juntas C5", "Sala de Juntas Edificio Magisterio", "Sala de Exposiciones C-5 (indicar ala en observaciones)", "Sala de Exposiciones D-1", "Cáterin Sótano A3", "Vestíbulo edificio (indicar edificio y ala en observaciones)", "Exteriores edificio (indicar edificio en observaciones)", "Otros (especificar en observaciones)"];
                        if (empty($_POST["espacio"])) {
                            $error = $error . ", El espacio no está definido, ha ocurrido algún error";
                        } else {
                            $espacio = test_input($_POST["espacio"]);
                            if (!in_array($espacio, $arrayEspacios)) {
                                $error = $error . ", El espacio seleccionado no existe, ha ocurrido algún error";
                            }
                        }

                        if (empty($_POST["startdate"])) {
                            $error = $error . ", El fecha de inicio no está definida, ha ocurrido algún error";
                        } else {
                            $startdate = test_input($_POST["startdate"]);
                            if (!strtotime($startdate)) {
                                $error = $error . ", La fecha de inicio es incorrecta, ha ocurrido algún error";
                            } else {
                                $bookdate = explode(" ", $startdate)[0];
                            }
                            //echo $startdate . "<br/>";
                        }

                        if (empty($_POST["hora_finalizacion"])) {
                            $error = $error . ", El fecha de fin no está definida, ha ocurrido algún error";
                        } else {
                            $enddate = test_input($_POST["hora_finalizacion"]);
                            $enddate = $bookdate . " " . $enddate . ":00";
                            if (!strtotime($enddate)) {
                                $error = $error . ", La fecha de fin es incorrecta, ha ocurrido algún error";
                            }
                        }

                        if (empty($_POST["hora_acto"])) {
                            $error = $error . ", El fecha del acto no está definida, ha ocurrido algún error";
                        } else {
                            $eventdate = test_input($_POST["hora_acto"]);
                            $eventdate = $bookdate . " " . $eventdate . ":00";
                            if (!strtotime($eventdate)) {
                                $error = $error . ", La fecha del acto es incorrecta, ha ocurrido algún error";
                            }
                        }

                        if (empty($_POST["evento"])) {
                            $error = $error . ", Es necesario introducir una descripción del evento a realizar";
                        } else {
                            $event = test_input($_POST["evento"]);
                        }

                        if (empty($_POST["observaciones"])) {
                            $error = $error . ", Es necesario introducir una descripción del evento a realizar";
                        } else {
                            $observaciones = test_input($_POST["observaciones"]);
                        }

                        if (is_numeric(test_input($_POST["num_personas_mesa_presidencial"]))) {
                            $num_per_mesa_presi = test_input($_POST["num_personas_mesa_presidencial"]);
                        } else {
                            $num_per_mesa_presi = 0;
                        }
                        if (is_numeric(test_input($_POST["botellas"]))) {
                            $botellas = test_input($_POST["botellas"]);
                        } else {
                            $botellas = 0;
                        }
                        
                        if (empty($_POST["site"])) {
                            $error = $error . ", Ha ocurrido un error inesperado";
                        } else {
                            $site = test_input($_POST["site"]);
                        }



                        if ($error !== "") {
                            echo "No se ha hecho na de na<br/>";
                            //$sql = "SELECT COUNT(id) FROM reserve_table WHERE plane='$site' AND name='$espacio' AND ((startdate BETWEEN UNIX_TIMESTAMP('$startdate') AND UNIX_TIMESTAMP('$enddate')) OR (enddate BETWEEN UNIX_TIMESTAMP('$startdate') AND UNIX_TIMESTAMP('$enddate')));";
                            $sql = "SELECT COUNT(id) solapacion FROM reserve_table "
                                    . "WHERE plane='$site' AND "
                                    . "name='$espacio' AND "
                                    . "((UNIX_TIMESTAMP('$startdate') BETWEEN startdate AND enddate) OR (UNIX_TIMESTAMP('$enddate') BETWEEN startdate AND enddate));";
                            echo $sql . "<br/>";
                            
                        } else {
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "phpro_auto";

                            // Create connection
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            // Check connection
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            /*$var1 = filter_input(INPUT_GET, 'date');
                            $var2 = filter_input(INPUT_GET, 'site');
                            $var3 = filter_input(INPUT_GET, 'node');
                            $var4 = strtotime('+1 day', strtotime($var1));
                            $var4 = date('Y-m-d H:i:s', $var4);
                            $var4 = explode("-", explode(" ", $var4)[0]);
                            $var4 = mktime(0, 0, 0, $var4[1], $var4[2], $var4[0]);*/
                            
                            $sql = "SELECT COUNT(id) FROM reserve_table WHERE plane='$site' AND name='$espacio' AND ((startdate BETWEEN UNIX_TIMESTAMP('$startdate') AND UNIX_TIMESTAMP('$enddate')) AND (enddate BETWEEN UNIX_TIMESTAMP('$startdate') AND UNIX_TIMESTAMP('$enddate')));";
                            //$sql = "SELECT from_unixtime(MIN(startdate),\"%H:%i\") startdate FROM reserve_table WHERE plane = '$var2' AND node = $var3 AND (startdate BETWEEN UNIX_TIMESTAMP('$var1') AND $var4) AND (enddate BETWEEN UNIX_TIMESTAMP('$var1') AND $var4);";
                            $result = $conn->query($sql);
                            $return;
//array_push($return, $sql);
//echo $sql;
                            if ($result->num_rows > 0) {
// output data of each row
                                while ($row = $result->fetch_assoc()) {
//echo  date('H:i', $row['startdate']) . " " .  date('H:i', $row['enddate']);
//echo $row['startdate'];
//$par1['startdate'] = date('H:i', $row['startdate']);
//$par1['enddate'] = date('H:i', $row['enddate']);
                                    $return = $row;
                                }
                            }
                        }








                        foreach ($_POST as $key => $value) {
                            echo "<tr>";
                            echo "<td>";
                            echo $key;
                            echo "</td>";
                            echo "<td>";
                            echo test_input($value);
                            echo "</td>";
                            echo "</tr>";
                        }
                    }

                    function test_input($data) {
                        $data = trim($data);
                        $data = stripslashes($data);
                        $data = htmlspecialchars($data);
                        return $data;
                    }
                    ?>

                    <span class="error"><?php echo substr($error, 2); ?></span>

                </table>

            </div>

        </div>



        <script>
            //var arrCapa = ["672 (434 en Patio de butacas y 238 en Anfiteatro)", "47", "127", "82", "75", "38", "18", "47", "80", "25", "38", "31", "24", "44", "", "", "", "", "", ""];

            var arrCapa = ["82"];
            var arrNormas = ["", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""];
            // normas de uso de la Sala de Prensa
            arrNormas[6] = "<P><span style=color:red;font-size:11px;>Normas de uso indicadas por el Secretariado de Comunicación:</span> " +
                    "<ol style=color:red;font-size:11px;>" +
                    "   <li><b>Posibilidad de anulación de la reserva</b>. Dado el doble uso de esta sala, y su asignación preferente a las necesidades de Comunicación de nuestra institución, la reserva de la misma puede ser anulada si el Gabinete de Comunicación de la Universidad de Jaén precisa de la misma para un actos con los medios de comunicación.</li>" +
                    "   <li><b>Restricción en el uso de sistema de audio</b>. La sala dispone de un sistema de audio/megafonía específico para la realización de ruedas de prensa, estando restringido su uso a tal fin. Dicho material únicamente puede ser utilizado bajo supervisión del personal del Gabinete de Comunicación. El uso del televisor también queda restringido a la celebración de ruedas de prensa, no así el proyector.</li>" +
                    "   <li><b>No mover el mobiliario</b>. Debido al cableado de audio que se ha incorporado a las mesas de la sala, éstas no pueden ser movidas de su sitio, ya que podría dañar el cableado y, por consiguiente, el sistema de audio.</li>" +
                    "</ol></P>";
            arrNormas[4] = "<P><span style=color:red;font-size:14px;><b>Previa autorización del Vicerrectorado encargado de las Actividades Culturales</b></span></P>";
            arrNormas[15] = arrNormas[4];
            arrNormas[14] = arrNormas[4];
            arrNormas[1] = "<P><span style=color:red;font-size:14px;><b>Previa autorización de la Secretaría General</b></span></P>";
            //var arrEquip = ["111101110110001111", "111101110000000010", "111101110110000011", "111101110110010011", "111101110010000010", "111011110000000010", "110000000000000010", "111001111000000010", "111101111000100010", "111001110000000010", "111001111010000010", "111001110000000010", "111001110000000010", "111101110000000010", "000000000000000000", "000000000000000000", "000000000000000000", "000000000000000000", "000000000000000000", "000000000000000000"];

            var arrEquip = ["111101110110010011"];
            var arrNames = ["Sala de Grados D1"];
            var arrCheck = ["sin_necesidades", "canon", "CPU", "megafonia", "megafonia_debate", "audio_pc", "cable_red", "cable_portatil", "altavoces", "micro_inalambrico", "atril", "tv", "videoconferencia", "traduccion_simultanea", "conexion_cuadro_electrico", "agua_caliente_camerinos", "botellas", "num_personas_mesa_presidencial"];</script>

        <SCRIPT type='text/javascript'>

            function Valida() {
                var oblig = "";
                if ((document.getElementById('solicitante').value === '') || (document.getElementById('solicitante').value.length <= 12)) {
                    oblig = oblig + ", Nombre y Apellidos";
                }
                if (document.getElementById('edificio').selectedIndex === 0) {
                    oblig = oblig + ", Edificio";
                }
                if (document.getElementById('dependencia').value === '') {
                    oblig = oblig + ", Dependencia";
                }
                if (document.getElementById('sector').selectedIndex === 0) {
                    oblig = oblig + ", Sector";
                }
                if (document.getElementById('email').value === '') {
                    oblig = oblig + ", E-mail";
                }
                if ((document.getElementById('telefono').value === '') || (document.getElementById('telefono').value.length <= 3)) {
                    oblig = oblig + ", Teléfono";
                }
                if ((document.getElementById('evento').value === '') || (document.getElementById('evento').value.length <= 4)) {
                    oblig = oblig + ", Evento";
                }
                if (document.getElementById('fecha').value === '') {
                    oblig = oblig + ", Fecha";
                }
                if (document.getElementById('hora_inicio').value === '') {
                    oblig = oblig + ", Hora de inicio reserva";
                }
                if (document.getElementById('hora_finalizacion').value === '') {
                    oblig = oblig + ", Hora de finalización reserva";
                }
                if (document.getElementById('hora_acto').value === '') {
                    oblig = oblig + ", Hora de inicio del acto";
                }
                if (document.getElementById('espacio').selectedIndex === 0) {
                    oblig = oblig + ", Espacio";
                }

                if (oblig !== '') {
                    alert("Para continuar debe cumplimentar correctamente los siguientes campos:\n\n" + oblig.substring(2) + ".");
                    return false;
                }

                var filtro = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if (!filtro.test(document.getElementById('email').value)) {
                    alert("La dirección E-mail introducida es incorrecta.");
                    document.getElementById('email').focus();
                    return (false);
                }

                //return true;
                return Comprueba_nece();
            }

            function soloNumeros(evt)
            {
                var keyPressed = (evt.which) ? evt.which : event.keyCode;
                return !(keyPressed > 31 && (keyPressed < 48 || keyPressed > 57));
            }

            function Comprueba_nece() {
                //espacios con necesidades
                if ((FrontPage_Form1.espacio.selectedIndex > 0) && (FrontPage_Form1.espacio.selectedIndex < 15)) {
                    Nece = false;
                    for (i = 0; i <= 15; i++) {
                        if (FrontPage_Form1.elements[arrCheck[i]].checked === true) {
                            Nece = true;
                            break;
                        }
                    }
                    if (Nece === false) {
                        if (confirm("No ha seleccionado ninguna de las casillas de 'Necesidades para la celebración del evento'.\n\nEn cualquier caso, ¿ desea Vd. continuar con la solicitud de esta reserva ?"))
                        {
                            FrontPage_Form1.elements["sin_necesidades"].checked = true;
                        }
                        else {
                            return false;
                        }
                    }
                }

                return true;
            }

            function Sin_Nece_Click() {
                if (FrontPage_Form1.sin_necesidades.checked === true) {
                    for (i = 1; i <= 17; i++) {
                        if (i >= 16) {
                            FrontPage_Form1.elements[arrCheck[i]].value = '';
                        }
                        else {
                            FrontPage_Form1.elements[arrCheck[i]].checked = false;
                        }
                    }
                }
            }

            function Desactiva_Sin_Nece() {
                FrontPage_Form1.sin_necesidades.checked = false;
            }

            function Controla_Checks() {
                var nombrediv = '';
                for (i = 0; i <= 17; i++) {
                    if (i >= 16) {
                        FrontPage_Form1.elements[arrCheck[i]].value = '';
                    }
                    else {
                        FrontPage_Form1.elements[arrCheck[i]].checked = false;
                    }
                    nombrediv = 'd_' + arrCheck[i];
                    document.getElementById(nombrediv).style.display = '';
                }
                document.getElementById('d_txt').style.display = '';
                document.getElementById('d_normas').style.display = 'none';
                //if (FrontPage_Form1.espacio.selectedIndex !== 0)
                if (arrNames[curData[0]])
                {
                    console.log("Hemos entrado");
                    var Comp = arrEquip[curData[0]];
                    for (i = 0; i <= 17; i++) {
                        if (Comp.substring(i, i + 1) === "0")
                        {
                            nombrediv = 'd_' + arrCheck[i];
                            document.getElementById(nombrediv).style.display = 'none';
                        }
                    }
                    if (Comp === '000000000000000000') {
                        document.getElementById('d_txt').style.display = 'none';
                    }
                    if (arrCapa[curData[0]] !== "") {
                        document.getElementById("d_capacidad").innerHTML = "<b><font color='#FF0000'>Capacidad de la sala: " + arrCapa[curData[0]] + "</font></b>";
                    }
                    else {
                        document.getElementById("d_capacidad").innerHTML = "";
                    }
                    if (arrNormas[curData[0]] !== "") {
                        document.getElementById("d_normas").style.display = '';
                        document.getElementById("d_normas").innerHTML = arrNormas[curData[0]];
                    }
                    else {
                        document.getElementById("d_normas").style.display = 'none';
                    }
                }
                else {
                    console.log("Nanai de las chinas");
                    //document.getElementById('d_megafonia_debate').style.display = 'none';
                    //document.getElementById('d_num_personas_mesa_presidencial').style.display = 'none';
                    document.getElementById("d_capacidad").innerHTML = "";
                    document.getElementById('d_txt').style.display = 'none';
                    document.getElementById('d_normas').style.display = 'none';
                    for (i = 0; i <= 17; i++) {
                        nombrediv = 'd_' + arrCheck[i];
                        document.getElementById(nombrediv).style.display = 'none';
                    }
                }
            }
        </SCRIPT>


        <script>

            function logout() {
                $.get("logout.php");
                parent.window.location.reload();
                return false;
            }

            function parse() {
                //var val = "site";
                var result = [],
                        tmp = [];
                location.search
                        //.replace ( "?", "" ) 
                        // this is better, there might be a question mark inside
                        .substr(1)
                        .split("&")
                        .forEach(function (item) {
                            tmp = item.split("=");
                            //if (tmp[0] === val)
                            result.push(decodeURIComponent(tmp[1]));
                        });
                return result;
            }



            // window.onload = function () {
            var curData = parse();
            console.log(curData);
            var info = "";
            info += "<h2>" + curData[1] + "</h2><br/>";
            document.getElementById('infoSite').innerHTML = info;
            document.getElementById('site').value = curData[1];
            Controla_Checks();
            var bookDate = curData[2].split(" ")[0];
            var bookTime = curData[2].substring(10, curData[2].lastIndexOf(":"));
            document.getElementById('fecha').value = document.getElementById('fecha2').value = bookDate;
            document.getElementById('hora_inicio').value = bookTime;
            document.getElementById('espacio2').value = arrNames[curData[0]];
            document.getElementById('espacio').value = arrNames[curData[0]];
            document.getElementById('startdate').value = curData[2];

            var x = document.getElementById('hora_finalizacion');
            var y = document.getElementById('hora_acto');
            var startDel = false;
            //console.log(x[x.length-1].value);
            for (var i = x.length - 1; i >= 0; i--) {
                //console.log(x[i].value + " " + bookTime);
                if (!startDel && x[i].value === bookTime)
                    startDel = true;
                //x.remove(i);
                else
                if (startDel)
                    x.remove(i);
                //x.remove(0);
                //x.remove(0});
            }
            x.remove(0);
            console.log(x[0].value);

<?php
//date_default_timezone_set('UTC');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phpro_auto";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//$var1 = filter_input(INPUT_GET, 'date').split(" ");    
//$var1 = explode(" ", filter_input(INPUT_GET, 'date'));
$var1 = filter_input(INPUT_GET, 'date');
$var2 = filter_input(INPUT_GET, 'site');
$var3 = filter_input(INPUT_GET, 'node');
$var4 = strtotime('+1 day', strtotime($var1));
$var4 = date('Y-m-d H:i:s', $var4);
$var4 = explode("-", explode(" ", $var4)[0]);
$var4 = mktime(0, 0, 0, $var4[1], $var4[2], $var4[0]);


//echo $var4[0];
//echo $var1 . " " . date('Y-m-d H:i:s', $var4);
//echo  date('Y-m-d H:i:s', $var4);


$sql = "SELECT from_unixtime(MIN(startdate),\"%H:%i\") startdate FROM reserve_table WHERE plane = '$var2' AND node = $var3 AND (startdate BETWEEN UNIX_TIMESTAMP('$var1') AND $var4) AND (enddate BETWEEN UNIX_TIMESTAMP('$var1') AND $var4);";
$result = $conn->query($sql);
$return;
//array_push($return, $sql);
//echo $sql;
if ($result->num_rows > 0) {
// output data of each row
    while ($row = $result->fetch_assoc()) {
//echo  date('H:i', $row['startdate']) . " " .  date('H:i', $row['enddate']);
//echo $row['startdate'];
//$par1['startdate'] = date('H:i', $row['startdate']);
//$par1['enddate'] = date('H:i', $row['enddate']);
        $return = $row;
    }
}

echo "var curReser = \"" . $return['startdate'] . "\";";

//echo  date('H:i', $return[0]['startdate']) . " " .  date('H:i', $return[0]['enddate']);
//echo "var curReser = " . json_encode($return) . ";";
?>

            for (var j = 0; j < x.length; j++) {
                if (x[j].value === curReser)
                    var s = j;
            }
            for (var m = x.length; m > s; m--)
                //console.log(m);
                x.remove(m);
            y.innerHTML = x.innerHTML;
            //console.log(s + " " + e);


            //x.remove


            function hora_acto_validar(v) {
                if (v > x.selectedIndex) {
                    alert("La hora de inicio no puede estar fuera del horario de reserva.");
                    y.options[0].selected = 'selected';
                }
            }



            //console.log(document.getElementById('hora_finalizacion').indexOf(document.getElementById('hora_inicio').value));
            //$("#fecha").attr("size", curData[2].split(" ")[0].length);
            // };

        </script>






    </body>

</html>

