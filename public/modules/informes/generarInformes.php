<?php

function create_report($idprovncia, $mes, $anno) {
		
error_reporting();
include_once 'classes/PHPExcel.php';

////////////////////////CONEXION//////////////////////////////
	///localhost, nombre del servidor<br />
	///root, nombre de la cuenta de usuario<br />
	/// '' contraseña, sino tiene deje vacio
	///BD, nombre de la base de datos
	// $conexion = mysql_connect('localhost','root','');
	// mysql_select_db('gesflota',$conexion);
/////////////////////////////////////////////////////////////
$objXLS = new PHPExcel();
$objSheet = $objXLS->setActiveSheetIndex(0);


////////////////////CONFIGURACIÓN DE ELEMENTOS DE HOJA///////////////////////////
	
	// INMOVILIZAR PANELES	
	$objXLS->getActiveSheet()->freezePane('X13');
	$objXLS->getActiveSheet()->freezePaneByColumnAndRow('23', 13);
	// FILTROS
		$objXLS->getActiveSheet()->setAutoFilter('B12:D12'); 

////////////////////CONFIGURACIÓN DE IMPRESIÓN///////////////////////////

$objXLS->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 6);
$objXLS->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objXLS->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$objXLS->getActiveSheet()->getPageSetup()->setFitToPage(true);
$objXLS->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$objXLS->getActiveSheet()->getPageSetup()->setFitToHeight(0);

$objXLS->getActiveSheet()->getPageMargins()->setTop(0.6);
$objXLS->getActiveSheet()->getPageMargins()->setRight(0.25);
$objXLS->getActiveSheet()->getPageMargins()->setLeft(0.25);
$objXLS->getActiveSheet()->getPageMargins()->setBottom(0.3);


$objXLS->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);



////////////////////NOMBRE PRIMERA HOJA///////////////////////////
$objXLS->getActiveSheet()->setTitle('Intervenciones');

//////////////////// INCLUDES ///////////////////////////

$meses = array('enero','febrero','marzo','abril','mayo','junio','julio', 'agosto','septiembre','octubre','noviembre','diciembre');

//////////////////// FUNCIONES PARA PROCESAR DATOS DE LA BD ///////////////////////////

function procesarBoleano($booleanData) {
	return $booleanData ? "X": "";
}
function procesarFecha($date) {
	$date = substr($date,0,-3);
	$t_year  = substr($date,0,4);
	$t_month = substr($date,5,2);
	$t_day   = substr($date,8,2);
	$t_hours   = substr($date,11,2);
	$t_mins   = substr($date,14,2);


    return $t_day . "-" . $t_month . "-" . $t_year . " " . $t_hours . ":" . $t_mins;
}

function procesarMotivo($valor) {
	switch ($valor) {
		case 'avisoAveria':
			return "AVISO DE AVERÍA";
			break;
		case 'desplazamiento':
			return "DESPLAZAMIENTO";
			break;
		case 'avisoUnidad':
			return "AVISO EN UNIDAD";
			break;
		case 'preventivos':
			return "ACT. PREVENTIVAS";
			break;
		case 'asistencia':
			return "ASISTENCIA";
			break;
	}
}


function obtenerProvincia($id){
	$qProvincia = "select UPPER(Nombre) as Nombre from provincias where IDProvincia = " . $id;
	$resultP = mysql_query($qProvincia);
	$regP = mysql_fetch_assoc($resultP);

	return utf8_encode($regP["Nombre"]);
}

//////////////////// PARAMETROS VARIABLES PARA CONSULTAR INFORME ///////////////////////////


$mesText = $meses[$mes-1];
$provinciaText = obtenerProvincia($provincia);

////////////////////ARRAYS DE ESTILO///////////////////////////

// DEFINICIÓN DE COLORES
$color_dark_blue = '151B31';
$color_royal_blue = '033243';
$color_marine_aqua = '056564';
$color_dark_yellow = 'CDB370';
$color_stone_gray = 'E7DDCB';
$color_white = 'FFFFFF';
$color_gray = 'CCCCCC';


// Rellenos

$white = array(
    'fill'  => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,  
        'color' => array('rgb' => $color_white)
     
));

$gray = array(
    'fill'  => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,  
        'color' => array('rgb' => $color_gray)
     
));

$royal_blue = array(
    'fill'  => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,  
        'color' => array('rgb' => $color_royal_blue)
     
));

