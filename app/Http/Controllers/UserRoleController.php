<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleController extends Controller
{

        //view user in dashboard and searching
        function viewUsers(){
            $user = new User();
            $data =$user->viewUser('',5);
            return view('user.users',['data'=>$data]);         
        }
        //use that ajax call 
        function SviewUsers(Request $req){
            $user = new User();
            if($req->ajax()){
                $search = $_GET['search'];
                $limit = $_GET['limit'];
                $data = $user->viewUser($search,$limit);
                return view('user.userdata',['data'=>$data])->render();
            }
        }
    //add role and update role
    function addRole(Request $req){
        $user = new User();
        //update role
        if(isset($req->roleid)){
            $id= $req->roleid;
            $data = [
                'user_role'=>$req->userrole,
                'name'=>$req->username,
                'email'=>$req->email,
            ];
            $user->updateUser($id,$data);
        }
        else{//add role

            $req->validate([
                'email' => 'required|string|email|max:255|unique:users'
            ]);
                $data = [
                    'name'=>$req->username,
                    'email'=>$req->email,
                    'password'=>Hash::make($req->password),
                    'user_role'=>$req->userrole
                ];
                $user->addUser($data); 
        }
        return redirect('users');
    }
    //delete role or user
    function deleteRole($id){
        $user = new User();
        $data = ['is_delete'=>1];
        $user->updateUser($id,$data);
        return redirect('users');
    }
    //view user-data in update form
    function showUpdateRole($id){
        $data = User::find($id);    
        return view('user.userRole',['data'=>$data]);
    }

}