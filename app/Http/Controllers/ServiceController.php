<?php

namespace App\Http\Controllers;

use App\Service;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use App\Jobs\MyServiceCreatedJob;
// use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Jobs\NewServiceCreatedJob;
use Intervention\Image\Facades\Image;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ServiceController extends Controller
{
    public function getAuthService(){
        $auth = auth('api')->user()->id;
        $service = Service::where('user_id', $auth)->first();
        if($service){
            return response()->json($service, 200);
        }
    }

    public function createService(Request $request){
        $this->validate($request, [
            'service.category_id' => 'required',
            'service.title' => 'required|min:10|max:250|unique:services,title',
            'service.position' => 'max:100',
            'service.description' => 'required|min:20|max:600',
            'service.experience_years' => 'required|integer',
            'service.highlights' => 'max:255',
            'service.keystrength' => 'max:150',
            'service.email' => 'email',
            'service.phone_number' => 'required|max:16',
            'service.website' => 'max:100',
            'service.address' => 'required|min:5|max:255',
            'service.city' => 'required|min:2|max:50',
            'service.facebook' => 'max:200',
            'service.instagram' => 'max:200',
            'service.twitter' => 'max:200',
            'service.linkedIn' => 'max:100',
            'service.youtube' => 'max:100',
            'service.catch' => 'required|min:5|max:120',
        ]);

        $user = auth('api')->user()->id;
        $pool = 'ABCDEFGHIJKLMNPQRSTUVWXYZ1234567890';
        $service_id = substr(str_shuffle($pool), 0, 6);

        $service = new Service;
        $service->category_id = $request->service['category_id'];
        $service->user_id = $user;
        $service->title = $request->service['title'];
        $service->position = $request->service['position'];
        $service->description = $request->service['description'];
        $service->highlight = $request->service['highlights'];
        $service->keystrength = $request->service['keystrength'];
        $service->experience_years = $request->service['experience_years'];
        $service->email = $request->service['email'];
        $service->phone = $request->service['phone_number'];
        $service->address = $request->service['address'];
        $service->city = $request->service['city'];
        $service->location_id = $request->service['location_id'];
        $service->website = $request->service['website'];
        $service->facebook = $request->service['facebook'];
        $service->instagram = $request->service['instagram'];
        $service->twitter = $request->service['twitter'];
        $service->linkedin = $request->service['linkedIn'];
        $service->service_id = $service_id;
        $service->catch = $request->service['catch'];;
        $service->save();

        $service->fresh();

        // notify admin
        dispatch(new NewServiceCreatedJob($service));

        return response()->json($service, 201);
    }

    public function addServiceImage(Request $request){
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        // save file in folder...later in s3 when ready to deploy
        $auth = auth('api')->user();
        $service = Service::where('user_id', $auth->id)->first();
        $file = $request->image;
        if($file){
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz';
            $ext = $file->getClientOriginalExtension();
            $filename = substr(str_shuffle($pool), 0, 8).".".$ext;

            $result = $file->storeOnCloudinary('hozumart/services');
            $publicId = $result->getPublicId();
            $path = $result->getPath();
            $service->update([
                $service->public_id = $publicId,
                $service->image = $path
            ]);
        }

        dispatch(new MyServiceCreatedJob($service, $auth));

        return response()->json($service, 201);
    }

    public function updateServiceImage(Request $request){
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        // save file in folder...later in s3 when ready to deploy
        $auth = auth('api')->user();
        $service = Service::where('user_id', $auth->id)->first();
        $file = $request->image;
        if($file){
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz';
            $ext = $file->getClientOriginalExtension();
            $filename = substr(str_shuffle($pool), 0, 8).".".$ext;

            // check if image exists for the service and unlink
            $old_file = $service->public_id;
            if($old_file){
                \Cloudinary::destroy($old_file);
            }

            $result = $file->storeOnCloudinary('hozumart/services');
            $publicId = $result->getPublicId();
            $path = $result->getPath();
            $service->update([
                $service->public_id = $publicId,
                $service->image = $path
            ]);
        }

        return response()->json($service, 201);
    }

    public function updateService(Request $request){
        $this->validate($request, [
            'service.category_id' => 'required',
            'service.title' => 'required|min:10|max:250',
            'service.position' => 'max:100',
            'service.description' => 'required|min:20|max:600',
            'service.experience_years' => 'required|integer',
            'service.highlight' => 'max:255',
            'service.keystrength' => 'max:150',
            'service.phone' => 'required|max:16',
            'service.website' => 'max:100',
            'service.address' => 'required|min:5|max:255',
            'service.city' => 'required|min:2|max:50',
            'service.facebook' => 'max:200',
            'service.instagram' => 'max:200',
            'service.twitter' => 'max:200',
            'service.linkedin' => 'max:100',
            'service.youtube' => 'max:100',
            'service.catch' => 'required|min:5|max:120',
        ]);

        $service = auth('api')->user()->service;

        $service->update([
            $service->category_id = $request->service['category_id'],
            $service->title = $request->service['title'],
            $service->position = $request->service['position'],
            $service->description = $request->service['description'],
            $service->experience_years = $request->service['experience_years'],
            $service->highlight = $request->service['highlight'],
            $service->keystrength = $request->service['keystrength'],
            $service->phone = $request->service['phone'],
            $service->website = $request->service['website'],
            $service->address = $request->service['address'],
            $service->city = $request->service['city'],
            $service->facebook = $request->service['facebook'],
            $service->instagram = $request->service['instagram'],
            $service->twitter = $request->service['twitter'],
            $service->linkedin = $request->service['linkedin'],
            $service->youtube = $request->service['youtube'],
            $service->catch = $request->service['catch']
        ]);

        return response()->json($service, 200);
    }

    public function getFeatServices(){
        $servs = Service::where('is_featured', true)->get();

        return response()->json($servs, 200);
    }

    public function getPopularServices(){
        $servs = Service::all();
        $servs = $servs->sortByDesc('ratings')->values()->take(3);

        return response()->json($servs, 200);
    }
}