$marine_aqua = array(
    'fill'  => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,  
        'color' => array('rgb' => $color_marine_aqua)
));


// Texts
$h1 = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => $color_stone_gray),
        'size'  => 15,
        'name'  => 'Verdana'
));

$h2 = array(
    'font'  => array(
    	'bold' => true,
        'italic'  => true,
        'color' => array('rgb' => $color_stone_gray),
        'size'  => 10,
        'name'  => 'Verdana'
));

$h3 = array(
    'font'  => array(
    	'bold' => true,
        'italic'  => true,
        'color' => array('rgb' => $color_marine_aqua),
        'size'  => 8,
        'name'  => 'arial'
    ),
    'borders' => array(
    	'allborders' => array(
      		'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
      		'color' => array('rgb' => $color_royal_blue)
      	),
    	'top' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THICK,
      		'color' => array('rgb' => $color_royal_blue)
		),
		'right' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THICK,
      		'color' => array('rgb' => $color_royal_blue)
		),
		'left' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THICK,
      		'color' => array('rgb' => $color_royal_blue)
		),
      	'bottom' => array(
      		'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
      		'color' => array('rgb' => $color_royal_blue)
)));

// H3 con bordes laterales gruesos
$h3_THICK = array(
    'font'  => array(
    	'bold' => true,
        'italic'  => true,
        'color' => array('rgb' => $color_marine_aqua),
        'size'  => 8,
        'name'  => 'arial'
    ),
    'borders' => array(
    	'allborders' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THICK,
      		'color' => array('rgb' => $color_royal_blue)
      	),  
      	'bottom' => array(
      		'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
      		'color' => array('rgb' => $color_royal_blue)
)));

$h4 = array(
    'font'  => array(
    	'bold' => true,
        'italic'  => true,
        'color' => array('rgb' => $color_marine_aqua),
        'size'  => 8,
        'name'  => 'arial'
    ),
    'borders' => array(
    	'vertical' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THIN,
      		'color' => array('rgb' => $color_royal_blue)
      	),
    	'top' => array(
      		'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
      		'color' => array('rgb' => $color_royal_blue)
		),
		'right' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THICK,
      		'color' => array('rgb' => $color_royal_blue)
		),
		'left' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THICK,
      		'color' => array('rgb' => $color_royal_blue)
		),
      	'bottom' => array(
      		'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
      		'color' => array('rgb' => $color_royal_blue)
)));

$border_firstCol = array(
    'borders' => array(
    	'left' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THICK,
      		'color' => array('rgb' => $color_royal_blue)
		), 
		'right' => array(
      		'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
      		'color' => array('rgb' => $color_royal_blue) 	
)));

// Body cells

$border_midCol = array(
    'borders' => array(
    	'right' => array(
      		'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
      		'color' => array('rgb' => $color_royal_blue) 	
)));

$border_lastCol = array(
    'borders' => array(
		'right' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THICK,
      		'color' => array('rgb' => $color_royal_blue)
)));

$border_firstCol_vert = array(
    'borders' => array(
    	'left' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THICK,
      		'color' => array('rgb' => $color_royal_blue)
		), 
		'right' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THIN,
      		'color' => array('rgb' => $color_royal_blue) 	
)));

// Body cells

$border_midCol_vert = array(
    'borders' => array(
    	'right' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THIN,
      		'color' => array('rgb' => $color_royal_blue) 	
)));

$border_lastCol_vert = array(
    'borders' => array(
		'right' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THICK,
      		'color' => array('rgb' => $color_royal_blue)
)));
$end_table= array(
    'borders' => array(
		'bottom' => array(
      		'style' => PHPExcel_Style_Border::BORDER_THICK,
      		'color' => array('rgb' => $color_royal_blue)
)));

$fill_cell_impares = array(
    'fill'  => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,  
        'color' => array('rgb' => 'eeeeee')
));

$fill_cell_royalblue = array(
    'fill'  => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,  
        'color' => array('rgb' => $color_royal_blue)
));


////////////////////ESTILO CABECERA PAGINAS///////////////////////////

