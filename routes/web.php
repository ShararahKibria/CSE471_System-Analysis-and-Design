<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-categories1', function () {
    $result1 = DB::select('SELECT * FROM car_catergories WHERE description = ?', ['Luxury']);
    return $result1;
});

Route::get('/test-categories2', function () {
    $result1 = DB::select('SELECT * FROM car_catergories WHERE description = ?', ['family']);
    return $result1;
});

Route::get('/test-categories3', function () {
    $result1 = DB::select('SELECT * FROM car_catergories WHERE description = ?', ['emergency']);
    return $result1;
});

Route::get('/mechanics', function () {
    $result_mechanics = DB::select('SELECT * FROM mechanics WHERE status = 0');
    return $result_mechanics;
});

Route::get('/car_services', function () {
    $result_car_services = DB::select('SELECT * FROM car_services');
    return $result_car_services;
});
Route::get('/car_approval', function () {
    $result_car_approval = DB::select('SELECT * FROM car_owners WHERE approved = 0');
    return $result_car_approval;
});

Route::get('/mechanic_approval', function () {
    $result_mechanic_approval = DB::select('SELECT * FROM mechanics WHERE approved = 0');
    return $result_mechanic_approval;
});

Route::get('/driver_approval', function () {
    $result_driver_approval = DB::select('SELECT * FROM drivers WHERE approved = 0');
    return $result_driver_approval;
});


