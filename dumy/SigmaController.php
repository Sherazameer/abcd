<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Exceptions\CustomException;
use App\Http\Controllers\API\ResponseController;
use App\Models\Sigma;
use App\Models\Roll;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SigmaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request, ResponseController $responseController)
{
    // Get the authenticated user's ID
    $userId = Auth::id();

    // Get the user's role
    $userRole = Roll::where('user_id', $userId)->first();

    // Check if the user is an admin
    if ($userRole && $userRole->name === 'admin') {
        // If admin, return all data
        $data['posts'] = Sigma::all();
    } else {
        // If not admin, return only the data for the user's ID
        $data['posts'] = Sigma::where('user_id', $userId)->get();
    }

    return $responseController->jsonResponse(true, 'Data retrieved successfully', $data, 200);
}
    //    public function index(Request $request)
    //  {
    //      $responseController = new ResponseController();
 
    //      // Get the authenticated user's ID
    //      $userId = Auth::id();
 
    //      // Get the user's role
    //      $userRole = Roll::where('user_id', $userId)->first();
 
    //      // Check if the user is an admin
    //      if ($userRole && $userRole->name === 'admin') {
    //          // If admin, return all data
    //          $data['posts'] = Sigma::all();
    //      } else {
    //          // If not admin, return only the data for the user's ID
    //          $data['posts'] = Sigma::where('user_id', $userId)->get();
    //      }
 
    //      return $responseController->jsonResponse(true, 'Data retrieved successfully', $data, 200);
    //  }
    //  public function index()
    //  {
    //      $responseController = new ResponseController();
    //      $user = Auth::user() ?? throw new CustomException('User not authenticated', 401);
 
    //      // Fetch the user's role from the Roll table
    //      $role = Roll::where('user_id', $user->id)->first();
 
    //      if (!$role) {
    //          throw new CustomException('Role not found for the user', 404);
    //      }
 
    //      // Check if the user is an admin or a regular user
    //      if ($role->name === 'admin') {
    //          // Admin can see all data
    //          $data['posts'] = Sigma::all();
    //      } elseif ($role->name === 'user') {
    //          // Regular users can see only their own data
    //          $data['posts'] = Sigma::where('user_id', $user->id)->get();
    //      } else {
    //          // Handle other cases (e.g., user has no valid role)
    //          throw new CustomException('Unauthorized access', 403);
    //      }
 
    //      return $responseController->jsonResponse(true, 'All data resource retrieved successfully', $data, 200);
    //  }
   

     // public function index()
    // {
    //     $responseController = new ResponseController();
    //     $data['posts'] = Sigma::all();
    //     return $responseController->jsonResponse(true, 'All data resource retrieved successfully', $data, 200);
    // }


    // public function index()
    // {
    //     $responseController = new ResponseController();
    //     $user = Auth::user();

    //     if (!$user) {
    //         throw new CustomException('User not authenticated', 401);
    //     }

    //     $userRole = Roll::where('user_id', $user->id)->first();

    //     if ($userRole && $userRole->name == 'admin') {
    //         // Admin can see all data
    //         $data['posts'] = Sigma::all();
    //     } else {
    //         // Regular users can see only their own data
    //         $data['posts'] = Sigma::where('user_id', $user->id)->get();
    //     }

    //     return $responseController->jsonResponse(true, 'All data resource retrieved successfully', $data, 200);
    // }

    // public function index()
    // {
    //     $user = Auth::user() ?? throw new CustomException('User not authenticated', 401);
    //     $isAdmin = Roll::where('user_id', $user->id)->value('name') === 'admin';
    
    //     $data['posts'] = $isAdmin ? Sigma::all() : Sigma::where('user_id', $user->id)->get();
    
    //     return (new ResponseController())->jsonResponse(true, 'All data resource retrieved successfully', $data, 200);
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $responseController = new ResponseController();
        $validateUser = $responseController->validateRequest($request, [
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validateUser->fails()) {
            throw new CustomException('Validation Error', 401);
        }

        $image_name = $this->uploadImage($request->file('image'));
        $user = Sigma::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $image_name,
        ]);

        return $responseController->jsonResponse(true, 'Resource created successfully', ['user' => $user], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $responseController = new ResponseController();
        $data['posts'] = Sigma::select('id', 'title', 'description', 'image')->where('id', $id)->get();
        return $responseController->jsonResponse(true, 'Your Single resource retrieved successfully', $data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $responseController = new ResponseController();
        $validateUser = $responseController->validateRequest($request, [
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validateUser->fails()) {
            throw new CustomException('Validation Error', 401);
        }

        $update_image = Sigma::find($id);
        if (!$update_image) {
            throw new CustomException('Resource not found', 404);
        }

        $image_name = $request->hasFile('image') ? $this->uploadImage($request->file('image'), $update_image->image) : $update_image->image;

        $update_image->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $image_name,
        ]);

        return $responseController->jsonResponse(true, 'Resource updated successfully', ['data' => $update_image], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $responseController = new ResponseController();
        $imagePath = Sigma::select('image')->where('id', $id)->first();
        if ($imagePath) {
            $this->deleteImage($imagePath->image);
        }
        $post = Sigma::where('id', $id)->delete();
        return $responseController->jsonResponse(true, 'Resource deleted successfully', ['post' => $post], 200);
    }

    /**
     * Upload an image and return the image name.
     */
    private function uploadImage($image, $oldImage = null)
    {
        $path = public_path('/uploads/');
        if ($oldImage && file_exists($path . $oldImage)) {
            unlink($path . $oldImage);
        }
        $ext = $image->getClientOriginalExtension();
        $image_name = time() . '.' . $ext;
        $image->move($path, $image_name);
        return $image_name;
    }

    /**
     * Delete an image from the uploads directory.
     */
    private function deleteImage($image_name)
    {
        $filepath = public_path('/uploads/' . $image_name);
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
}