// LOGOS
	// IPMOTOR
	// $objDrawingIPM = new PHPExcel_Worksheet_Drawing();
	// $objDrawingIPM->setName('IPMOTOR');
	// $objDrawingIPM->setDescription('Logo del software de gestión de flotas IPMOTOR');
	// $objDrawingIPM->setPath('./images/ipmotor.png');
	// $objDrawingIPM->setCoordinates('F2');
	// $objDrawingIPM->setHeight(25);
	// $objDrawingIPM->setOffsetX(62);
	// $objDrawingIPM->setOffsetY(7);
	// $objDrawingIPM->setWorksheet($objXLS->getActiveSheet());

	// CORREOS
	$objDrawingCRRS = new PHPExcel_Worksheet_Drawing();
	$objDrawingCRRS->setName('Correos');
	$objDrawingCRRS->setDescription('Logo de Correos');
	$objDrawingCRRS->setPath('./classes/images/correos.png');
	$objDrawingCRRS->setCoordinates('Q2');
	$objDrawingCRRS->setHeight(43);
	$objDrawingCRRS->setOffsetX(-8);
	$objDrawingCRRS->setOffsetY(2);
	$objDrawingCRRS->setWorksheet($objXLS->getActiveSheet());

	// DANSAJA
	$objDrawingDNSJ = new PHPExcel_Worksheet_Drawing();
	$objDrawingDNSJ->setName('Dansaja');
	$objDrawingDNSJ->setDescription('Logo de Dansaja');
	$objDrawingDNSJ->setPath('./classes/images/dansaja.png');
	$objDrawingDNSJ->setCoordinates('B2');
	$objDrawingDNSJ->setHeight(65);
	$objDrawingDNSJ->setOffsetX(-6);
	$objDrawingDNSJ->setOffsetY(-4);
	$objDrawingDNSJ->setWorksheet($objXLS->getActiveSheet());



// COMPONER FILAS

$objXLS->getActiveSheet()->mergeCells('A1:W1')->getRowDimension(1)->setRowHeight(4);
$objXLS->getActiveSheet()->mergeCells('A2:W2')->getRowDimension(2)->setRowHeight(25);
$objXLS->getActiveSheet()->mergeCells('A3:W3')->getRowDimension(3)->setRowHeight(15);
$objXLS->getActiveSheet()->mergeCells('A4:W4')->getRowDimension(4)->setRowHeight(4);
$objXLS->getActiveSheet()->mergeCells('A5:W5')->getRowDimension(5)->setRowHeight(5);
$objXLS->getActiveSheet()->mergeCells('A6:W6')->getRowDimension(6)->setRowHeight(7);






// COLOR DE RELLENO
$objXLS->getActiveSheet()->getStyle('A1:W5')->applyFromArray($royal_blue);
$objXLS->getActiveSheet()->getStyle('A5:W5')->applyFromArray($marine_aqua);




////////////////////ESTRUCTURA DEL DOCUMENTO///////////////////////////

// COMBINAR TITULOS N3
$objXLS->getActiveSheet()->mergeCells('H7:M7')->getRowDimension(7)->setRowHeight(14);
$objXLS->getActiveSheet()->mergeCells('N7:P7')->getRowDimension(7)->setRowHeight(14);
$objXLS->getActiveSheet()->mergeCells('Q7:V7')->getRowDimension(7)->setRowHeight(14);

// EXPANDIR TITULO N4'UNIDAD DE DESTINO' HASTA TITULO N3
$objXLS->getActiveSheet()->mergeCells('P8:P9')->getStyle('P8:P9')->applyFromArray($gray);


// ANCHO Y ALTO COLUMNAS Y FILAS
	
	// ALTOS FILAS
	$objXLS->getActiveSheet()->getRowDimension(7)->setRowHeight(20);
	$objXLS->getActiveSheet()->getRowDimension(10)->setRowHeight(23);

	// ANCHO COLUMNAS
	$objXLS->getActiveSheet()->getColumnDimension('A')->setWidth(3);
	$objXLS->getActiveSheet()->getColumnDimension('B')->setWidth(16);
	$objXLS->getActiveSheet()->getColumnDimension('C')->setWidth(30);
	$objXLS->getActiveSheet()->getColumnDimension('D')->setWidth(12);
	$objXLS->getActiveSheet()->getColumnDimension('E')->setWidth(12);
	$objXLS->getActiveSheet()->getColumnDimension('F')->setWidth(17);
	$objXLS->getActiveSheet()->getColumnDimension('G')->setWidth(14);
	
 	foreach(range('H','V') as $columnID) {
 		$objXLS->getActiveSheet()->getColumnDimension($columnID)->setWidth(3);
 	}
 		// ANCHO PARA DESTINO
		$objXLS->getActiveSheet()->getColumnDimension('P')->setWidth(30);

	$objXLS->getActiveSheet()->getColumnDimension('W')->setWidth(3);


