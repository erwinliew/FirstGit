<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class ProfileController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $profile = Profile::where('user_id', $user->id)->first();
        $posts = \App\Models\Post::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $numPosts = \App\Models\Post::where('user_id', $user->id)->count();

        return view('profile', [
            'user' => $user,
            'profile' => $profile,
            'posts' => $posts,
            'numPosts' => $numPosts
        ]);
    }

    public function create()
    {
        return view('createProfile');
    }

    public function postCreate()
    {
        $data = request()->validate([
            'description' => 'required',
            'profilepic' => ['required', 'image'],
            'bgpic' => ['required', 'image'],
        ]); // this is server side validation. Laravel makes sure that description and profile pic is filled in.

        // store the image in uploads, under public
        // request(‘profilepic’) is like $_GET[‘profilepic’]. This is the Laravel way. 
        $imagePath = request('profilepic')->store('uploads', 'public');
        $bgPath = request('bgpic')->store('uploads', 'public');

        // create a new profile, and save it
        $user = Auth::user();
        $profile = new Profile();
        $profile->user_id = $user->id;
        $profile->description = request('description');
        $profile->image = $imagePath;
        $profile->background = $bgPath;
        $saved = $profile->save(); // we do not need SQL code anymore. $profile->save() creates the new database entry

        // if it saved, we send them to the profile page
        // this variable $saved is an error bit that monitors whether profile is saved or not. You can 
        // use it to redirect the user on error. 
        if ($saved) {
            return redirect('/profile');
        }
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = Profile::where('user_id', $user->id)->first();
        return view('editProfile', [
            'profile' => $profile
        ]);
    }

    public function postEdit($id)
    {
        $data = request()->validate([
            'description' => 'required',
            'profilepic' => 'image',
            'bgpic' => 'image',
        ]);
        // Load the existing profile
        $user = Auth::user();

        // Find the corresponding profile with the $id,
        // Create a new profile if its empty
        $profile = Profile::find($id);
        if (empty($profile)) {
            $profile = new Profile();
            $profile->user_id = $user->id;
        }
        $profile->description = request('description');
        // Save the new profile pic... if there is one in the request()!
        if (request()->has('profilepic')) {
            $imagePath = request('profilepic')->store('uploads', 'public');
            $profile->image = $imagePath;
            // Use this code to check for pic size: request('profilepic')->getSize()
        }

        if (request()->has('bgpic')) {
            $bgPath = request('bgpic')->store('uploads', 'public');
            $profile->background = $bgPath;
        }


        // Now, save it all into the database
        $updated = $profile->save();
        if ($updated) {
            return redirect('/profile');
        }
    }
}
