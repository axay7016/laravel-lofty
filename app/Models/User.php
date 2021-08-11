<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spark\Billable;

class User extends Authenticatable
{
    use Billable, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //view and search user
    public function viewUser($search,$limit){
       $userdata = User::where('is_delete','==',0)
                        ->where(function($query) use ($search) {
                            $query->where('name', 'LIKE', '%'.$search.'%')
                                  ->orwhere('email', 'LIKE', '%'.$search.'%');
                        })
                        ->paginate($limit);
       return $userdata;
    }
    
    //adduser
    public function addUser($data){
        $adduser = User::insert($data);
        return $adduser;
    }

    //update user
    public function updateUser($id,$data){
        $updateuser = User::where('id',$id)->update($data);
        return $updateuser;
    }


}
