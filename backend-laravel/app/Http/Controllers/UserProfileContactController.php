<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserProfileContact;

class UserProfileContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'profile_id' => 'required|integer',
                'contacts' => 'required|array|min:1',
                'contacts.*.name' => 'required|string',
                'contacts.*.url' => 'required|string',
                'contacts.*.file_icon' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $contacts = $request->input('contacts', []);
            $inserted = [];

            foreach ($contacts as $index => $contact) {
                $user_profile_contact = new UserProfileContact();

                if ($request->hasFile("contacts.{$index}.file_icon")) {
                    $icon_data = $request->file("contacts.{$index}.file_icon");
                    $icon_data_base64 = base64_encode(file_get_contents($icon_data->getRealPath()));
                } else {
                    $icon_data_base64 = null;
                }

                $user_profile_contact->profile_id = $request->profile_id;
                $user_profile_contact->name = $contact['name'];
                $user_profile_contact->url = $contact['url'];
                $user_profile_contact->status = 'active';
                $user_profile_contact->image_data = $icon_data_base64;

                if ($user_profile_contact->save()) {
                    $inserted[] = $user_profile_contact;
                }
            }

            if (count($inserted) !== count($contacts)) {
                return response()->json([
                    'message' => "บางรายการไม่สามารถบันทึกได้",
                    'saved_count' => count($inserted),
                    'expected_count' => count($contacts),
                ], 400);
            }

            return response()->json([
                'message' => "บันทึกช่องทางการติดต่อสำเร็จ",
                'userProfileContacts' => $inserted,
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'message' => "เกิดข้อผิดพลาดขณะบันทึกข้อมูลช่องทางการติดต่อ",
                'error' => $error->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $contacts = UserProfileContact::where('profile_id', $id)->get();

            if ($contacts->isEmpty()) {
                return response()->json([
                    'message' => 'controller user profile contact show() false or require null',
                    'id' => $id,
                    'contacts' => null
                ], 400);
            }

            $contacts = $contacts->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'profile_id' => $contact->profile_id,
                    'name' => $contact->name,
                    'url' => $contact->url,
                    'detail' => $contact->detail,
                    'imageData' => $contact->image_data ? base64_encode($contact->image_data) : null,
                    'createdAT' => $contact->created_at,
                    'updatedAT' => $contact->updated_at,
                ];
            });

            return response()->json([
                'message' => 'controller user profile contact show() success',
                'contacts' => $contacts
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'controller user profile contact show() error',
                'error' => $error->getMessage()
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $contact = UserProfileContact::findOrFail($id);
            $contact->delete();

            return response()->json([
                'message' => 'Contact deleted successfully.',
                'id' => $id
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete contact.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
