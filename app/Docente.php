<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Docente extends Model
{

	use SoftDeletes;

	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function cursos()
    {
        return $this->belongsToMany('App\Curso');
    }

    public function realizadas()
    {
        return $this->hasMany('App\Realizada');
    }

    public function responden($calificacion, $id_curso, $id_pregunta) {
    	
    	/*
    	
    	Contar el numero de respuestas con dicha calificacion,
    	hechas a la pregunta id=$id_pregunta, tal que cada respuesta
    	apunta a una realizada con curso id=$id_curso y con este docente
    	
    	*/

    	$pregunta = Pregunta::find($id_pregunta);

    	$numero_respuestas = 0;
    	$respuestas = $pregunta->respuestas()->where('calificacion', $calificacion)->get();

    	foreach ($respuestas as $item) {
    		$coincide_curso = $item->realizada->curso_id == $id_curso;
			$coincide_docente = $item->realizada->docente_id == $this->id;
		    if ($coincide_curso && $coincide_docente) $numero_respuestas+=1;
    	}

		return $numero_respuestas;

    }

    public function porcentaje($calificacion, $id_curso, $id_pregunta) {
    
    	$pregunta = Pregunta::find($id_pregunta);	
    	
    	$total_respuestas = 0;
    	$respuestas = $pregunta->respuestas()->get();

    	foreach ($respuestas as $item) {
    		$coincide_curso = $item->realizada->curso_id == $id_curso;
			$coincide_docente = $item->realizada->docente_id == $this->id;
		    if ($coincide_curso && $coincide_docente) $total_respuestas+=1;
    	}
    	
    	$total_responden = $this->responden($calificacion, $id_curso, $id_pregunta);
        if($total_respuestas == 0){
            $porcentaje_respuestas = 0;
        }else{
        	$porcentaje_respuestas = (100/$total_respuestas)*$total_responden;
        }
        $porcentaje_respuestas = round($porcentaje_respuestas);
        $resul = "";
        if($porcentaje_respuestas != 0){
            $resul = $porcentaje_respuestas."%";
        }

    	return  $resul;//Redondeo del porcentaje

    }

    public function participantes($id_curso, $id_pregunta) {

    	/*

    	Se asume que cualquier pregunta que pertenezca a la misma encuesta
    	tiene el mismo numero de respuestas sin importar la calificacion.

    	*/
    
    	$pregunta = Pregunta::find($id_pregunta);	
    	
    	$total_respuestas = 0;
    	$respuestas = $pregunta->respuestas()->get();

    	foreach ($respuestas as $item) {
    		$coincide_curso = $item->realizada->curso_id == $id_curso;
			$coincide_docente = $item->realizada->docente_id == $this->id;
		    if ($coincide_curso && $coincide_docente) $total_respuestas+=1;
    	}
    	

    	return $total_respuestas;

    }

}
