<?php

use Illuminate\Http\Request;
use App\Models\PersonalDetails;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// STORE DATA TO THE DATABASE
Route::post('/create-personal-details', function(Request $request) {

    if ($request->hasFile('profile_img')) {
        $imageName = time().'.'.$request->file('profile_img')->getClientOriginalName();
        $request->file('profile_img')->move(public_path('image'), $imageName);
    } else {
        $imageName = null;
    }

    $personalDetail = new PersonalDetails();
    $personalDetail->profile_img = $imageName;
    $personalDetail->first_name = $request['fname'];
    $personalDetail->middle_name = $request['mname'];
    $personalDetail->last_name = $request['lname'];
    $personalDetail->birthdate = $request['birthdate'];
    $personalDetail->address = $request['address'];
    $personalDetail->gender = $request['gender'];
    $personalDetail->contact_no = $request['contact'];
    $personalDetail->save();

    return response()->json([
        'message' => 'Personal details submitted successfully.',
        'data' => $personalDetail,
    ], 200);
});


// FETCH DATA FROM THE DATABASE THEN IT SHOW TO THE DATATABLE
Route::get('/personal-details', function($entries = 10) {
    $personalDetails = PersonalDetails::orderByDesc('created_at')->paginate($entries);

    return $personalDetails;
});


// FETCH DATA TO DISPLAY TO EDIT FORM INPUT FIELDS
Route::get('/edit-personal-details/{id}', function($id) {
    $data = PersonalDetails::find($id);

    return response()->json($data, 200);
});

// UPDATE DATA TO THE DATABASE
Route::put('/update-personal-details/{id}', function(Request $request, $id) {
    $data = PersonalDetails::find($id);

    if (!$data) {
        return response()->json(['message' => 'Personal details not found.'], 404);
    }
    $validatedData = $request->validate([
        'first_name' => 'required|string',
        'middle_name' => 'nullable|string',
        'last_name' => 'required|string',
        'birthdate' => 'required|date',
        'address' => 'required|string',
        'gender' => 'required|string|in:male,female',
        'contact_no' => 'required|string',
    ]);

    $data->update($validatedData);

    return response()->json([
        'message' => 'Personal details updated successfully.',
        'data' => $data
    ], 200);
});

// DELETE DATA TO THE DATABASE
Route::delete('/delete-personal-details/{id}', function($id) {
    $data = PersonalDetails::find($id);

    if (!$data) {
        return response()->json(['message' => 'Personal details not found.'], 404);
    }
    $data->delete();

    return response()->json([
        'message' => 'Personal details deleted successfully.'
    ], 200);
});