// Route to approve a car owner
Route::post('/approve-car-owner', function (Request $request) {
    try {
        $id = $request->input('id');
        
        // Check if car owner exists and is pending
        $carOwner = DB::select('SELECT * FROM car_owners WHERE id = ? AND approved = 0', [$id]);
        
        if (empty($carOwner)) {
            return response()->json(['success' => false, 'message' => 'Car owner not found'], 404);
        }
        
        // Approve the car owner
        DB::update('UPDATE car_owners SET approved = 1 WHERE id = ?', [$id]);
        
        return response()->json([
            'success' => true,
            'message' => 'Car owner approved successfully',
            'data' => ['id' => $id, 'name' => $carOwner[0]->full_name]
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error approving car owner: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Failed to approve'], 500);
    }
});

// Route to reject a car owner
Route::post('/reject-car-owner', function (Request $request) {
    try {
        $id = $request->input('id');
        
        // Check if car owner exists and is pending
        $carOwner = DB::select('SELECT * FROM car_owners WHERE id = ? AND approved = 0', [$id]);
        
        if (empty($carOwner)) {
            return response()->json(['success' => false, 'message' => 'Car owner not found'], 404);
        }
        
        // Delete the car owner record
        DB::delete('DELETE FROM car_owners WHERE id = ?', [$id]);
        
        return response()->json([
            'success' => true,
            'message' => 'Car owner rejected successfully',
            'data' => ['id' => $id, 'name' => $carOwner[0]->full_name]
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error rejecting car owner: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Failed to reject'], 500);
    }
});

// Route to approve a mechanic
Route::post('/approve-mechanic', function (Request $request) {
    try {
        $id = $request->input('id');
        
        $mechanic = DB::select('SELECT * FROM mechanics WHERE id = ? AND approved = 0', [$id]);
        
        if (empty($mechanic)) {
            return response()->json(['success' => false, 'message' => 'Mechanic not found'], 404);
        }
        
        DB::update('UPDATE mechanics SET approved = 1 WHERE id = ?', [$id]);
        
        return response()->json([
            'success' => true,
            'message' => 'Mechanic approved successfully',
            'data' => ['id' => $id, 'name' => $mechanic[0]->name]
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error approving mechanic: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Failed to approve'], 500);
    }
});

// Route to reject a mechanic
Route::post('/reject-mechanic', function (Request $request) {
    try {
        $id = $request->input('id');
        
        $mechanic = DB::select('SELECT * FROM mechanics WHERE id = ? AND approved = 0', [$id]);
        
        if (empty($mechanic)) {
            return response()->json(['success' => false, 'message' => 'Mechanic not found'], 404);
        }
        
        DB::delete('DELETE FROM mechanics WHERE id = ?', [$id]);
        
        return response()->json([
            'success' => true,
            'message' => 'Mechanic rejected successfully',
            'data' => ['id' => $id, 'name' => $mechanic[0]->name]
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error rejecting mechanic: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Failed to reject'], 500);
    }
});

// Route to approve a driver
Route::post('/approve-driver', function (Request $request) {
    try {
        $id = $request->input('id');
        
        $driver = DB::select('SELECT * FROM drivers WHERE id = ? AND approved = 0', [$id]);
        
        if (empty($driver)) {
            return response()->json(['success' => false, 'message' => 'Driver not found'], 404);
        }
        
        DB::update('UPDATE drivers SET approved = 1 WHERE id = ?', [$id]);
        
        return response()->json([
            'success' => true,
            'message' => 'Driver approved successfully',
            'data' => ['id' => $id, 'name' => $driver[0]->name]
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error approving driver: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Failed to approve'], 500);
    }
});

// Route to reject a driver
Route::post('/reject-driver', function (Request $request) {
    try {
        $id = $request->input('id');
        
        $driver = DB::select('SELECT * FROM drivers WHERE id = ? AND approved = 0', [$id]);
        
        if (empty($driver)) {
            return response()->json(['success' => false, 'message' => 'Driver not found'], 404);
        }
        
        DB::delete('DELETE FROM drivers WHERE id = ?', [$id]);
        
        return response()->json([
            'success' => true,
            'message' => 'Driver rejected successfully',
            'data' => ['id' => $id, 'name' => $driver[0]->name]
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error rejecting driver: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Failed to reject'], 500);
    }
});
Route::post('/reject-car-owner', function (Request $request) {
    try {
        $request->validate(['id' => 'required|integer|exists:car_owners,id']);
        
        $ownerId = $request->input('id');
        $carOwner = DB::table('car_owners')->where('id', $ownerId)->where('approved', 0)->first();

        if (!$carOwner) {
            return response()->json(['success' => false, 'message' => 'Car owner not found'], 404);
        }

        DB::table('car_owners')->where('id', $ownerId)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Car owner rejected successfully',
            'data' => ['id' => $ownerId, 'name' => $carOwner->name]
        ]);
    } catch (\Exception $e) {
        Log::error('Error rejecting car owner: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Failed to reject'], 500);
    }
});


Route::get('/drivers', function () {
    $result_drivers = DB::select('SELECT * FROM drivers WHERE status = 0');
    return $result_drivers;
});

// Route::get('/mechanics', [MechanicController::class, 'index']);
// Route::post('/confirm-mechanic', [MechanicController::class, 'confirmService']);

Route::get('/cars/emergency', function () {
    return view('cars.emergency');
});

Route::get('/cars/family', function () {
    return view('cars.family');
});

Route::get('/cars/luxury', function () {
    return view('cars.luxury');
});

Route::get('/cars/find_mechanics', function () {
    return view('cars.find_mechanics');
});

Route::get('/cars/find_driver', function () {
    return view('cars.find_driver');
});

Route::get('/cars/find_premium_service', function () {
    return view('cars.find_premium_service');
});

Route::get('/cars/add_car', function () {
    return view('cars.add_car');
});

Route::get('/cars/sign_in', function () {
    return view('cars.sign_in');
});

Route::get('/cars/find_driver/review/{id}', function ($id) {
    $driver = \DB::table('drivers')->where('id', $id)->first();
    $reviews = \DB::table('reviews')->where('driver_id', $id)->get();
    
    return view('cars.find_review', compact('driver', 'reviews'));
});

Route::get('/cars/add_car', function () {
    return view('cars.add_car');
});
Route::get('/admin/car_approve', function () {
    return view('admin.car_approve');
});
Route::get('/admin/mechanic_approve', function () {
    return view('admin.mechanic_approve');
});
Route::get('/admin/driver_approve', function () {
    return view('admin.driver_approve');
});

// Handle form submission (POST)
Route::post('/cars/add_car', function (Request $request) {
    try {
        // Direct database insertion
        DB::table('car_owners')->insert([
            'full_name' => $request->input('full_name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'car_make' => $request->input('car_make'),
            'car_model' => $request->input('car_model'),
            'car_year' => $request->input('car_year'),
            'car_color' => $request->input('car_color'),
            'license_plate' => $request->input('license_plate'),
            'vin' => $request->input('vin'),
            'car_condition' => $request->input('car_condition'),
            'mileage' => $request->input('mileage'),
            'location' => $request->input('location'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Car Owner Registered Successfully!');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Registration failed. Please try again.');
    }
});

Route::get('/mechanic/dashboard', function () {
    return view('mechanic.dashboard');
});

Route::get('/cars/add_mechanic', function () {
    return view('cars.add_mechanic');
});
Route::get('/admin/admin', function () {
    return view('admin.admin');
});


Route::get('/cars/add_driver', function () {
    return view('cars.add_driver');
});

Route::get('/car-owner/dashboard', function () {
    return view('car-owner.dashboard');
});

Route::get('/driver/dashboard', function () {
    return view('driver.dashboard');
});

Route::get('/register/register', function () {
    return view('register.register');
});

Route::post('/sign_in', function (Request $request) {
    try {
        $role = $request->input('role');
        $email = $request->input('email');
        $password = $request->input('password');
        
        // Validate input
        if (!$role || !$email || !$password) {
            return redirect()->back()->with('error', 'All fields are required');
        }
        
        $user = null;
        $tableName = '';
        
        // Check credentials in the appropriate table based on role
        switch ($role) {
            case 'mechanic':
                $user = DB::table('mechanics')->where('email', $email)->first();
                $tableName = 'mechanics';
                break;
                
            case 'car_owner':
                $user = DB::table('car_owners')->where('email', $email)->first();
                $tableName = 'car_owners';
                break;
                
            case 'driver':
                $user = DB::table('drivers')->where('email', $email)->first();
                $tableName = 'drivers';
                break;
                
            default:
                return redirect()->back()->with('error', 'Invalid role selected');
        }
        
        // Check if user exists
        if (!$user) {
            return redirect()->back()->with('error', 'No account found with this email for the selected role');
        }
        
        // Check password (plain text comparison)
        if ($password !== $user->password) {
            return redirect()->back()->with('error', 'Invalid password');
        }
        
        // Authentication successful
        session([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $role,
            'user_table' => $tableName
        ]);
        
        // Redirect based on role - you can change these URLs as needed
        switch ($role) {
            case 'mechanic':
                return redirect('/mechanic/dashboard')->with('success', 'Welcome back, Mechanic!');

            case 'car_owner':
                return redirect('/car-owner/dashboard')->with('success', 'Welcome back, Car Owner!');

            case 'driver':
                return redirect('/driver/dashboard')->with('success', 'Welcome back, Driver!');
        }
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Sign in failed: ' . $e->getMessage());
    }
});

Route::post('/cars/add_driver', function (Request $request) {
    try {
        // Validate required fields
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:drivers,email',
            'password' => 'required|string|min:6', // Minimum 6 characters for security
            'license_type' => 'required|string|in:Light,Heavy,Motorbike',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'expected_salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:0,1',
            'rating' => 'nullable|numeric|between:0,5',
        ]);

        // Hash the password for security
        $validated['password'] = Hash::make($validated['password']);
        
        // Set default image path
        $validated['image'] = 'images/driver/Nurul.JPEG';
        
        // Set default rating if not provided
        $validated['rating'] = $validated['rating'] ?? 0;

        // Insert into database
        $driver = DB::table('drivers')->insertGetId($validated);

        // Return JSON response for AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Driver registered successfully!',
                'driver_id' => $driver
            ]);
        }

        // For non-AJAX requests, redirect with success message
        return redirect()->back()->with('success', 'Driver registered successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        
        // Return validation errors as JSON for AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Please check your input and try again.'
            ], 422);
        }

        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput()
            ->with('error', 'Please check your input and try again.');
            
    } catch (\Illuminate\Database\QueryException $e) {
        
        \Log::error('Database error during driver registration: ' . $e->getMessage());
        
        $errorMessage = 'Registration failed due to database error.';
        
        // Handle specific database errors
        if ($e->getCode() == 23000) { // Integrity constraint violation
            if (strpos($e->getMessage(), 'email') !== false) {
                $errorMessage = 'This email address is already registered.';
            }
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 500);
        }
        
        return redirect()->back()
            ->withInput()
            ->with('error', $errorMessage);
            
    } catch (\Exception $e) {
        
        \Log::error('Unexpected error during driver registration: ' . $e->getMessage());
        
        $errorMessage = 'An unexpected error occurred. Please try again.';
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 500);
        }
        
        return redirect()->back()
            ->withInput()
            ->with('error', $errorMessage);
    }
});


// Mechanic Registration Route
Route::post('/cars/add_mechanic', function (Request $request) {
    try {
        // Validate required fields
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'specialty' => 'required|string|min:10|max:1000',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'address' => 'required|string|max:500',
            'status' => 'required|in:0,1,2', // 0=Offline, 1=Available, 2=Busy
            'rating' => 'nullable|numeric|between:0,5',
            'email' => 'required|email|unique:mechanics,email',
        ]);

        // Direct database insertion
        DB::table('mechanics')->insert([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'password' => $request->input('password'),
            'specialty' => $request->input('specialty'),
            'latitude' => $request->input('latitude') ?: null,
            'longitude' => $request->input('longitude') ?: null,
            'address' => $request->input('address'),
            'status' => $request->input('status'),
            'rating' => $request->input('rating') ?: 0,
            'email' => $request->input('email'),
        ]);

        return redirect()->back()->with('success', 'Mechanic Registered Successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput()
            ->with('error', 'Please check your input and try again.');
            
    } catch (\Exception $e) {
        // Log the error for debugging
        \Log::error('Mechanic registration failed: ' . $e->getMessage());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Registration failed. Please try again.');
    }






    
});

