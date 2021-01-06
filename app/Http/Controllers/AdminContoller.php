<?php

namespace App\Http\Controllers;
use App\User;
use Validator;
use Illuminate\Http\Request;

class AdminContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return $users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//
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
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|max:55',
            'lastname'  => 'required|max:55',
            'email'     => 'required|email',
            'image'     => 'required|image|mimes:jpeg,jpg,svg,png,gif|max:5048',
           
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);
        }

        // $old_file = User::findOrfail($id);
        // $old_path = $old_file->image;
        // unlink($old_path);
        $data = $request->all();  //dd is to dump and die to print
        $user = User::where('id' , $id)->first();       
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->email = $data['email'];
        $user->image = $data['image'];
        $user->update($data);
               
        // $todo->description = $data['description'];
         //or we can replace it with $todo->update($data); so houwe byefhamm la halo chou bade ghayyirr bala ma ektoub ktir w it works bala save bala shi ya3ne btekhlas l function fiya
         
       
        if($request->image)
        {

            $image = $request->image;
            $name = time().'_' . $image->getClientOriginalName();
            $filePath = $request->file('image')->storeAs('', $name, 'public');
            $user['image'] = $name;  
          
         }
         $user->save();
         
         return response()->json('Successfully updated');
     
    
    }
    


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::where('id' , $id)->delete();
    }
}
