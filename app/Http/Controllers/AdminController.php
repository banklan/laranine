<?php

namespace App\Http\Controllers;

use App\User;
use App\Admin;
use App\Review;
use App\Service;
use App\Portfolio;
use App\TestImage;
use App\PortfolioFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function getPaginatedUsers(){
        $users = User::latest()->paginate(20);

        return response()->json($users, 200);
    }

    public function searchForUsers(Request $request){
        $q = $request->q;
        $users = User::where('first_name', 'LIKE', "%".$q."%")
                        ->orWhere('last_name', 'LIKE', "%".$q."%")
                        ->orWhere('email', 'LIKE', "%".$q."%")
                        ->orWhere('phone', 'LIKE', "%".$q."%")
                        ->get();

        return response()->json($users, 200);
    }

    public function getAllSuperUsers(){
        $users = Admin::all();

        return response()->json($users, 200);
    }

    public function uploadTestImage(Request $request){
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        $file = $request->image;
        if($file){
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz';
            $ext = $file->getClientOriginalExtension();
            $filename = substr(str_shuffle($pool), 0, 8).".".$ext;
            // $response = cloudinary()->upload($file->getRealPath())->getSecurePath();
            $response = $request->file('image')->storeOnCloudinaryAs('hozumart/test');
            $test = new TestImage;
            $test->image_url = $response->getPath();
            $test->save();
            return response()->json($test, 200);
        }
    }

    public function getSuperUser($id){
        $admin = Admin::findOrFail($id);

        return response()->json($admin, 200);
    }

    public function updateSuperUserProfPic(Request $request, $id){
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        $admin = Admin::findOrFail($id);
        $pic = $admin->picture;
        if($pic){
            Cloudinary::destroy($admin->public_id);
        }
        $file = $request->image;
        if($file){
            $response = $request->file('image')->storeOnCloudinaryAs('hozumart/admin/profiles');
            $admin->update([
                $admin->picture = $response->getPath(),
                $admin->public_id = $response->getPublicId()
            ]);
        }
        return response()->json($admin->picture, 200);
    }

    public function updateSuperUser(Request $request, $id){
        $this->validate($request, [
            'user.first_name' => 'required|min:2|max:30',
            'user.last_name' => 'required|min:2|max:30',
            'user.phone' => 'required|max:14',
        ]);

        $user = Admin::findOrFail($id);
        $user->update([
            $user->first_name = $request->user['first_name'],
            $user->last_name = $request->user['last_name'],
            $user->phone = $request->user['phone'],
        ]);

        return response()->json($user, 201);
    }

    public function searchForAdmins(Request $request){
        $q = $request->q;
        $users = Admin::where('first_name', 'LIKE', "%".$q."%")
                        ->orWhere('last_name', 'LIKE', "%".$q."%")
                        ->orWhere('email', 'LIKE', "%".$q."%")
                        ->orWhere('phone', 'LIKE', "%".$q."%")
                        ->get();

        return response()->json($users, 200);
    }

    public function getUser($id){
        $user = User::findOrFail($id);

        return response()->json($user, 200);
    }

    public function toggleUserStatus(Request $request, $id){
        $user = User::findOrFail($id);

        if($user->status == 0){
            $user->update([
                $user->status = 1
            ]);
        }else{
            $user->update([
                $user->status = 0
            ]);
        }

        return response()->json($user->status, 200);
    }

    public function updateUserPassword(Request $request, $id){
        $user = User::findOrFail($id);

        $this->validate($request, [
            'password.password' => 'required|min:5|max:20|confirmed',
            'password.password_confirmation' => 'required'
        ]);

        $new = $request->password['password'];
        $user->update([
            $user->password = Hash::make($new)
        ]);

        return response()->json($user, 200);
    }

    public function deleteuser($id){
        $user = User::findOrFail($id);
        $pic = $user->public_id;
        if($pic){
            Cloudinary::destroy($pic);
        }
        $user->delete();
        return response()->json(['message' =>'User Deleted'], 200);
    }

    public function updateUser(Request $request, $id){
        $user = User::findOrFail($id);

        $this->validate($request, [
            'user.first_name' => 'required|min:3|max:30',
            'user.last_name' => 'required|min:3|max:30',
            'user.phone' => 'required|max:14',
        ]);

        $user->update([
            $user->first_name = $request->user['first_name'],
            $user->last_name = $request->user['last_name'],
            $user->phone = $request->user['phone'],
        ]);

        return response()->json($user, 201);
    }

    public function getPaginatedServices(){
        $services = Service::latest()->paginate(20);

        return response()->json($services, 200);
    }

    public function getService($id){
        $service = Service::findOrFail($id);

        return response()->json($service, 200);
    }

    public function toggleApproval(Request $request, $id){
        $service = Service::findOrFail($id);
        if($service->is_approved){
            $service->update([
                $service->is_approved = false
            ]);
        }else{
            $service->update([
                $service->is_approved = true
            ]);
        }

        return response()->json($service->is_approved, 200);
    }

    public function toggleStatus(Request $request, $id){
        $service = Service::findOrFail($id);
        if($service->is_active){
            $service->update([
                $service->is_active = false
            ]);
        }else{
            $service->update([
                $service->is_active = true
            ]);
        }

        return response()->json($service->is_active, 200);
    }

    public function togglePlan(Request $request, $id){
        $service = Service::findOrFail($id);
        if($service->is_premium){
            $service->update([
                $service->is_premium = false
            ]);
        }else{
            $service->update([
                $service->is_premium = true
            ]);
        }

        return response()->json($service->is_premium, 200);
    }

    public function toggleFeature(Request $request, $id){
        $service = Service::findOrFail($id);
        if($service->is_featured){
            $service->update([
                $service->is_featured = false
            ]);
        }else{
            $service->update([
                $service->is_featured = true
            ]);
        }

        return response()->json($service->is_featured, 200);
    }

    public function getPgntdPortfolios(){
        $pfs = Portfolio::latest()->paginate(10);

        return response()->json($pfs, 200);
    }

    public function deletePf(Request $request, $id){
        $pf = Portfolio::findOrFail($id);
        $files = PortfolioFile::where('portfolio_id', $id)->get();
        foreach($files as $file){
            //delete cloudinary file
            //delete file from db
        }
        $pf->delete();

        return response()->json(['message' => 'Deleted!']);
    }

    public function getPortfolio($id){
        $pf = Portfolio::with('service')->findOrFail($id);

        return response()->json($pf, 200);
    }

    public function updatePortfolio(Request $request, $id){
        $this->validate($request, [
            'portfolio.title' => 'required|min:5|max:200',
            'portfolio.detail' => 'required|min:10|max:600',
            'portfolio.cost' => 'numeric|between:1,100000000000',
        ]);

        $pf = Portfolio::findOrFail($id);
        $pf->update([
            $pf->title = $request->portfolio['title'],
            $pf->detail = $request->portfolio['detail'],
            $pf->cost = $request->portfolio['cost'] * 100,
        ]);

        return response()->json($pf, 200);
    }

    public function togglePfApproval($id){
        $pf = Portfolio::findOrFail($id);
        if($pf->is_approved){
            $pf->update([
                $pf->is_approved = false
            ]);
        }else{
            $pf->update([
                $pf->is_approved = true
            ]);
        }

        return response()->json($pf->is_approved, 200);
    }

    public function deletePfFile(Request $request, $id){
        $file = PortfolioFile::findOrFail($id);
        $public_id = $file->public_id;
        if($public_id){
            \Cloudinary::destroy($public_id);
        }
        $file->delete();
        return response()->json(['message' => 'File Deleted!'], 200);
    }

    public function getReviews()
    {
        $revs = Review::latest()->paginate(20);
        // User::latest()->paginate(20);

        return response()->json($revs, 200);
    }

    public function deleteService($id){
        $serv = Service::findOrFail($id);
        $pfs = Portfolio::where('service_id', $id)->get();
        foreach($pfs as $pf){
            $files = PortfolioFile::where('portfolio_id', $pf->id)->get();
             //delete portfolio files
            foreach($files as $file){
                \Cloudinary::destroy($file->public_id);
                $file->delete();
            }
            $pf->delete();
        }

        //delete service file
        $serv_file = $serv->public_id;
        if($serv_file){
            \Cloudinary::destroy($serv_file);
        }

        $serv->delete();

        return response()->json(['message' => 'Service Deleted'], 200);
    }
}
