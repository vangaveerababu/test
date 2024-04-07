<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use DataTables;

use App\Jobs\NewUserWelcomeMail;
use App\Jobs\WelcomeEmailJob;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = UserModel::select('id','name','email')->orderBy('id','DESC')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" class="btn btn-primary btn-sm">View</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users');
    }

    public function add(){

     return view('adduser');

    }

    public function create(Request $request)
    {
        // validate your request
 
        // create a user
        $user = UserModel::create([
            "name"=> $request->name,
            "email" => $request->email
            // other fields
        ]);
 
        // dispatch your queue job
       // dispatch(new NewUserWelcomeMail($user));

        WelcomeEmailJob::dispatch($request->email,$request->name);
 
        // return your response
    }

    public function save(Request $request){



         $model = new UserModel();
        
       //   $model->email = $request->get('firstname');
       //   $model->name =  $request->get('lastname');
       //   $model->save();

          UserModel::create($request->post());
          WelcomeEmailJob::dispatch($request->email,$request->name);

          return redirect()->route('users.index');




    }

    public function sent(){

          
           $data = array(

            'subject' =>'my name is veeru',
          //  'body'  => "this is body",
            'path'  => '/public/itsolutionstuff.pdf'
           );

           Mail::to('veerababuvanga@gmail.com')->send(new WelcomeMail($data));
        
        //  WelcomeEmailJob::dispatch($email,$name);

          return redirect()->route('users.index');




    }

    public function sendmail()

    {
     $data = array('name'=>"Virat Gandhi");
   
      Mail::send(['text'=>'mail'], $data, function($message) {
         $message->to('veerababuvanga@gmail.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
         $message->from('xyz@gmail.com','Virat Gandhi');
      });
      echo "Basic Email Sent. Check your inbox.";


    }




}