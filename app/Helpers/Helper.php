<?php

use App\Models\Varios\AreaUsuario;
use App\Models\Varios\VariableGlobal;

function variable_global($clave){
	if(VariableGlobal::select('valor')->where('clave', $clave)->count() > 0 ){
		return VariableGlobal::select('valor')->where('clave', $clave)->first()->valor;
	}else{
		return '';
	}
}

function obj_variable_global($clave){
	if(VariableGlobal::select('valor')->where('clave', $clave)->count() > 0 ){
		return VariableGlobal::where('clave', $clave)->first();
	}
	return false;
}

function soy_jefe($area, $user){
	if(evaluar_permisos(['ADM_SIS'], $user->tipos_usuario)){
		return true;
	}
	$ausu = AreaUsuario::where('area_id', $area->id)->where('user_id', $user->id)->where('es_jefe', true)->count();
	return boolval($ausu);
}

function evaluar_permisos($roles_necesarios, $roles_del_usuario){
	foreach($roles_del_usuario as $rol){
		if( in_array($rol->nombre, $roles_necesarios) ){
			return true;
		}
	}
	return false;
}

function pesosargentinos($importe){
	return number_format($importe, 2, ',', '.');
}

function dia_de_la_semana($dia_n){
	$dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
	return $dias[$dia_n];
}

function mes($mes_n){
	$meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
	return $meses[$mes_n];
}

function fecha_string_completa($fecha){
	$fecha_unix = strtotime($fecha);
	$dia_str = dia_de_la_semana(date('N', $fecha_unix));
	$dia = date('d', $fecha_unix);
	$mes = mes(date('n', $fecha_unix));
	return $dia_str . ' ' . $dia . ' de ' . $mes . ' del ' . date('Y', $fecha_unix);
}

function medio_de_pago($medio){
	$medios = [
		'E' => 'EFECTIVO',
		'T' => 'TARJETA',
		'D' => 'TRANSFERENCIA/DEPÓSITO',
	];
	return $medios[$medio];
}