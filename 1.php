<?php
//update listado_licitaciones as a left join (SELECT codigo_externo, max(fecha_creacion) as MAXFECHA FROM `listado_licitaciones` group by codigo_externo) as b on a.codigo_externo=b.codigo_externo set antigua=1 where a.fecha_creacion<>b.MAXFECHA
$dias=$argv[1];

if (ob_get_level() == 0)
{
    ob_start();
} 
$dbhost = 'localhost';
$dbname = 'asesori1_mercado_publico';
$dbuser = 'asesori1_cesnar';
$dbpass = 'YvKC1ely)E^D';
$st=mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if ($st){
mysqli_set_charset( $st, 'utf8');
mysqli_select_db($st, $dbname);


$fecha_inicial=date("Y-m-d", strtotime('-'.$dias.' days'));
for ($i = 0; $i <= 19; $i++) {
$fecha=date("dmY", strtotime($fecha_inicial.' + '.$i.' days'));
$url="";
$url="http://api.mercadopublico.cl/servicios/v1/publico/licitaciones.json?fecha=".$fecha."&ticket=F8537A18-6766-4DEF-9E59-426B4FEE2844";

$checkCntent=file_get_contents($url);


$data = json_decode($checkCntent, true );
switch(json_last_error()) 
    {
        case JSON_ERROR_NONE:
            $error='';
            goto Continuar;
        case JSON_ERROR_DEPTH:
            $error= '{'.$url.' - Excedido tamaño máximo de la pila}';
            goto Siguiente;
        case JSON_ERROR_STATE_MISMATCH:
            $error= '{'.$url.' - Desbordamiento de buffer o los modos no coinciden}';
            goto Siguiente;
        case JSON_ERROR_CTRL_CHAR:
            $error= '{'.$url.' - Encontrado carácter de control no esperado}';
            goto Siguiente;
        case JSON_ERROR_SYNTAX:
            $error= '{'.$url.' - Error de sintaxis, JSON mal formado}';
            goto Siguiente;
        case JSON_ERROR_UTF8:
            $error= '{'.$url.' - Caracteres UTF-8 malformados, posiblemente codificados de forma incorrecta}';
            goto Siguiente;
        default:
            $error= '{'.$url.' - Error desconocido}';
            goto Siguiente;
    }

Continuar:

$num=0;
foreach($data['Listado'] as $linea){
$num=$num+1;
$limpia = mysqli_query($st, 'delete from listado_licitaciones where fecha_creacion=\''.date("Y-m-d", strtotime($fecha_inicial.' + '.$i.' days')).'\' and codigo_externo= \''.$linea['CodigoExterno'].'\';');
$reset_id = mysqli_query($st, 'ALTER TABLE listado_licitaciones AUTO_INCREMENT=1;');
$result = mysqli_query($st, 'insert into listado_licitaciones (fecha_creacion, numerador, codigo_externo, nombre, codigo_estado, fecha_cierre, hora_cierre) values (\''.date("Y-m-d", strtotime($fecha_inicial.' + '.$i.' days')).'\','.$num.', \''.$linea['CodigoExterno'].'\', \''.$linea['Nombre'].'\', '.$linea['CodigoEstado'].', \''.substr($linea['FechaCierre'],0,10).'\' ,\''.substr($linea['FechaCierre'],11,8).'\' )');
}

Siguiente:
$CantLic=isset($data['Cantidad']) ? $data['Cantidad'] :'0';
echo '['.date("Y-m-d", strtotime($fecha_inicial.' + '.$i.' days')).' -> '.$CantLic.' filas disponibles y '.$num.' filas almacenadas]'.$error.'<br>';
$num=0;
ob_flush();
flush();
}
}
else{
echo "no conectado;\n";
}

ob_end_flush();


?>
