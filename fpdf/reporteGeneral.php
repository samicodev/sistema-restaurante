<?php

require('./fpdf.php');

class PDF extends FPDF
{

   // Cabecera de página
   function Header()
   {
      $this->Image('logo.png', 20, 5, 30); //logo de la empresa,moverDerecha,moverAbajo,tamañoIMG
      $this->SetFont('Times', 'BU', 18); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(85); // Movernos a la derecha
      $this->SetTextColor(11, 12, 19); //color
      //creamos una celda o fila
      $this->Cell(30, 15, utf8_decode('REPORTE DE VENTAS'), 0, 0, 'C', 0); // AnchoCelda,AltoCelda,titulo,borde(1-0),saltoLinea(1-0),posicion(L-C-R),ColorFondo(1-0)
      $this->Ln(20); // Salto de línea
      $this->SetTextColor(50); //color

   }

   // Pie de página
   function Footer()
   {
      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); //pie de pagina(numero de pagina)
   }
}



$pdf = new PDF();
$pdf->AddPage("portrait"); /* aqui entran dos para parametros (horientazion,tamaño)V->portrait H->landscape tamaño (A3.A4.A5.letter.legal) */
$pdf->AliasNbPages(); //muestra la pagina / y total de paginas

$pdf->SetFont('Times', '', 12);
$pdf->SetDrawColor(163, 163, 163); //colorBorde


include_once "../conexion.php"; //llamamos a la conexion BD

      
      $sentencia = $conexion->prepare("SELECT pe.*, dpe.*
            FROM pedidos pe
            INNER JOIN detalle_pedidos dpe
            ON  pe.id=dpe.id_pedido
         ");

      
      $sentencia->execute();

      $resultado = $sentencia->get_result();
      


   /* TITULO DE LA TABLA 1 */
      //color
      $pdf->SetTextColor(0, 0, 0);
      $pdf->SetFillColor(254,173,38); //colorFondo
      $pdf->SetDrawColor(58,50,50); //colorBorde
      $pdf->Cell(0.01); // mover a la derecha
      $pdf->SetFont('Times', 'B', 12);
      $pdf->Cell(190, 7, utf8_decode("LISTADO DE PEDIDOS"), 1, 1, 'C',1);
      $pdf->Ln(0);

      /* CAMPOS DE LA TABLA 1 */
      //color
      $pdf->SetFillColor(254,173,38); //colorFondo
      $pdf->SetTextColor(0, 0, 0); //colorTexto
      $pdf->SetDrawColor(58,50,50); //colorBorde
      $pdf->SetFont('Times', 'B', 9);
      $pdf->Cell(10, 7, utf8_decode('N°'), 1, 0, 'C', 1);
      $pdf->Cell(30, 7, utf8_decode('FECHA'), 1, 0, 'C', 1);
      $pdf->Cell(35, 7, utf8_decode('NUMERO DE MESA'), 1, 0, 'C', 1);
      $pdf->Cell(45, 7, utf8_decode('MENU'), 1, 0, 'C', 1);
      $pdf->Cell(30, 7, utf8_decode('PRECIO UNIDAD'), 1, 0, 'C', 1);
      $pdf->Cell(25, 7, utf8_decode('CANTIDAD'), 1, 0, 'C', 1);
      $pdf->Cell(15, 7, utf8_decode('TOTAL'), 1, 1, 'C', 1);

      /* REGISTROS TABLA 1 */

      $i = 0;
   if(!empty($resultado)){
      foreach($resultado as $k=>$v){
         $i++;
         /* TABLA */
         $pdf->SetFont('Times', '', 9);
         $pdf->Cell(10, 7, utf8_decode($i), 1, 0, 'C', 0);
         $pdf->Cell(30, 7, utf8_decode($v['fecha']), 1, 0, 'C', 0);
         $pdf->Cell(35, 7, utf8_decode($v['num_mesa']), 1, 0, 'C', 0);
         $pdf->Cell(45, 7, utf8_decode($v['nombre']), 1, 0, 'C', 0);
         $pdf->Cell(30, 7, utf8_decode($v['precio']), 1, 0, 'C', 0);
         $pdf->Cell(25, 7, utf8_decode($v['cantidad']), 1, 0, 'C', 0);
         $pdf->Cell(15, 7, utf8_decode($v['total']), 1, 1, 'C', 0);

      };
   }else{
      $pdf->SetFont('Times', '', 9);
      $pdf->Cell(190, 7, utf8_decode("No se tiene registros"), 1, 1, 'C', 0);
   }

      $pdf->Ln(5);

date_default_timezone_set('America/La_Paz');
$fecha_actual=date('Y-m-d');
$nombre_archivo='reporte quipos de computo ue simon bolivar '.$fecha_actual.'.pdf';
$pdf->Output($nombre_archivo, 'I');//nombreDescarga, Visor(I->visualizar - D->descargar)

?>

