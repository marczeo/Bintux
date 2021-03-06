<?php

namespace App\Http\Controllers;
use App\Repositories\BusRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Repositories\RouteRepository;
use App\Rel_concessionaire;
use App\Bus;

class BusController extends Controller
{
    /**
     * The bus repository instance.
     *
     * @var busesRepository
     */
    protected $busesDAO;

    /**
     * Create a new controller instance.
     *
     * @param  busesRepository  $busesRepository
     * @return void
     */
    public function __construct(BusRepository $busesRepository, Request $request)
    {

        //Cuando la petición es desde API
        if($request->route()){
        if($request->route()->getPrefix()=="api"){
            $this->middleware('jwt.auth',['only'=>['getAllJson']]);
        }
        else{#Peticion desde web
            $this->middleware('auth');
            $this->middleware('concessionaire',['only' => [
                'index',
                'destroy',
            ]]);

        }}


        //Use DAO
        $this->busesDAO = $busesRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('bus.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $routeDAO = new RouteRepository();
        $rutas = json_decode(json_encode($routeDAO->getAllRoutes(null)))->data;
        return view('bus.create',compact('rutas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->busesDAO->createBus($request);
        return redirect('/bus');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bus=Bus::findOrFail($id);
        $bus->delete();
        return redirect('/bus');
    }

    public function getAllJson()
    {
        $buses = $this->busesDAO->getAllBuses();
        return $buses;
    }

    /**
     * Change status -> enabled or disabled a bus
     *
     * @param \Illuminate\Http\Request  $request
     * @return json
    */
    public function changeStatus(Request $request, Bus $bus)
    {
        $id_Route = Rel_concessionaire::where('concessionaire_id',$bus->concessionaire_id)->get();

	$output = array($bus->concessionaire->id, $bus->concessionaire->rel_concessionaire->route->id);

	$jsondata = json_encode($output);

	$command = escapeshellcmd('/usr/bin/python /var/www/vhosts/biintux.me/httpdocs/Biintux/python/RouteChangedController.py'). ' ' . escapeshellarg( $jsondata );

	$output = shell_exec($command);

	echo $output;

        $result=$this->busesDAO->changeStatus($bus, $request['estatus']);

        return $result;
    }
}
