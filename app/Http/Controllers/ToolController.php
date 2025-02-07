<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Tool::paginate(10);
        return view('admin.tool.index',['tool'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tool.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name'=>'required',
            'quanity'=>'required|Integer',
            'image'=>'required|mimes:jpg,png,jpeg|max:5048'

        ]);
        $newImageName = time().'-'.$request->name.'.'.$request->image->extension();

        $request->image->move(public_path('images'),$newImageName);

        $tool = new Tool();
        $tool->name = $request->name;
        $tool->quanity = $request->quanity;
        $tool->image = $newImageName;
        $tool->save();

        return redirect()->route('tool.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {



       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tool = new Tool();
        $data = $tool->find($id);

        return view('admin.tool.edit',['data'=>$data]);
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
        $request->validate([
            'name'=>'required',
            'quanity'=>'required|Integer',
            'image'=>'mimes:jpg,png,jpeg|max:5048'

        ]);
        $tool = new Tool();
        $data = $tool->find($id);
        if($data){
            if($request->hasFile('image')){
                $file = $request->file('image');
                $filename = time().'-'.$request->name.'.'.$request->image->extension();
                $file->move(public_path('images'),$filename);
                $data->image = $filename;

            }
            $data->name = $request->name;
            $data->quanity = $request->quanity;
            $data->save();
        }
        return redirect()->route('tool.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        Tool::destroy($id);
        return response()->json([
            'status'  => true, // thành công
        ]);
    }
    public function search(Request $request) 
    {
        $searchTool = $request->get('search');
        if($searchTool){
        $tool = Tool::where('name','LIKE','%'. $searchTool . '%')->paginate(12);
        $totalResult = $tool->total();

        return view('admin.tool.search',['data'=>$tool,'keyword'=>$searchTool,'totalResult'=>$totalResult]);
        }else{

            return redirect()->route('tool.index');
        }
    }
}
