<?php
namespace App\Plugins\Native\SetupPlugin\Main\Controllers;

use DB;
use Session;
Use App;
use Config;
use Artisan;
use File;

class autoDestroyController
{
    public function createConfig($data)
    {
        $host = $data["host"];
        $port = $data["port"];
        $db = $data["db"];
        $username = $data["username"];
        $password = $data["password"];

        //mysql disconnecten (auch wenn es bis dato noch gar keine verbindung gibt)
        DB::disconnect();
        //cache clearen
        Artisan::call('config:clear');

        //GGF password parsen , also wenn mysql kein password schutz hat, einfach n leeren string übergeben
        if($password) { config(["database.connections.mysql.password" => $password]);} else { config(["database.connections.mysql.password" => ""]); }

        config(["database.connections.mysql.host" => $host]);
        config(["database.connections.mysql.port" => $port]);
        config(["database.connections.mysql.database" => $db]);
        config(["database.connections.mysql.username" => $username]);

        //vorhandene Config einlesen
        $path = base_path() . "/config/database.php";
        $content = File::get($path);
        //entsprechend replacen
        $newcontent = str_replace("%%host%%", $host, $content);
        $newcontent = str_replace("%%port%%", $port, $newcontent);
        $newcontent = str_replace("%%db%%", $db, $newcontent);
        $newcontent = str_replace("%%user%%", $username, $newcontent);
        if($password) { $newcontent = str_replace("%%pw%%", $password, $newcontent); } else { $newcontent = str_replace("%%pw%%", '', $newcontent); }
        //datei Löschen und neu erstellen
        File::delete($path);
        File::put($path, $newcontent);

        Artisan::call('config:clear');
        $err = "none";

        try
        {
            //mysql neu connecten
            DB::reconnect();
            //tabellen migrieren
            Artisan::call('migrate');
        }
        catch (\Illuminate\Database\QueryException | \PDOException | \Exception $e)
        {
            //wenn ein Fehler auftritt wird wieder alles resettet
            File::delete($path);
            File::put($path, $content);
            $err = "error";
            return $e;

        }

        //if($err == "none")
        //{
            //selbst zerstören (diese Datei)
           // $selfile = base_path()."/app/Plugins/Native/SetupPlugin/main/controllers/autoDestroyController.php";
            //File::delete($selfile);
        //}

        return "done";
    }
}

/**..
            'host' => '%%host%%',
            'port' => '%%port%%',
            'database' => '%%db%%',
            'username' => '%%user%%',
            'password' => '%%pw%%',
*/
