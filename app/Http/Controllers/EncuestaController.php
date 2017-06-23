<?php

namespace App\Http\Controllers;

use App\Encuesta;
use App\Http\Traits\Utilidades;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;

use Carbon\Carbon;
use App\Pregunta;
use Validator;

class EncuestaController extends Controller
{

    use Utilidades;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $this->authorize('es_admin', User::class);
        
        $routeEntera = Route::getFacadeRoot()->current()->uri(); //No esta en buscar
        $routeSeparada = explode('/', $routeEntera);

        if (last($routeSeparada)!='buscar') {
            $route = implode('/', $routeSeparada);
            $route = $route.'/buscar';
        } else {
            $route = implode('/', $routeSeparada);
        }
                
        $encuestas = Encuesta::all();

        $h1 = "Encuestas en el sistema";
        
        $title = "ID, Fecha inicial, Fecha limite, Asunto o Descripcion"; //Para el tooltrip

        $c = "";

        return view(
            'admin.encuestas',
            ['encuestas' => $encuestas, 'route' => $route,
            'title' => $title, 'c' => $c, 'h1' => $h1]);
    
    }

    public function buscar(Request $request)
    {

        $this->authorize('es_admin', User::class);
        
        $route = Route::getFacadeRoot()->current()->uri(); //Ya esta en buscar
        
        $query = $request->get('q');

        if (!$query) return $this->index();

        // $encuestas1 = collect([]);
        // $encuestas2 = collect([]);
        // $encuestas3 = collect([]);

        // if (is_numeric($query)) $encuestas1 = Encuesta::where('id', $query)->get();
        
        // if ($this->es_fecha($query)) {

        //     $encuestas2 = Encuesta::where('inicio', $query)->get();
        //     $encuestas3 = Encuesta::where('vence', $query)->get();

        // }

        // $encuestas4 = Encuesta::where('asunto', 'like','%'.$query.'%')->get();
        // $encuestas5 = Encuesta::where('descripcion', 'like','%'.$query.'%')->get();

        // $encuestas =
        // $encuestas5->merge(
        //     $encuestas4->merge(
        //         $encuestas3->merge(
        //             $encuestas2->merge(
        //                 $encuestas1))));

        $encuestas = collect([]);

        if (is_numeric($query)) {

            $encuestas = Encuesta::where('id', $query)->get();
        
        } else if ($this->es_fecha($query)) {

            $encuestas = Encuesta::where('inicio', $query)
            ->orWhere('vence', $query)->get();

        } else {

            $encuestas = Encuesta::where('asunto', 'like', '%'.$query.'%')
            ->orWhere('descripcion', 'like', '%'.$query.'%')->get();

        }

        // dd(DB::getQueryLog());

        $h1 = "Se encontraron ".$encuestas->count()." encuestas";

        if ($encuestas->count()==0) $h1 = "No se encontraron encuestas";

        $title = "ID, Fecha inicial, Fecha limite, Asunto o Descripcion"; //Para el tooltrip

        $c = $request->consulta;

        return view(
            'admin.encuestas',
            ['encuestas' => $encuestas, 'route' => $route,
            'title' => $title, 'c' => $c, 'h1' => $h1]);
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('es_admin', User::class);
        return view('encuesta.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('es_admin', User::class);

        $inicio = Carbon::now()->toDateString();  // 1975-12-25
        $arreglo = explode("/", $request->get('vence'));

        $vence = Carbon::createFromDate($arreglo[2], $arreglo[1], $arreglo[0])->toDateString();
        $messages = [ 'after' => 'La fecha limite debe ser posterior al dia de hoy'];

        $validator = Validator::make($request->all(),[
                'vence' => 'required',
           ],$messages
        );

        if($inicio >= $vence){
            $validator->errors()->add('vence', 'La fecha limite debe ser posterior al dia de hoy');
            return view('encuesta.create')->withErrors($validator, 'vence');
        }
        $data = [
            'asunto'=>$request->get('asunto'),
            'descripcion'=>$request->get('descripcion'),
            'inicio'=>$inicio,
            'vence'=>$vence,
        ];

        $ultima_encuesta = Encuesta::all()->sortBy('updated_at')->last();
        
        $encuesta = Encuesta::create($data);

        if ($ultima_encuesta) {

        	$ultimas_preguntas = $ultima_encuesta->preguntas()->get()->toArray();

	        foreach ($ultimas_preguntas as $u_p) {
	            $pregunta = new Pregunta;
	            $n = $encuesta->preguntas()->count();
	            $pregunta->encuesta()->associate($encuesta);
	            $pregunta->numero = $n+1;
	            $pregunta->enunciado = $u_p['enunciado'];
	            $pregunta->save();
	        }

	    }

        return $this->show($encuesta->id);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Encuesta  $encuesta
     * @return \Illuminate\Http\Response
     */
    public function show($id){

        $encuesta = Encuesta::findOrFail($id);
        // $pregunta = $encuesta->preguntas();
        // $preguntas = Pregunta::with(['encuesta' => function($query){
        //         $query->where('encuesta_id');
        //     }])->get();
        // $preguntas = Pregunta::with('encuesta')->find($id);
        $preguntas = Pregunta::where('encuesta_id',$id)->get();
        $cant = $preguntas->count();
        $encuesta->vence = $this->uyDateFormat($encuesta->vence);
        $encuesta->inicio = $this->uyDateFormat($encuesta->inicio);
        return view('encuesta.show', ['encuesta' => $encuesta,'preguntas'=>$preguntas,'cant'=>$cant]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Encuesta  $encuesta
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('es_admin', User::class);
        //return view('encuesta.edit', compact($encuesta));
        $encuesta = Encuesta::findOrFail($id);
        $encuesta->vence = $this->uyDateFormat($encuesta->vence);

        
        // return View::make('encuesta.edit', ['id'=>$encuesta->id])->with('encuesta', $encuesta);        
        return view('encuesta.edit', ['id'=>$encuesta->id])->with('encuesta', $encuesta);        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Encuesta  $encuesta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->flash());
        $this->authorize('es_admin', User::class);

        $encuesta = Encuesta::findOrFail($id);


        $inicio = Carbon::now()->toDateString();  // 1975-12-25
        $arreglo = explode("/", $request->get('vence'));
        $vence = Carbon::createFromDate($arreglo[2], $arreglo[1], $arreglo[0])->toDateString();
        


        $messages = [ 'after' => 'La fecha limite debe ser posterior al dia de hoy'];
        $validator = Validator::make($request->all(),[
                'vence' => 'required',
           ],$messages
        );        
        if($inicio >= $vence){
            $validator->errors()->add('vence', 'La fecha limite debe ser posterior al dia de hoy');
            //dd(old());
            $encuesta->vence = $this->uyDateFormat($encuesta->vence);
            return view('encuesta.edit',['id'=>$encuesta->id])->with('encuesta', $encuesta)->withErrors($validator, 'vence')->withOldFormData(Input::all());
        }


        if($validator->fails()){
            // return view('encuesta.edit', ['id'=>$encuesta->id])->withErrors($validator, 'vence');
            //dd($validator->errors());
            return $this->edit($id)->withErrors($validator,'vence');
        }


        $encuesta->asunto = $request->get('asunto');
        $encuesta->descripcion = $request->get('descripcion');
        $encuesta->vence = $vence;
        $encuesta->save();

        $request->session()->flash('message','Encuesta actualizada exitosamente');
        return $this->show($id);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Encuesta  $encuesta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('es_admin', User::class);
        //$flight->history()->forceDelete();
        // $encuesta = Encuesta::findOrFail($id);
        // $pre = $encuesta->preguntas()->get();
        // return view('encuesta.help',['pregunta'=>$pre]);
        
        $encuesta = Encuesta::findOrFail($id);
        //if (Realizada::where('encuesta_id', $id)->get()->isNotEmpty()) {
        //dd($encuesta->realizadas()->get()->isNotEmpty());
        if($encuesta->realizadas()->get()->isNotEmpty()){
            $request->session()->flash('error', 'Encuesta no se puede eliminar porque alguien ya la completo');
            return $this->index();
        }
        $encuesta->preguntas()->delete();
        $encuesta->delete();
        // parent::delete();
        $request->session()->flash('message', 'Encuesta borrada exitosamente!');
        return $this->index();

    }
}
