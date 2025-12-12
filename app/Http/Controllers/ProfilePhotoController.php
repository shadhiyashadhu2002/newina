<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GalleryImage;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfilePhotoController extends Controller
{
    /**
     * Upload profile photo
     */
    public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB
        ]);

        try {
            $user = auth()->user();
            
            // Delete old photo if exists
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            // Store new photo
            $file = $request->file('photo');
            $filename = time() . '_' . Str::slug($user->first_name) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile-photos', $filename, 'public');

            // Update user profile photo
            $user->update(['photo' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Profile photo uploaded successfully',
                'photo_url' => asset('storage/' . $path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading photo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload gallery images
     */
    public function uploadGalleryImage(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            $user = auth()->user();
            $member = $user->member; // assuming relationship exists

            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Member profile not found'
                ], 404);
            }

            $uploadedImages = [];

            foreach ($request->file('images') as $file) {
                // Create upload record
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('gallery', $filename, 'public');

                $upload = Upload::create([
                    'user_id' => $user->id,
                    'file_name' => $path,
                    'file_original_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'extension' => $file->getClientOriginalExtension(),
                    'type' => 'image'
                ]);

                // Create gallery image record
                GalleryImage::create([
                    'user_id' => $user->id,
                    'image' => $upload->id
                ]);

                $uploadedImages[] = [
                    'id' => $upload->id,
                    'url' => asset('storage/' . $path)
                ];
            }

            return response()->json([
                'success' => true,
                'message' => count($uploadedImages) . ' image(s) uploaded successfully',
                'images' => $uploadedImages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading images: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete gallery image
     */
    public function deleteGalleryImage($imageId)
    {
        try {
            $galleryImage = GalleryImage::find($imageId);

            if (!$galleryImage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image not found'
                ], 404);
            }

            // Check authorization
            if ($galleryImage->user_id != auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Delete upload file
            $upload = Upload::find($galleryImage->image);
            if ($upload) {
                Storage::disk('public')->delete($upload->file_name);
                $upload->delete();
            }

            // Delete gallery record
            $galleryImage->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user gallery images
     */
    public function getUserGallery($userId)
    {
        try {
            $images = GalleryImage::where('user_id', $userId)
                ->with('upload')
                ->get()
                ->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => asset('storage/' . $image->upload->file_name),
                        'original_name' => $image->upload->file_original_name
                    ];
                });

            return response()->json([
                'success' => true,
                'images' => $images
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching gallery: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get profile photo
     */
    public function getProfilePhoto($userId)
    {
        try {
            $user = User::find($userId);

            if (!$user || !$user->photo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Photo not found',
                    'placeholder' => asset('images/placeholder.png')
                ]);
            }

            return response()->json([
                'success' => true,
                'photo_url' => asset('storage/' . $user->photo)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
