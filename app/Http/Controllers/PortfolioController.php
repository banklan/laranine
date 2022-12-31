<?php

namespace App\Http\Controllers;

use App\Portfolio;
use App\PortfolioFile;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PortfolioController extends Controller
{
    public function createPortfolio(Request $request, $id){
        $count = Portfolio::where('service_id', $id)->count();
        if($count < 3){
            $user = auth('api')->user()->id;
            $service_id = $id;
            $this->validate($request, [
                'portfolio.title' => 'required|min:5|max:200|unique:portfolios,title',
                'portfolio.detail' => 'required|min:10|max:600',
                'portfolio.cost' => 'numeric|between:1,100000000000',
            ]);

            $portfolio = new Portfolio;
            $portfolio->service_id = $service_id;
            $portfolio->title = $request->portfolio['title'];
            $portfolio->detail = $request->portfolio['detail'];
            $portfolio->cost = $request->portfolio['cost'] * 100;
            $portfolio->is_approved = false;
            $portfolio->save();

            return response()->json($portfolio, 201);
        }
    }

    public function uploadPortfolioFiles(Request $request, $id){
        $files = $request->files;
        if($request->hasFile('files')){
            foreach($request->file('files') as $file){
                $pool = '0123456789abcdefghijklmnopqrstuvwxyz';
                $ext = $file->getClientOriginalExtension();
                $filename = substr(str_shuffle($pool), 0, 8).".".$ext;

                $result = $file->storeOnCloudinary('hozumart/portfolios');

                $path = $result->getPath();
                $publicId = $result->getPublicId();

                $pf_img = new PortfolioFile;
                $pf_img->portfolio_id = $id;
                $pf_img->file = $path;
                $pf_img->public_id = $publicId;
                $pf_img->save();
            }
            $port = Portfolio::findOrFail($id);
            return response()->json($port, 200);
        }
    }

    public function getPortfolio($id){
        $pf = Portfolio::findOrFail($id);
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

    public function deletePortfolioFile(Request $request, $id){
        $file = PortfolioFile::findOrFail($id);
        $file_name = $file->file;
        if($file_name){
            $filePath = public_path('/assets/portfolios/'.$file_name);
            if(file_exists($filePath)){
                unlink($filePath);
            }
        }
        $file->delete();
        return response()->json(['message' => 'File deleted'], 200);
    }
}
