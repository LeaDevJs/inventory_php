<?php
//INSTANCIA para conectarnos a database
//se crea una funcion para que cuando llamemos al archivo este se ejecute la funcion

function conexion(){
    $pdo= new PDO("mysql:host=localhost;dbname=inventario","root","");
    return $pdo;
}
//consultas↓ siempre comillas simples adentro
//$pdo->query("INSERT INTO categoria(categoria_nombre,categoria_ubicacion) VALUES('prueba','texto ubicacion')");

//verificar datos
//si hay error devuelve true
function verificar_datos($filtro,$cadena){
    if(preg_match("/^".$filtro."$/",$cadena)){
        return false;
    }else{
        return true;
    }
}
/*ejemplo 
$nombre="carlos";
if(verificar_datos("[a-zA-Z]{6,10}",$nombre)){
    echo "los datos no coinciden";
}
*/

//limpiar cadenas de texto
function limpiar_cadena($cadena){
    //trim elimina espacios en blanco
    $cadena=trim($cadena);
    //stricpslashes quita las barras /
    $cadena=stripcslashes($cadena);
    //reemplaza un texto por patrones
    //elimina etiquetas javascript e inyeccion sql
    $cadena=str_ireplace("<script>","",$cadena);
    $cadena=str_ireplace("</script>","",$cadena);
    $cadena=str_ireplace("<script src", "", $cadena);
	$cadena=str_ireplace("<script type=", "", $cadena);
	$cadena=str_ireplace("SELECT * FROM", "", $cadena);
	$cadena=str_ireplace("DELETE FROM", "", $cadena);
	$cadena=str_ireplace("INSERT INTO", "", $cadena);
	$cadena=str_ireplace("DROP TABLE", "", $cadena);
	$cadena=str_ireplace("DROP DATABASE", "", $cadena);
	$cadena=str_ireplace("TRUNCATE TABLE", "", $cadena);
	$cadena=str_ireplace("SHOW TABLES;", "", $cadena);
	$cadena=str_ireplace("SHOW DATABASES;", "", $cadena);
	$cadena=str_ireplace("<?php", "", $cadena);
	$cadena=str_ireplace("?>", "", $cadena);
	$cadena=str_ireplace("--", "", $cadena);
	$cadena=str_ireplace("^", "", $cadena);
	$cadena=str_ireplace("<", "", $cadena);
	$cadena=str_ireplace("[", "", $cadena);
	$cadena=str_ireplace("]", "", $cadena);
	$cadena=str_ireplace("==", "", $cadena);
	$cadena=str_ireplace(";", "", $cadena);
	$cadena=str_ireplace("::", "", $cadena);
	$cadena=trim($cadena);
	$cadena=stripslashes($cadena);
	return $cadena;
}
//renombrar fotos
function renombrar_fotos($nombre){
    $nombre=str_ireplace(" ", "_", $nombre);
    $nombre=str_ireplace("/", "_", $nombre);
    $nombre=str_ireplace("#", "_", $nombre);
    $nombre=str_ireplace("-", "_", $nombre);
    $nombre=str_ireplace("$", "_", $nombre);
    $nombre=str_ireplace(".", "_", $nombre);
    $nombre=str_ireplace(",", "_", $nombre);
    //generar un numero aleatorio por si tienen el mismo nombre
    $nombre=$nombre."_".rand(0,100);
    return $nombre;
}

function paginador_tablas($pagina,$Npaginas,$url,$botones){
    $tabla='<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

    if($pagina<=1){
        $tabla.='
        <a class="pagination-previous is-disabled" disabled >Anterior</a>
        <ul class="pagination-list">';
    }else{
        $tabla.='
        <a class="pagination-previous" href="'.$url.($pagina-1).'" >Anterior</a>
        <ul class="pagination-list">
            <li><a class="pagination-link" href="'.$url.'1">1</a></li>
            <li><span class="pagination-ellipsis">&hellip;</span></li>
        ';
    }

    $ci=0;
    for($i=$pagina; $i<=$Npaginas; $i++){
        if($ci>=$botones){
            break;
        }
        if($pagina==$i){
            $tabla.='<li><a class="pagination-link is-current" href="'.$url.$i.'">'.$i.'</a></li>';
        }else{
            $tabla.='<li><a class="pagination-link" href="'.$url.$i.'">'.$i.'</a></li>';
        }
        $ci++;
    }

    if($pagina==$Npaginas){
        $tabla.='
        </ul>
        <a class="pagination-next is-disabled" disabled >Siguiente</a>
        ';
    }else{
        $tabla.='
            <li><span class="pagination-ellipsis">&hellip;</span></li>
            <li><a class="pagination-link" href="'.$url.$Npaginas.'">'.$Npaginas.'</a></li>
        </ul>
        <a class="pagination-next" href="'.$url.($pagina+1).'" >Siguiente</a>
        ';
    }

    $tabla.='</nav>';
    return $tabla;
}
