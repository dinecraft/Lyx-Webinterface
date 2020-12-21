<?php

namespace App\Http\Controllers\SetupInstallation;
use DB;
use Session;
Use App;
use Config;
use Artisan;
use File;
use Hash;
use Throwable;

use App\User;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetupController extends Controller
{
    //constructor
    public function __construct()
    {
        //$this->middleware('isInstalled');
        $this->middleware('LanguageMiddleware'); //lang
    }


    //function to show the Installation Site
    public function InstallStepOne()
    {
        $start_exec_time = round(microtime(true) *1000);
         $v = view("setup.step1");
        $end_exec_time = round(microtime(true) *1000) - $start_exec_time;
        //dd($end_exec_time);
        return $v;
    }

    //function to post language select
    public function InstallPostOne(Request $request)
    {
        Session(["main_lang" => Request()->input("lang")]);
        return redirect("/install/setup/2");
    }

    //function accept
    public function InstallStepTwo(Request $request)
    {
        return view("setup.step2");
    }

    //function show set database
    public function InstallStepThree()
    {
        return view("setup.step3");
    }

    //function set database
    public function InstallPostThree()
    {
        $validatedData = Request()->validate([
            'mysql_host' => 'required|max:255',
            'mysql_port' => 'required|max:255',
            'mysql_db' => 'required|max:255',
            'mysql_user' => 'required|max:255',
            'mysql_pw' => 'max:255'
        ]);

        $host = Request("mysql_host");
        $port = Request("mysql_port");
        $db = Request("mysql_db");
        $username = Request("mysql_user");
        $password = Request("mysql_pw");

        //config generieren
        $plugin = "App\Plugins\Native\SetupPlugin\handler";
        $destroyer = new $plugin(); //call handler of the Plugin
        $result = $destroyer->callDirect( "*autoDestroyController@createConfig", ["host" => $host, "port" => $port, "db" => $db, "username" => $username, "password" => $password]);
        if ($result != "done")
        {
            return view("setup.step3")->withErrors(["Can't connect to Database."]);
        }
        //$destroyer->createConfig($host, $port, $db, $username, $password);
        return redirect("/install/setup/4");
    }

    public function InstallStepFour()
    {
        $err = "none";
        try {
            DB::connection()->getPdo();
        }
        catch (\Illuminate\Database\QueryException | \PDOException | \Exception $e)
        {
            return view("setup.step3")->withErrors(["Can't connect to Database."]);
        }
        //if DB connected
        return view("setup.step4");
    }

    //function set admin account
    public function InstallPostFour()
    {
        $validatedData = Request()->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'password' => 'required|max:255|min:6',
            'repeat' => 'required|max:255|min:6'
        ]);

        $insert = new User();
        $insert->name = Request()->input("name");
        $insert->email = Request()->input("email");
        $insert->password = Hash::make(Request()->input("password"));
        $insert->role = 5;
        $insert->active = 1;
        $insert->save();
        return response("done");
    }
}
