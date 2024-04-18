<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Models\Image;
use Illuminate\Support\Facades\Session;

class DropzoneController extends Controller
{
    /**
     * Generate Image upload View
     *
     * @return void
     */
    public function index(): View
    {
        $images = Image::all();
        return view('dropzone', compact('images'));
    }
      
    /**
     * Image Upload Code
     *
     * @return void
     */
    public function store(Request $request): JsonResponse
    {
        // Initialize an array to store image information
        $images = [];
  
        // Process each uploaded image
        foreach($request->file('files') as $image) {
            // Generate a unique name for the image
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
              
            // Move the image to the desired location
            $image->move(public_path('images'), $imageName);
  
            // Add image information to the array
            $images[] = [
                'name' => $imageName,
                'path' => asset('/images/'. $imageName),
                'filesize' => filesize(public_path('images/'.$imageName))
            ];
        }
  
        // Store images in the database using create method
        foreach ($images as $imageData) {
            Image::create($imageData);
        }
     // Set flash 
      return response()->json(['success' => true, 'message' => 'Images uploaded successfully.']);

        //  Session::flash('success', 'Images uploaded successfully.');
        // return response()->json(['success'=>$images]);
    }
}