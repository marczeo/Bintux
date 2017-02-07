<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ciclovia;
use App\Rel_cycling;
use App\Node;
use App\Repositories\CicloviaRepository;

class CicloviaController extends Controller
{
    /**
     * The type node associated with the controller.
     *
     * @var string
     */

    protected $typeNode = 'bikeway';

     /**
     * The ciclovia repository instance.
     *
     * @var CicloviaRepository
     */
    protected $cicloviasDAO;

    /**
     * Create a new controller instance.
     *
     * @param  CicloviaRepository  $ciclovias
     * @return void
     */
    public function __construct(CicloviaRepository $ciclovias)
    {
        //$this->middleware('auth');

        $this->cicloviasDAO = $ciclovias;
    }
	/**
     * Show the bikeway dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $ciclovias = $this->cicloviasDAO->getAllCiclovias()->get();
        return view('ciclovia.index',compact('ciclovias'));
    }

    /**
     * Show the bikeway dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    { 
        $ciclovias = $this->cicloviasDAO->getAllCiclovias()->select('encodepath', 'color')->get();
        return $ciclovias;
    }
    public function getAllJson()
    { 
        $ciclovias = $this->cicloviasDAO->getAllCiclovias()->select('id','name', 'color')->get();
        return $ciclovias->toJson();
    }

    /**
     * Add a bikeway to database
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$ciclovia = new ciclovia;
        $ciclovia->setColor();
        while ($this->cicloviasDAO->existColor($ciclovia->color)){
            $ciclovia->setColor();
        }

        $ciclovia->code="BW-666";
        $ciclovia->name=$request->name;
        $ciclovia->encodepath=$request->encodePath;
        $ciclovia->save();

         /*Limpieza de nodos*/
        preg_match_all('/\((.*?)\)/', $request->markerList, $nodes);
        $primeraPasada=true;
        foreach ($nodes[1] as $node) {
            $latLong=explode (",", $node);
            $newNode= new Node;
            $newNode->latitude=$latLong[0];
            $newNode->longitude=$latLong[1];
            $newNode->type=$this->typeNode;
            $newNode->save();

            if($primeraPasada)
            {
                $primeraPasada=false;
            }
            else{
                $rel_cycling->next_node_id=$newNode->id;
                $rel_cycling->save();
            }
            $rel_cycling = new Rel_cycling;
            $rel_cycling->cycling_route_id = $ciclovia->id;
            $rel_cycling->start_node_id=$newNode->id;

        }
        $rel_cycling->save();

        return redirect('/ciclovia');
    }

    /**
     * Show the form to new bikeway
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ciclovia.create');
    }

    /**
     * Save changes bikeway.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        return view('ciclovia.update');
    }

    /**
     * Delete bikeway from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return view('ciclovia.destroy');
    }

    /**
     * Show a bikeway.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('ciclovia.destroy');
    }

    /**
     * Show the bikeway form to edit.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('ciclovia.edit');
    }
}
