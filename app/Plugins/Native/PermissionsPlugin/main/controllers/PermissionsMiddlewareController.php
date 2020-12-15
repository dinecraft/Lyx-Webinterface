<?php
namespace App\Plugins\Native\PermissionsPlugin\Main\Controllers;

use App\Models\Permissions;
use App\Models\PermissionUsers;

class permissionsMiddlewareController
{
    public function __constructor()
    {
        //
    }

    // [Function] To Check if sb has a Permission. Returns "yes" or "no".
    public function checkForPermission($permName, $userid, $userlevel)
    {
        $checkIfPermissionExists = Permissions::where("permName" ,$permName)->count();  // [Check]  If Permission Exists generelly.
        if($checkIfPermissionExists <= 0)  // [When: Not Exists]  Return Error.
        {
            return ["type" => "err", "content" => "Not existent Permission requested."];  // [return]  Error.
        }

         // [Check]  If User has the Permission.
        $checkIfUserPermission = PermissionUsers::where("userid", $userid)->where("permName", $permName)->where("value", "yes")->count();
        $checkIfUserLevel = Permissions::where("permName", $permName)->first();
        $checkIfUserLevel = $checkIfUserLevel["permLevel"];
        if($checkIfUserPermission >= 1 || $userlevel >= $checkIfUserLevel)  // [When: User Have Permission or Has Role Level]  Return 'yes'.
        {
            return ["type" => "return", "content" => "yes"];  // [Return]  yes.
        }
        else
        {
            return ["type" => "return", "content" => "no"];  // [Return]  False, Permission not allowed.
        }
    }
}