// ALINEACION

	// 'B' CENTRADA
	$objXLS->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	// 'B' Y 'D' CENTRADA
	foreach(range('D','E') as $columnID) {
		$objXLS->getActiveSheet()->getStyle($columnID)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	}


////////////////////TITULOS N1///////////////////////////


// TEXTOS
$objSheet->setCellValue('A2', "FLOTA DE VEHÍCULOS DE DOS RUEDAS - " . $provinciaText);

// ESTILOS

$objXLS->getActiveSheet()->getStyle('A2')->applyFromArray($h1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


////////////////////TITULOS N2///////////////////////////

// TEXTOS
$objSheet->setCellValue('A3', "INTERVENCIONES REALIZADA - " . mb_strtoupper($mesText,'utf-8') . " ". $anno);

// ESTILOS

$objXLS->getActiveSheet()->getStyle('A3')->applyFromArray($h2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


////////////////////TITULOS N3///////////////////////////

// TEXTOS

$objSheet->setCellValue('B10', 'FECHA DE INTERVENCIÓN');
$objSheet->setCellValue('C10', 'UNIDAD');
$objSheet->setCellValue('D10', 'MATRÍCULA');
$objSheet->setCellValue('E10', 'LECTURA KILÓMETROS');
$objSheet->setCellValue('F10', 'MOTIVO DE INTERVENCIÓN');
$objSheet->setCellValue('G10', 'LUGAR DE INTERVENCIÓN');

$objSheet->setCellValue('H7', "INT. PREVENTIVAS");
$objSheet->setCellValue('N7', "VEHÍCULOS DESPLAZADOS");
$objSheet->setCellValue('Q7', "INT. CORRECTIVAS");


// COMBINAR CELDAS PARA TEXTOS HORIZONTALES
	$objXLS->getActiveSheet()->mergeCells('B10:B12');
	$objXLS->getActiveSheet()->mergeCells('C10:C12');
	$objXLS->getActiveSheet()->mergeCells('D10:D12');
	$objXLS->getActiveSheet()->mergeCells('E10:E12');
	$objXLS->getActiveSheet()->mergeCells('F10:F12');
	$objXLS->getActiveSheet()->mergeCells('G10:G12');

// CENTRAR ROTULOS ORIZONTALES EN LA VERTICAL
	$objXLS->getActiveSheet()->getStyle('B10:G10')->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

// ESTILOS
	
	// COLORES
		// RELLENO, TEXT, BORDES
		$objXLS->getActiveSheet()->getStyle('H7:V7')->applyFromArray($gray)->applyFromArray($h3_THICK);
													
		$objXLS->getActiveSheet()->getStyle('B10:G10')->applyFromArray($h3);
		$objXLS->getActiveSheet()->getStyle('B11:G11')->applyFromArray($h3);
		$objXLS->getActiveSheet()->getStyle('B12:G12')->applyFromArray($h3);
	

	// ALINEACIONES
	$objXLS->getActiveSheet()->getStyle('H7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$objXLS->getActiveSheet()->getStyle('N7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
	$objXLS->getActiveSheet()->getStyle('Q7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	

////////////////////TITULOS N4///////////////////////////

// TEXTOS

$objSheet->setCellValue('H8', ' M. PREVENTIVO');
$objSheet->setCellValue('I8', ' ITV');
$objSheet->setCellValue('J8', ' LAVADO');
$objSheet->setCellValue('K8', ' AJUSTE FRENOS');
$objSheet->setCellValue('L8', ' PRESIÓN NEUMÁT.');
$objSheet->setCellValue('M8', ' NIVEL DE ACEITE');
$objSheet->setCellValue('N8', ' ENTREGADO');
$objSheet->setCellValue('O8', ' RECOGIDO');
$objSheet->setCellValue('P10', 'UNIDAD DE DESTINO');
$objSheet->setCellValue('Q8', ' R. MECÁNICA');
$objSheet->setCellValue('R8', ' R. ELÉCTRICA');
$objSheet->setCellValue('S8', ' R. RUEDAS');
$objSheet->setCellValue('T8', ' R. CARROCERÍA');
$objSheet->setCellValue('U8', ' R. COFRE');
$objSheet->setCellValue('V8', ' OTRAS');

// ESTILOS Y ESTRUCTURA

	
	// COMBINAR CELDAS PARA TEXTOS HORIZONTALES
	$objXLS->getActiveSheet()->mergeCells('P10:P12');
	

	// COMBINAR CELDAS PARA TEXTOS VERTICALES
	$objXLS->getActiveSheet()->mergeCells('H8:H12');
	$objXLS->getActiveSheet()->mergeCells('I8:I12');
	$objXLS->getActiveSheet()->mergeCells('J8:J12');
	$objXLS->getActiveSheet()->mergeCells('K8:K12');
	$objXLS->getActiveSheet()->mergeCells('L8:L12');
	$objXLS->getActiveSheet()->mergeCells('M8:M12');
	$objXLS->getActiveSheet()->mergeCells('N8:N12');
	$objXLS->getActiveSheet()->mergeCells('O8:O12');

	$objXLS->getActiveSheet()->mergeCells('Q8:Q12');
	$objXLS->getActiveSheet()->mergeCells('R8:R12');
	$objXLS->getActiveSheet()->mergeCells('S8:S12');
	$objXLS->getActiveSheet()->mergeCells('T8:T12');
	$objXLS->getActiveSheet()->mergeCells('U8:U12');
	$objXLS->getActiveSheet()->mergeCells('V8:V12');

// CENTRAR Y ROTAR TEXTOS VERTICALES
$objXLS->getActiveSheet()->getStyle('H8:O8')->getAlignment()->setTextRotation(90)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objXLS->getActiveSheet()->getStyle('Q8:V8')->getAlignment()->setTextRotation(90)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// CENTRAR ROTULOS ORIZONTALES EN LA VERTICAL
	$objXLS->getActiveSheet()->getStyle('P10')->getAlignment()->setWrapText(true)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	// ALINEAR IZQUIERDA Y QUITAR ROTACIÓN A 'DESTINO'
	$objXLS->getActiveSheet()->getStyle('P10')->getAlignment()->setTextRotation(0)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

// COLORES Y BORDES
	// BORDES
	$objXLS->getActiveSheet()->getStyle('H8:M12')->applyFromArray($h4);
	$objXLS->getActiveSheet()->getStyle('N8:P12')->applyFromArray($h4);
	$objXLS->getActiveSheet()->getStyle('Q8:V12')->applyFromArray($h4);	
	// COLORES
	$objXLS->getActiveSheet()->getStyle('B10:G10')->applyFromArray($gray);	
	$objXLS->getActiveSheet()->getStyle('H8:O8')->applyFromArray($gray);	
	$objXLS->getActiveSheet()->getStyle('P10:P10')->applyFromArray($gray);	
	$objXLS->getActiveSheet()->getStyle('Q8:V8')->applyFromArray($gray);	



//////////////////// CONSULTA INTERVENCIONES///////////////////////////


$qIntervenciones = "SELECT ";
$qIntervenciones .= "i.FechaIntervencion AS Fecha, ";
$qIntervenciones .= "CONCAT(u.Unidad, ' ', u.TipoUnidad) AS Unidad, ";
$qIntervenciones .= "m.Matricula AS Matricula, ";
$qIntervenciones .= "i.kilometros AS Kilometros, ";
$qIntervenciones .= "i.MotivoIntervencion AS Motivo, ";
$qIntervenciones .= "i.LugarIntervencion AS LugarI, ";
$qIntervenciones .= "i.MPreventivo AS Preventivo, ";
$qIntervenciones .= "i.ITV AS ITV, ";
$qIntervenciones .= "i.Lavado AS Lavado, ";
$qIntervenciones .= "i.AjFrenos AS Frenos, ";
$qIntervenciones .= "i.PrNeumaticos AS PNeumaticos, ";
$qIntervenciones .= "i.NivelAceite AS Aceite, ";
$qIntervenciones .= "i.Entregado AS Entregado, ";
$qIntervenciones .= "i.Recogido AS Recogido, ";
$qIntervenciones .= "CONCAT(ud.Unidad, ' ', ud.TipoUnidad) AS UnidadDestino, ";
$qIntervenciones .= "i.RMecanica AS Mecanica, ";
$qIntervenciones .= "i.RElectrica AS Electrica, ";
$qIntervenciones .= "i.RRuedas AS Rueda, ";
$qIntervenciones .= "i.RCarroceria AS Carroceria, ";
$qIntervenciones .= "i.RCofre AS Cofre, ";
$qIntervenciones .= "i.ROtras AS Otras ";
$qIntervenciones .= "FROM unidades u, intervenciones i ";
$qIntervenciones .= 	"LEFT JOIN ";
$qIntervenciones .=			"unidades ud ";
$qIntervenciones .=		"ON i.IDUnidadDestino = ud.IDUnidad ";
$qIntervenciones .=	"INNER JOIN ";
$qIntervenciones .= "matriculas m ";
$qIntervenciones .= " ON i.IDMatricula = m.IDMatricula ";
$qIntervenciones .= "WHERE i.IDUnidad = u.IDUnidad ";
$qIntervenciones .=		"AND u.IDProvincia = " . $provincia . " ";
$qIntervenciones .=		"AND MONTH(i.FechaIntervencion) = " . $mes . " ";
$qIntervenciones .=		"AND YEAR(i.FechaIntervencion) = " . $anno . " ";
$qIntervenciones .= "ORDER BY i.FechaIntervencion ASC";


//////////////////// VOLCAR CUERPO DE LA TABLA///////////////////////////
	
	$numero=12;

	$intervenciones=mysql_query($qIntervenciones);
	while($intervencion=mysql_fetch_array($intervenciones)){
		$numero++;
		
		
		$objSheet->setCellValue('B'.$numero, procesarFecha($intervencion['Fecha']));
		
		$objXLS->getActiveSheet()->getStyle('B'.$numero)->applyFromArray($border_firstCol);
		
		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('B'.$numero)->applyFromArray($fill_cell_impares); }
		
		$objSheet->setCellValue('C'.$numero, $intervencion['Unidad']);
		$objXLS->getActiveSheet()->getStyle('C'.$numero)->applyFromArray($border_midCol);
		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('C'.$numero)->applyFromArray($fill_cell_impares); }
		
		$objSheet->setCellValue('D'.$numero, $intervencion['Matricula']);
		$objXLS->getActiveSheet()->getStyle('D'.$numero)->applyFromArray($border_midCol);
		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('D'.$numero)->applyFromArray($fill_cell_impares); }
		
		$objSheet->setCellValue('E'.$numero, $intervencion['Kilometros']);
		$objXLS->getActiveSheet()->getStyle('E'.$numero)->applyFromArray($border_midCol);
		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('E'.$numero)->applyFromArray($fill_cell_impares); }
		
		$objSheet->setCellValue('F'.$numero, procesarMotivo($intervencion['Motivo']));
		$objXLS->getActiveSheet()->getStyle('F'.$numero)->applyFromArray($border_midCol);
		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('F'.$numero)->applyFromArray($fill_cell_impares); }
		
		$objSheet->setCellValue('G'.$numero, strtoupper($intervencion['LugarI']));
		$objXLS->getActiveSheet()->getStyle('G'.$numero)->applyFromArray($border_midCol);

		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('G'.$numero)->applyFromArray($fill_cell_impares); }

		$objSheet->setCellValue('H'.$numero, procesarBoleano(procesarBoleano($intervencion['Preventivo'])));
		$objXLS->getActiveSheet()->getStyle('H'.$numero)->applyFromArray($border_firstCol_vert);
		$objXLS->getActiveSheet()->getStyle('H'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('H'.$numero)->applyFromArray($fill_cell_impares); }

		$objSheet->setCellValue('I'.$numero, procesarBoleano($intervencion['ITV']));
		$objXLS->getActiveSheet()->getStyle('I'.$numero)->applyFromArray($border_midCol_vert);
		$objXLS->getActiveSheet()->getStyle('I'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('I'.$numero)->applyFromArray($fill_cell_impares); }

		$objSheet->setCellValue('J'.$numero, procesarBoleano($intervencion['Lavado']));
		$objXLS->getActiveSheet()->getStyle('J'.$numero)->applyFromArray($border_midCol_vert);
		$objXLS->getActiveSheet()->getStyle('J'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('J'.$numero)->applyFromArray($fill_cell_impares); }

		$objSheet->setCellValue('K'.$numero, procesarBoleano($intervencion['Frenos']));
		$objXLS->getActiveSheet()->getStyle('K'.$numero)->applyFromArray($border_midCol_vert);
		$objXLS->getActiveSheet()->getStyle('K'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('K'.$numero)->applyFromArray($fill_cell_impares); }

		$objSheet->setCellValue('L'.$numero, procesarBoleano($intervencion['PNeumaticos']));
		$objXLS->getActiveSheet()->getStyle('L'.$numero)->applyFromArray($border_midCol_vert);
		$objXLS->getActiveSheet()->getStyle('L'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('L'.$numero)->applyFromArray($fill_cell_impares); }

		$objSheet->setCellValue('M'.$numero, procesarBoleano($intervencion['Aceite']));
		$objXLS->getActiveSheet()->getStyle('M'.$numero)->applyFromArray($border_lastCol_vert);
		$objXLS->getActiveSheet()->getStyle('M'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('M'.$numero)->applyFromArray($fill_cell_impares); }

		$objSheet->setCellValue('N'.$numero, procesarBoleano($intervencion['Entregado']));
		$objXLS->getActiveSheet()->getStyle('N'.$numero)->applyFromArray($border_midCol_vert);
		$objXLS->getActiveSheet()->getStyle('N'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('N'.$numero)->applyFromArray($fill_cell_impares); }


		$objSheet->setCellValue('O'.$numero, procesarBoleano($intervencion['Recogido']));
		$objXLS->getActiveSheet()->getStyle('O'.$numero)->applyFromArray($border_midCol_vert);
		$objXLS->getActiveSheet()->getStyle('O'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('O'.$numero)->applyFromArray($fill_cell_impares); }

		$objSheet->setCellValue('P'.$numero, $intervencion['UnidadDestino']);
		$objXLS->getActiveSheet()->getStyle('P'.$numero)->applyFromArray($border_lastCol_vert);

		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('P'.$numero)->applyFromArray($fill_cell_impares); }

		$objSheet->setCellValue('Q'.$numero, procesarBoleano($intervencion['Mecanica']));
		$objXLS->getActiveSheet()->getStyle('Q'.$numero)->applyFromArray($border_midCol_vert);
		$objXLS->getActiveSheet()->getStyle('Q'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('Q'.$numero)->applyFromArray($fill_cell_impares); }

		$objSheet->setCellValue('R'.$numero, procesarBoleano($intervencion['Electrica']));
		$objXLS->getActiveSheet()->getStyle('R'.$numero)->applyFromArray($border_midCol_vert);
		$objXLS->getActiveSheet()->getStyle('R'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('R'.$numero)->applyFromArray($fill_cell_impares); }

		$objSheet->setCellValue('S'.$numero, procesarBoleano($intervencion['Rueda']));
		$objXLS->getActiveSheet()->getStyle('S'.$numero)->applyFromArray($border_midCol_vert);
		$objXLS->getActiveSheet()->getStyle('S'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('S'.$numero)->applyFromArray($fill_cell_impares); }

		$objSheet->setCellValue('T'.$numero, procesarBoleano($intervencion['Carroceria']));
		$objXLS->getActiveSheet()->getStyle('T'.$numero)->applyFromArray($border_midCol_vert);
		$objXLS->getActiveSheet()->getStyle('T'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('T'.$numero)->applyFromArray($fill_cell_impares); }

		$objSheet->setCellValue('U'.$numero, procesarBoleano($intervencion['Cofre']));
		$objXLS->getActiveSheet()->getStyle('U'.$numero)->applyFromArray($border_midCol_vert);
		$objXLS->getActiveSheet()->getStyle('U'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('U'.$numero)->applyFromArray($fill_cell_impares); }

		$objSheet->setCellValue('V'.$numero, procesarBoleano($intervencion['Otras']));
		$objXLS->getActiveSheet()->getStyle('V'.$numero)->applyFromArray($border_lastCol);
		$objXLS->getActiveSheet()->getStyle('V'.$numero)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		if ($numero % 2 == 0) { $objXLS->getActiveSheet()->getStyle('V'.$numero)->applyFromArray($fill_cell_impares); }

		if ($numero - 12 == mysql_num_rows($intervenciones)){
			$objXLS->getActiveSheet()->getStyle('B' . $numero . ':' . 'V' . $numero)->applyFromArray($end_table);
		}

	}



$objXLS->createSheet();
$objXLS->setActiveSheetIndex(1);
$objXLS->getActiveSheet()->setTitle('Repuestos');

$objXLS->setActiveSheetIndex(0);



//////////////////// GRABACIÓN DE LIBRO ///////////////////////////



$objWriter = PHPExcel_IOFactory::createWriter($objXLS, 'Excel2007');
$objWriter->save("almacen/Regiones.xls");

}
?>







