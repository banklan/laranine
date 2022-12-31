<?php

namespace App\Http\Controllers;

use App\User;
use App\Service;
use App\Portfolio;
use App\PortfolioFile;
use Illuminate\Http\Request;
use App\UserEmailConfirmation;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function confirmEmail(Request $request){
        $token = $request->token;

        $conf = UserEmailConfirmation::where('token', $token)->first();
        if($conf){
            if($conf->is_confirmed){
                return response()->json(['message' => 'confirmed already'], 202);
            }else{
                $user = User::findOrFail($conf->user_id);
                $conf->update([
                    $conf->is_confirmed = true
                ]);

                 // get user and update status
                // $user = User::findOrFail($conf->user_id);
                $user->update([
                    $user->status = 1,
                    $user->is_verified = 1
                ]);

                return response()->json(['message' => 'Verified'], 200);
            }
        }
    }

    public function confirmCurrentPassword(Request $request){
        $user = auth('api')->user();
        $current = $user->password;
        $check = Hash::check($request->password, $current);

        return response()->json($check, 200);
    }

    public function updateUserProfilePswd(Request $request){
        $this->validate($request, [
            'password' => 'required|min:5|max:20|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = auth('api')->user();
        $newPswd = $request->password;

        $user->update([
            $user->password = Hash::make($newPswd)
        ]);

        return response()->json(['message' => 'Password changed successfully'], 201);
    }

    public function updateUserProfile(Request $request){
        $this->validate($request, [
            'user.first_name' => 'required|min:2|max:40',
            'user.last_name' => 'required|min:2|max:40',
            'user.phone' => 'required|max:14',
        ]);

        $user = auth('api')->user();
        $user->update([
            $user->first_name = $request->user['first_name'],
            $user->last_name = $request->user['last_name'],
            $user->phone = $request->user['phone'],
        ]);
        return response()->json($user, 200);
    }

    public function deleteUserAccount(Request $request){
        $user = auth('api')->user();
        $service = Service::where('user_id', $user->id)->first();
        $portfolio = Portfolio::where('service_id'. $service->id)->first();
        $files = PortfolioFile::where('portfolio_id', $portfolio->id)->get();

        // unlink pf files and pf
        foreach ($files as $file){
            $filePath = public_path('/assets/portfolios/'.$file->file);
            if(file_exists($filePath)){
                unlink($filePath);
            }
            $file->delete();
        }
        $portfolio->delete();

        // unlink service image and service
        $service_img = public_path('/assets/portfolios/'.$service->image);
        if(file_exists($service_img)){
            unlink($service_img);
        }
        $service->delete();

        // delete user and profile pic
        $profile_pic = public_path('/assets/profile/'.$user->picture);
        if(file_exists($profile_pic)){
            unlink($profile_pic);
        }
        $user->delete();

        return response()->json(['message' => 'User deleted'], 200);
    }

    public function updateprofilePicture(Request $request){
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        $file = $request->image;

        if($file){
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz';
            $ext = $file->getClientOriginalExtension();
            $filename = substr(str_shuffle($pool), 0, 7).".".$ext;

            $user = auth('api')->user();
            // unlink old pic if exists
            $old_file = $user->public_id;
            if($old_file){
                \Cloudinary::destroy($old_file);
            }
            $result = $file->storeOnCloudinary('hozumart/profiles');
            $publicId = $result->getPublicId();
            $path = $result->getPath();
            $user->update([
                $user->public_id = $publicId,
                $user->picture = $path
            ]);

            return response()->json($user, 200);
        }

    }
}
