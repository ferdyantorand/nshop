<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 13/02/2018
 * Time: 11:34
 */

namespace App\Transformer;


use App\Models\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user){

        try{
            $createdDate = Carbon::parse($user->created_at)->format('d M Y');

            $action = "<a class='btn btn-xs btn-info' href='users/edit/".$user->id."' data-toggle='tooltip' data-placement='top'><i class='icon-mode_edit'></i></a>";
            $action .= "<a class='delete-modal btn btn-xs btn-danger' data-id='". $user->id ."' ><i class='icon-delete'></i></a>";

            return[
                'email'             => $user->email,
                'name'              => $user->first_name." ".$user->last_name,
                'phone'             => $user->phone,
                'status'            => $user->status->description,
                'created_at'        => $createdDate,
                'action'            => $action
            ];
        }
        catch (\Exception $exception){
            error_log($exception);
        }
    }
}