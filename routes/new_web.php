<!-- <?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

// Add CORS headers for all responses
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-TOKEN');

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

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

// ========== MECHANIC ROUTES (FIXED) ==========

// Get all mechanics with CORS headers
Route::get('/mechanics', function () {
    try {
        $mechanics = DB::table('mechanics')->get();
        
        return response()->json($mechanics, 200, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-CSRF-TOKEN',
            'Content-Type' => 'application/json',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to fetch mechanics',
            'message' => $e->getMessage()
        ], 500, [
            'Access-Control-Allow-Origin' => '*',
            'Content-Type' => 'application/json',
        ]);
    }
});

// Submit mechanic service request with CORS headers
Route::post('/mechanic-confirmation', function (Request $request) {
    try {
        $validated = $request->validate([
            'mechanic_id'   => 'required|integer',
            'mechanic_name' => 'required|string|max:255',
            'owner_id'      => 'required|string|max:255',
            'owner_name'    => 'required|string|max:255',
            'service_date'  => 'required|date|after_or_equal:today',
        ]);

        // Check if mechanic exists
        $mechanic = DB::table('mechanics')->where('id', $validated['mechanic_id'])->first();
        
        if (!$mechanic) {
            return response()->json([
                'error' => 'Mechanic not found'
            ], 404, [
                'Access-Control-Allow-Origin' => '*',
                'Content-Type' => 'application/json',
            ]);
        }

        $id = DB::table('mechanic_confirmation')->insertGetId([
            'mechanic_id'    => $validated['mechanic_id'],
            'mechanic_name'  => $validated['mechanic_name'],
            'owner_id'       => $validated['owner_id'],
            'owner_name'     => $validated['owner_name'],
            'service_date'   => $validated['service_date'],
            'status'         => 'pending',
            'payment_status' => 'pending',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => '✅ Service request created successfully!',
            'request_id' => $id,
            'data' => [
                'mechanic_name' => $validated['mechanic_name'],
                'service_date' => $validated['service_date'],
                'owner_name' => $validated['owner_name']
            ]
        ], 201, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-CSRF-TOKEN',
            'Content-Type' => 'application/json',
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'Validation failed',
            'message' => 'Please check your input fields',
            'errors' => $e->errors()
        ], 422, [
            'Access-Control-Allow-Origin' => '*',
            'Content-Type' => 'application/json',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to create service request',
            'message' => $e->getMessage()
        ], 500, [
            'Access-Control-Allow-Origin' => '*',
            'Content-Type' => 'application/json',
        ]);
    }
});

// Update mechanic status / payment status
Route::post('/mechanic-confirmation/{id}/status', function (Request $request, $id) {
    try {
        DB::table('mechanic_confirmation')
            ->where('id', $id)
            ->update([
                'status' => $request->input('status', 'pending'),
                'payment_status' => $request->input('payment_status', 'pending'),
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => '✅ Status updated successfully!',
            'id' => $id
        ], 200, [
            'Access-Control-Allow-Origin' => '*',
            'Content-Type' => 'application/json',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to update status',
            'message' => $e->getMessage()
        ], 500, [
            'Access-Control-Allow-Origin' => '*',
            'Content-Type' => 'application/json',
        ]);
    }
});

// Test route to verify server is working
Route::get('/test', function () {
    return response()->json([
        'message' => 'Laravel server is running!',
        'timestamp' => now(),
        'available_routes' => [
            'GET /mechanics' => 'Fetch all mechanics',
            'POST /mechanic-confirmation' => 'Submit service request',
            'POST /mechanic-confirmation/{id}/status' => 'Update request status'
        ]
    ], 200, [
        'Access-Control-Allow-Origin' => '*',
        'Content-Type' => 'application/json',
    ]);
});

// ========== APPROVAL ROUTES ==========

Route::post('/approve-car-owner', function (Request $request) {
    try {
        $id = $request->input('id');
        
        $carOwner = DB::select('SELECT * FROM car_owners WHERE id = ? AND approved = 0', [$id]);
        
        if (empty($carOwner)) {
            return response()->json(['success' => false, 'message' => 'Car owner not found'], 404);
        }
        
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

Route::post('/reject-car-owner', function (Request $request) {
    try {
        $id = $request->input('id');
        
        $carOwner = DB::select('SELECT * FROM car_owners WHERE id = ? AND approved = 0', [$id]);
        
        if (empty($carOwner)) {
            return response()->json(['success' => false, 'message' => 'Car owner not found'], 404);
        }
        
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

// ========== OTHER ROUTES ==========

Route::get('/drivers', function () {
    $result_drivers = DB::select('SELECT * FROM drivers WHERE status = 0');
    return $result_drivers;
});

// View routes
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

Route::get('/admin/car_approve', function () {
    return view('admin.car_approve');
});

Route::get('/driver/payments', function () {
    return view('driver.payments');
});

Route::get('/admin/mechanic_approve', function () {
    return view('admin.mechanic_approve');
});

Route::get('/admin/driver_approve', function () {
    return view('admin.driver_approve');
});

Route::get('/admin/admin', function () {
    return view('admin.admin');
});

Route::get('/driver/update-profile', function () {
    return view('driver.update-profile');
});

Route::get('/cars/add_mechanic', function () {
    return view('cars.add_mechanic');
});

Route::get('/cars/add_driver', function () {
    return view('cars.add_driver');
});

Route::get('/register/register', function () {
    return view('register.register');
});

// ========== FORM HANDLING ROUTES ==========

Route::post('/cars/add_car', function (Request $request) {
    try {
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

Route::post('/hire-driver', function (Request $request) {
    try {
        $driverId = $request->input('driver_id');
        $ownerId = $request->input('owner_id');
        $ownerName = $request->input('owner_name');

        if (!$driverId || !$ownerId || !$ownerName) {
            return response()->json(['error' => 'All fields are required'], 400);
        }

        $confirmationId = DB::table('driver_confirmation')->insertGetId([
            'driver_id' => $driverId,
            'owner_id' => $ownerId,
            'owner_name' => $ownerName,
            'first_down_payment' => 0,
            'final_payment' => 0,
            'accept' => 0,
            'created_at' => now(),
            'updated_at' => now(),
            'confirmation_date' => now()->addDays(1)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hire request submitted successfully',
            'id' => $confirmationId
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to submit hire request: ' . $e->getMessage()], 500);
    }
});

Route::get('/driver-requests/{driverId}', function ($driverId) {
    $requests = DB::table('driver_confirmation')
        ->where('driver_id', $driverId)
        ->get();

    return response()->json($requests);
});

Route::post('/driver-accept/{id}', function ($id) {
    DB::table('driver_confirmation')
        ->where('id', $id)
        ->update([
            'accept' => 1,
            'updated_at' => now()
        ]);

    return response()->json(['success' => true, 'message' => 'Request accepted']);
});

Route::post('/driver-reject/{id}', function ($id) {
    DB::table('driver_confirmation')
        ->where('id', $id)
        ->update([
            'accept' => -1,
            'updated_at' => now()
        ]);

    return response()->json(['success' => true, 'message' => 'Request rejected']);
});

// ========== DASHBOARD ROUTES ==========

Route::get('/driver/dashboard', function (Request $request) {
    $role = $request->query('role');
    $id = $request->query('id');

    $sessionRole = session('user_role');
    $sessionId = session('user_id');
    $sessionName = session('user_name');

    if (!$sessionName && ($id ?? $sessionId) && ($role ?? $sessionRole) === 'driver') {
        $userId = $id ?? $sessionId;
        $driver = DB::table('drivers')->where('id', $userId)->first();
        
        if ($driver) {
            if (isset($driver->name)) {
                $sessionName = $driver->name;
            } elseif (isset($driver->first_name) && isset($driver->last_name)) {
                $sessionName = $driver->first_name . ' ' . $driver->last_name;
            } elseif (isset($driver->first_name)) {
                $sessionName = $driver->first_name;
            } elseif (isset($driver->full_name)) {
                $sessionName = $driver->full_name;
            } else {
                $sessionName = explode('@', $driver->email)[0];
            }
            
            session(['user_name' => $sessionName]);
        }
    }

    return view('driver.dashboard', [
        'role' => $role ?? $sessionRole,
        'id' => $id ?? $sessionId,
        'name' => $sessionName ?? 'Driver'
    ]);
});

Route::get('/car-owner/dashboard', function (Request $request) {
    $role = $request->query('role');
    $id = $request->query('id');

    $sessionRole = session('user_role');
    $sessionId = session('user_id');
    $sessionName = session('user_name');

    if (!$sessionName && ($id ?? $sessionId) && ($role ?? $sessionRole) === 'car_owners') {
        $userId = $id ?? $sessionId;
        $carOwner = DB::table('car_owners')->where('id', $userId)->first();

        if ($carOwner) {
            if (isset($carOwner->name)) {
                $sessionName = $carOwner->name;
            } elseif (isset($carOwner->first_name) && isset($carOwner->last_name)) {
                $sessionName = $carOwner->first_name . ' ' . $carOwner->last_name;
            } elseif (isset($carOwner->first_name)) {
                $sessionName = $carOwner->first_name;
            } elseif (isset($carOwner->full_name)) {
                $sessionName = $carOwner->full_name;
            } else {
                $sessionName = explode('@', $carOwner->email)[0];
            }
            
            session(['user_name' => $sessionName]);
        }
    }

    return view('car-owner.dashboard', [
        'role' => $role ?? $sessionRole,
        'id' => $id ?? $sessionId,
        'name' => $sessionName ?? 'Car Owner'
    ]);
});

Route::get('/mechanic/dashboard', function (Request $request) {
    $role = $request->query('role');
    $id = $request->query('id');

    $sessionRole = session('user_role');
    $sessionId = session('user_id');

    return view('mechanic.dashboard', [
        'role' => $role ?? $sessionRole,
        'id' => $id ?? $sessionId
    ]);
});

// ========== PAYMENT AND PROFILE ROUTES ==========

Route::get('/driver/payments', function (Request $request) {
    $id = $request->query('id') ?? session('user_id');
    $role = $request->query('role') ?? session('user_role');
    
    if ($role !== 'driver') {
        return redirect()->back()->with('error', 'Unauthorized access');
    }
    
    $driver = DB::table('drivers')->where('id', $id)->first();
    
    if (!$driver) {
        return redirect()->back()->with('error', 'Driver not found');
    }
    
    $driverName = $driver->name;
    
    $payments = DB::table('payments')
        ->where('car_owner', $driverName)
        ->orderBy('created_at', 'desc')
        ->get();
    
    $totalEarned = $payments->where('final_payment', 1)->sum('paid_amount');
    $pendingAmount = $payments->where('first_payment', 0)->where('final_payment', 0)->sum('paid_amount');
    $partialAmount = $payments->where('first_payment', 1)->where('final_payment', 0)->sum('paid_amount');
    
    $completedPayments = $payments->where('final_payment', 1)->count();
    $pendingPayments = $payments->where('first_payment', 0)->where('final_payment', 0)->count();
    $partialPayments = $payments->where('first_payment', 1)->where('final_payment', 0)->count();
    
    return view('driver.payments', [
        'payments' => $payments,
        'driverName' => $driverName,
        'driverId' => $id,
        'totalEarned' => $totalEarned,
        'pendingAmount' => $pendingAmount,
        'partialAmount' => $partialAmount,
        'completedPayments' => $completedPayments,
        'pendingPayments' => $pendingPayments,
        'partialPayments' => $partialPayments
    ]);
});

Route::get('/driver/update-profile', function (Request $request) {
    $id = $request->query('id') ?? session('user_id');
    $role = $request->query('role') ?? session('user_role');
    
    if ($role !== 'driver') {
        return redirect()->back()->with('error', 'Unauthorized access');
    }
    
    $driver = DB::table('drivers')->where('id', $id)->first();
    
    if (!$driver) {
        return redirect()->back()->with('error', 'Driver not found');
    }
    
    return view('driver.update-profile', [
        'driver' => $driver,
        'id' => $id,
        'role' => $role
    ]);
});

Route::post('/driver/update-profile', function (Request $request) {
    try {
        $id = $request->input('id') ?? session('user_id');
        $workingHours = $request->input('working_hours');
        
        if (!$workingHours) {
            return redirect()->back()->with('error', 'Working hours field is required');
        }
        
        if (!$id) {
            return redirect()->back()->with('error', 'Driver ID not found');
        }
        
        $updated = DB::table('drivers')
            ->where('id', $id)
            ->update(['working_hour' => $workingHours]);
            
        if ($updated) {
            return redirect("/driver/dashboard?role=driver&id=$id")
                ->with('success', 'Working hours updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update working hours');
        }
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
    }
});

// ========== AUTHENTICATION ROUTES ==========

Route::post('/sign_in', function (Request $request) {
    try {
        $role = $request->input('role');
        $email = $request->input('email');
        $password = $request->input('password');
        
        if (!$role || !$email || !$password) {
            return redirect()->back()->with('error', 'All fields are required');
        }
        
        $user = null;
        $tableName = '';
        
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
        
        if (!$user) {
            return redirect()->back()->with('error', 'No account found with this email for the selected role');
        }
        
        $userName = '';
        if (isset($user->name)) {
            $userName = $user->name;
        } elseif (isset($user->first_name) && isset($user->last_name)) {
            $userName = $user->first_name . ' ' . $user->last_name;
        } elseif (isset($user->first_name)) {
            $userName = $user->first_name;
        } elseif (isset($user->full_name)) {
            $userName = $user->full_name;
        } else {
            $userName = explode('@', $user->email)[0];
        }
        
        session([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_name' => $userName,
            'user_role' => $role,
            'user_table' => $tableName
        ]);
        
        switch ($role) {
            case 'mechanic':
                return redirect("/mechanic/dashboard?role=$role&id={$user->id}")
                    ->with('success', "Welcome back, $userName!");

            case 'car_owner':
                return redirect("/car-owner/dashboard?role=$role&id={$user->id}")
                    ->with('success', "Welcome back, $userName!");

            case 'driver':
                return redirect("/driver/dashboard?role=$role&id={$user->id}")
                    ->with('success', "Welcome back, $userName!");
        }
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Sign in failed: ' . $e->getMessage());
    }
});

// ========== REGISTRATION ROUTES ==========

Route::post('/cars/add_driver', function (Request $request) {
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:drivers,email',
            'password' => 'required|string|min:6',
            'license_type' => 'required|string|in:Light,Heavy,Motorbike',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'expected_salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:0,1',
            'rating' => 'nullable|numeric|between:0,5',
        ]);

        $validated['image'] = 'images/driver/Nurul.JPEG';
        $validated['rating'] = $validated['rating'] ?? 0;

        $driver = DB::table('drivers')->insertGetId($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Driver registered successfully!',
                'driver_id' => $driver
            ]);
        }

        return redirect()->back()->with('success', 'Driver registered successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        
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
        
        if ($e->getCode() == 23000) {
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

Route::post('/cars/add_mechanic', function (Request $request) {
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'specialty' => 'required|string|min:10|max:1000',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'address' => 'required|string|max:500',
            'status' => 'required|in:0,1,2',
            'rating' => 'nullable|numeric|between:0,5',
            'email' => 'required|email|unique:mechanics,email',
        ]);

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
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Mechanic Registered Successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput()
            ->with('error', 'Please check your input and try again.');
            
    } catch (\Exception $e) {
        \Log::error('Mechanic registration failed: ' . $e->getMessage());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Registration failed. Please try again.');
    }
});






















// Add CORS headers for all responses
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-TOKEN');

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

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

// ========== MECHANIC ROUTES (FIXED) ==========

// Get all mechanics with CORS headers
Route::get('/mechanics', function () {
    try {
        $mechanics = DB::table('mechanics')->get();
        
        return response()->json($mechanics, 200, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-CSRF-TOKEN',
            'Content-Type' => 'application/json',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to fetch mechanics',
            'message' => $e->getMessage()
        ], 500, [
            'Access-Control-Allow-Origin' => '*',
            'Content-Type' => 'application/json',
        ]);
    }
});

// Submit mechanic service request with CORS headers
Route::post('/mechanic-confirmation', function (Request $request) {
    try {
        $validated = $request->validate([
            'mechanic_id'   => 'required|integer',
            'mechanic_name' => 'required|string|max:255',
            'owner_id'      => 'required|string|max:255',
            'owner_name'    => 'required|string|max:255',
            'service_date'  => 'required|date|after_or_equal:today',
        ]);

        // Check if mechanic exists
        $mechanic = DB::table('mechanics')->where('id', $validated['mechanic_id'])->first();
        
        if (!$mechanic) {
            return response()->json([
                'error' => 'Mechanic not found'
            ], 404, [
                'Access-Control-Allow-Origin' => '*',
                'Content-Type' => 'application/json',
            ]);
        }

        $id = DB::table('mechanic_confirmation')->insertGetId([
            'mechanic_id'    => $validated['mechanic_id'],
            'mechanic_name'  => $validated['mechanic_name'],
            'owner_id'       => $validated['owner_id'],
            'owner_name'     => $validated['owner_name'],
            'service_date'   => $validated['service_date'],
            'status'         => 'pending',
            'payment_status' => 'pending',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => '✅ Service request created successfully!',
            'request_id' => $id,
            'data' => [
                'mechanic_name' => $validated['mechanic_name'],
                'service_date' => $validated['service_date'],
                'owner_name' => $validated['owner_name']
            ]
        ], 201, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-CSRF-TOKEN',
            'Content-Type' => 'application/json',
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'Validation failed',
            'message' => 'Please check your input fields',
            'errors' => $e->errors()
        ], 422, [
            'Access-Control-Allow-Origin' => '*',
            'Content-Type' => 'application/json',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to create service request',
            'message' => $e->getMessage()
        ], 500, [
            'Access-Control-Allow-Origin' => '*',
            'Content-Type' => 'application/json',
        ]);
    }
});

// Update mechanic status / payment status
Route::post('/mechanic-confirmation/{id}/status', function (Request $request, $id) {
    try {
        DB::table('mechanic_confirmation')
            ->where('id', $id)
            ->update([
                'status' => $request->input('status', 'pending'),
                'payment_status' => $request->input('payment_status', 'pending'),
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => '✅ Status updated successfully!',
            'id' => $id
        ], 200, [
            'Access-Control-Allow-Origin' => '*',
            'Content-Type' => 'application/json',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to update status',
            'message' => $e->getMessage()
        ], 500, [
            'Access-Control-Allow-Origin' => '*',
            'Content-Type' => 'application/json',
        ]);
    }
});

// Test route to verify server is working
Route::get('/test', function () {
    return response()->json([
        'message' => 'Laravel server is running!',
        'timestamp' => now(),
        'available_routes' => [
            'GET /mechanics' => 'Fetch all mechanics',
            'POST /mechanic-confirmation' => 'Submit service request',
            'POST /mechanic-confirmation/{id}/status' => 'Update request status'
        ]
    ], 200, [
        'Access-Control-Allow-Origin' => '*',
        'Content-Type' => 'application/json',
    ]);
});

// ========== APPROVAL ROUTES ==========

Route::post('/approve-car-owner', function (Request $request) {
    try {
        $id = $request->input('id');
        
        $carOwner = DB::select('SELECT * FROM car_owners WHERE id = ? AND approved = 0', [$id]);
        
        if (empty($carOwner)) {
            return response()->json(['success' => false, 'message' => 'Car owner not found'], 404);
        }
        
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

Route::post('/reject-car-owner', function (Request $request) {
    try {
        $id = $request->input('id');
        
        $carOwner = DB::select('SELECT * FROM car_owners WHERE id = ? AND approved = 0', [$id]);
        
        if (empty($carOwner)) {
            return response()->json(['success' => false, 'message' => 'Car owner not found'], 404);
        }
        
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

// ========== OTHER ROUTES ==========

Route::get('/drivers', function () {
    $result_drivers = DB::select('SELECT * FROM drivers WHERE status = 0');
    return $result_drivers;
});

// View routes
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

Route::get('/admin/car_approve', function () {
    return view('admin.car_approve');
});

Route::get('/driver/payments', function () {
    return view('driver.payments');
});

Route::get('/admin/mechanic_approve', function () {
    return view('admin.mechanic_approve');
});

Route::get('/admin/driver_approve', function () {
    return view('admin.driver_approve');
});

Route::get('/admin/admin', function () {
    return view('admin.admin');
});

Route::get('/driver/update-profile', function () {
    return view('driver.update-profile');
});

Route::get('/cars/add_mechanic', function () {
    return view('cars.add_mechanic');
});

Route::get('/cars/add_driver', function () {
    return view('cars.add_driver');
});

Route::get('/register/register', function () {
    return view('register.register');
});

// ========== FORM HANDLING ROUTES ==========

Route::post('/cars/add_car', function (Request $request) {
    try {
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

Route::post('/hire-driver', function (Request $request) {
    try {
        $driverId = $request->input('driver_id');
        $ownerId = $request->input('owner_id');
        $ownerName = $request->input('owner_name');

        if (!$driverId || !$ownerId || !$ownerName) {
            return response()->json(['error' => 'All fields are required'], 400);
        }

        $confirmationId = DB::table('driver_confirmation')->insertGetId([
            'driver_id' => $driverId,
            'owner_id' => $ownerId,
            'owner_name' => $ownerName,
            'first_down_payment' => 0,
            'final_payment' => 0,
            'accept' => 0,
            'created_at' => now(),
            'updated_at' => now(),
            'confirmation_date' => now()->addDays(1)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hire request submitted successfully',
            'id' => $confirmationId
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to submit hire request: ' . $e->getMessage()], 500);
    }
});

Route::get('/driver-requests/{driverId}', function ($driverId) {
    $requests = DB::table('driver_confirmation')
        ->where('driver_id', $driverId)
        ->get();

    return response()->json($requests);
});

Route::post('/driver-accept/{id}', function ($id) {
    DB::table('driver_confirmation')
        ->where('id', $id)
        ->update([
            'accept' => 1,
            'updated_at' => now()
        ]);

    return response()->json(['success' => true, 'message' => 'Request accepted']);
});

Route::post('/driver-reject/{id}', function ($id) {
    DB::table('driver_confirmation')
        ->where('id', $id)
        ->update([
            'accept' => -1,
            'updated_at' => now()
        ]);

    return response()->json(['success' => true, 'message' => 'Request rejected']);
});

// ========== DASHBOARD ROUTES ==========

Route::get('/driver/dashboard', function (Request $request) {
    $role = $request->query('role');
    $id = $request->query('id');

    $sessionRole = session('user_role');
    $sessionId = session('user_id');
    $sessionName = session('user_name');

    if (!$sessionName && ($id ?? $sessionId) && ($role ?? $sessionRole) === 'driver') {
        $userId = $id ?? $sessionId;
        $driver = DB::table('drivers')->where('id', $userId)->first();
        
        if ($driver) {
            if (isset($driver->name)) {
                $sessionName = $driver->name;
            } elseif (isset($driver->first_name) && isset($driver->last_name)) {
                $sessionName = $driver->first_name . ' ' . $driver->last_name;
            } elseif (isset($driver->first_name)) {
                $sessionName = $driver->first_name;
            } elseif (isset($driver->full_name)) {
                $sessionName = $driver->full_name;
            } else {
                $sessionName = explode('@', $driver->email)[0];
            }
            
            session(['user_name' => $sessionName]);
        }
    }

    return view('driver.dashboard', [
        'role' => $role ?? $sessionRole,
        'id' => $id ?? $sessionId,
        'name' => $sessionName ?? 'Driver'
    ]);
});

Route::get('/car-owner/dashboard', function (Request $request) {
    $role = $request->query('role');
    $id = $request->query('id');

    $sessionRole = session('user_role');
    $sessionId = session('user_id');
    $sessionName = session('user_name');

    if (!$sessionName && ($id ?? $sessionId) && ($role ?? $sessionRole) === 'car_owners') {
        $userId = $id ?? $sessionId;
        $carOwner = DB::table('car_owners')->where('id', $userId)->first();

        if ($carOwner) {
            if (isset($carOwner->name)) {
                $sessionName = $carOwner->name;
            } elseif (isset($carOwner->first_name) && isset($carOwner->last_name)) {
                $sessionName = $carOwner->first_name . ' ' . $carOwner->last_name;
            } elseif (isset($carOwner->first_name)) {
                $sessionName = $carOwner->first_name;
            } elseif (isset($carOwner->full_name)) {
                $sessionName = $carOwner->full_name;
            } else {
                $sessionName = explode('@', $carOwner->email)[0];
            }
            
            session(['user_name' => $sessionName]);
        }
    }

    return view('car-owner.dashboard', [
        'role' => $role ?? $sessionRole,
        'id' => $id ?? $sessionId,
        'name' => $sessionName ?? 'Car Owner'
    ]);
});

Route::get('/mechanic/dashboard', function (Request $request) {
    $role = $request->query('role');
    $id = $request->query('id');

    $sessionRole = session('user_role');
    $sessionId = session('user_id');

    return view('mechanic.dashboard', [
        'role' => $role ?? $sessionRole,
        'id' => $id ?? $sessionId
    ]);
});

// ========== PAYMENT AND PROFILE ROUTES ==========

Route::get('/driver/payments', function (Request $request) {
    $id = $request->query('id') ?? session('user_id');
    $role = $request->query('role') ?? session('user_role');
    
    if ($role !== 'driver') {
        return redirect()->back()->with('error', 'Unauthorized access');
    }
    
    $driver = DB::table('drivers')->where('id', $id)->first();
    
    if (!$driver) {
        return redirect()->back()->with('error', 'Driver not found');
    }
    
    $driverName = $driver->name;
    
    $payments = DB::table('payments')
        ->where('car_owner', $driverName)
        ->orderBy('created_at', 'desc')
        ->get();
    
    $totalEarned = $payments->where('final_payment', 1)->sum('paid_amount');
    $pendingAmount = $payments->where('first_payment', 0)->where('final_payment', 0)->sum('paid_amount');
    $partialAmount = $payments->where('first_payment', 1)->where('final_payment', 0)->sum('paid_amount');
    
    $completedPayments = $payments->where('final_payment', 1)->count();
    $pendingPayments = $payments->where('first_payment', 0)->where('final_payment', 0)->count();
    $partialPayments = $payments->where('first_payment', 1)->where('final_payment', 0)->count();
    
    return view('driver.payments', [
        'payments' => $payments,
        'driverName' => $driverName,
        'driverId' => $id,
        'totalEarned' => $totalEarned,
        'pendingAmount' => $pendingAmount,
        'partialAmount' => $partialAmount,
        'completedPayments' => $completedPayments,
        'pendingPayments' => $pendingPayments,
        'partialPayments' => $partialPayments
    ]);
});

Route::get('/driver/update-profile', function (Request $request) {
    $id = $request->query('id') ?? session('user_id');
    $role = $request->query('role') ?? session('user_role');
    
    if ($role !== 'driver') {
        return redirect()->back()->with('error', 'Unauthorized access');
    }
    
    $driver = DB::table('drivers')->where('id', $id)->first();
    
    if (!$driver) {
        return redirect()->back()->with('error', 'Driver not found');
    }
    
    return view('driver.update-profile', [
        'driver' => $driver,
        'id' => $id,
        'role' => $role
    ]);
});

Route::post('/driver/update-profile', function (Request $request) {
    try {
        $id = $request->input('id') ?? session('user_id');
        $workingHours = $request->input('working_hours');
        
        if (!$workingHours) {
            return redirect()->back()->with('error', 'Working hours field is required');
        }
        
        if (!$id) {
            return redirect()->back()->with('error', 'Driver ID not found');
        }
        
        $updated = DB::table('drivers')
            ->where('id', $id)
            ->update(['working_hour' => $workingHours]);
            
        if ($updated) {
            return redirect("/driver/dashboard?role=driver&id=$id")
                ->with('success', 'Working hours updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update working hours');
        }
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
    }
});

// ========== AUTHENTICATION ROUTES ==========

Route::post('/sign_in', function (Request $request) {
    try {
        $role = $request->input('role');
        $email = $request->input('email');
        $password = $request->input('password');
        
        if (!$role || !$email || !$password) {
            return redirect()->back()->with('error', 'All fields are required');
        }
        
        $user = null;
        $tableName = '';
        
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
        
        if (!$user) {
            return redirect()->back()->with('error', 'No account found with this email for the selected role');
        }
        
        $userName = '';
        if (isset($user->name)) {
            $userName = $user->name;
        } elseif (isset($user->first_name) && isset($user->last_name)) {
            $userName = $user->first_name . ' ' . $user->last_name;
        } elseif (isset($user->first_name)) {
            $userName = $user->first_name;
        } elseif (isset($user->full_name)) {
            $userName = $user->full_name;
        } else {
            $userName = explode('@', $user->email)[0];
        }
        
        session([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_name' => $userName,
            'user_role' => $role,
            'user_table' => $tableName
        ]);
        
        switch ($role) {
            case 'mechanic':
                return redirect("/mechanic/dashboard?role=$role&id={$user->id}")
                    ->with('success', "Welcome back, $userName!");

            case 'car_owner':
                return redirect("/car-owner/dashboard?role=$role&id={$user->id}")
                    ->with('success', "Welcome back, $userName!");

            case 'driver':
                return redirect("/driver/dashboard?role=$role&id={$user->id}")
                    ->with('success', "Welcome back, $userName!");
        }
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Sign in failed: ' . $e->getMessage());
    }
});

// ========== REGISTRATION ROUTES ==========

Route::post('/cars/add_driver', function (Request $request) {
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:drivers,email',
            'password' => 'required|string|min:6',
            'license_type' => 'required|string|in:Light,Heavy,Motorbike',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'expected_salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:0,1',
            'rating' => 'nullable|numeric|between:0,5',
        ]);

        $validated['image'] = 'images/driver/Nurul.JPEG';
        $validated['rating'] = $validated['rating'] ?? 0;

        $driver = DB::table('drivers')->insertGetId($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Driver registered successfully!',
                'driver_id' => $driver
            ]);
        }

        return redirect()->back()->with('success', 'Driver registered successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        
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
        
        if ($e->getCode() == 23000) {
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

Route::post('/cars/add_mechanic', function (Request $request) {
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'specialty' => 'required|string|min:10|max:1000',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'address' => 'required|string|max:500',
            'status' => 'required|in:0,1,2',
            'rating' => 'nullable|numeric|between:0,5',
            'email' => 'required|email|unique:mechanics,email',
        ]);

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
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Mechanic Registered Successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput()
            ->with('error', 'Please check your input and try again.');
            
    } catch (\Exception $e) {
        \Log::error('Mechanic registration failed: ' . $e->getMessage());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Registration failed. Please try again.');
    }
});










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

// Route::get('/mechanics', function () {
//     $result_mechanics = DB::select('SELECT * FROM mechanics WHERE status = 0');
//     return $result_mechanics;
// });

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
Route::get('/driver/payments', function () {
    return view('driver.payments');
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

Route::post('/hire-driver', function (Request $request) {
    try {
        $driverId = $request->input('driver_id');
        $ownerId = $request->input('owner_id');
        $ownerName = $request->input('owner_name');

        if (!$driverId || !$ownerId || !$ownerName) {
            return response()->json(['error' => 'All fields are required'], 400);
        }

        $confirmationId = DB::table('driver_confirmation')->insertGetId([
            'driver_id' => $driverId,
            'owner_id' => $ownerId,
            'owner_name' => $ownerName,
            'first_down_payment' => 0,
            'final_payment' => 0,
            'accept' => 0, // default: not accepted
            'created_at' => now(),
            'updated_at' => now(),
            'confirmation_date' => now()->addDays(1)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hire request submitted successfully',
            'id' => $confirmationId
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to submit hire request: ' . $e->getMessage()], 500);
    }
});

// === Driver views requests sent to them ===
Route::get('/driver-requests/{driverId}', function ($driverId) {
    $requests = DB::table('driver_confirmation')
        ->where('driver_id', $driverId)
        ->get();

    return response()->json($requests);
});

// === Driver accepts a request ===
Route::post('/driver-accept/{id}', function ($id) {
    DB::table('driver_confirmation')
        ->where('id', $id)
        ->update([
            'accept' => 1,
            'updated_at' => now()
        ]);

    return response()->json(['success' => true, 'message' => 'Request accepted']);
});

// === Driver rejects a request ===
Route::post('/driver-reject/{id}', function ($id) {
    DB::table('driver_confirmation')
        ->where('id', $id)
        ->update([
            'accept' => -1, // use -1 for rejection
            'updated_at' => now()
        ]);

    return response()->json(['success' => true, 'message' => 'Request rejected']);
});

Route::get('/cars/add_mechanic', function () {
    return view('cars.add_mechanic');
});
Route::get('/admin/admin', function () {
    return view('admin.admin');
});
Route::get('/driver/update-profile', function () {
    return view('driver.update-profile');
});

Route::get('/driver/payments', function (Request $request) {
    $id = $request->query('id') ?? session('user_id');
    $role = $request->query('role') ?? session('user_role');
    
    // Verify it's a driver
    if ($role !== 'driver') {
        return redirect()->back()->with('error', 'Unauthorized access');
    }
    
    // Get driver info
    $driver = DB::table('drivers')->where('id', $id)->first();
    
    if (!$driver) {
        return redirect()->back()->with('error', 'Driver not found');
    }
    
    // Get all payments for this driver (assuming car_owner field contains driver name)
    $driverName = $driver->name; // Adjust this field name according to your drivers table
    
    $payments = DB::table('payments')
        ->where('car_owner', $driverName)
        ->orderBy('created_at', 'desc')
        ->get();
    
    // Calculate summary statistics
    $totalEarned = $payments->where('final_payment', 1)->sum('paid_amount');
    $pendingAmount = $payments->where('first_payment', 0)->where('final_payment', 0)->sum('paid_amount');
    $partialAmount = $payments->where('first_payment', 1)->where('final_payment', 0)->sum('paid_amount');
    
    $completedPayments = $payments->where('final_payment', 1)->count();
    $pendingPayments = $payments->where('first_payment', 0)->where('final_payment', 0)->count();
    $partialPayments = $payments->where('first_payment', 1)->where('final_payment', 0)->count();
    
    return view('driver.payments', [
        'payments' => $payments,
        'driverName' => $driverName,
        'driverId' => $id,
        'totalEarned' => $totalEarned,
        'pendingAmount' => $pendingAmount,
        'partialAmount' => $partialAmount,
        'completedPayments' => $completedPayments,
        'pendingPayments' => $pendingPayments,
        'partialPayments' => $partialPayments
    ]);
});

// Alternative route if you want to match payments by driver ID instead of name
Route::get('/driver/payments-alt', function (Request $request) {
    $id = $request->query('id') ?? session('user_id');
    $role = $request->query('role') ?? session('user_role');
    
    // Verify it's a driver
    if ($role !== 'driver') {
        return redirect()->back()->with('error', 'Unauthorized access');
    }
    
    // Get driver info
    $driver = DB::table('drivers')->where('id', $id)->first();
    
    if (!$driver) {
        return redirect()->back()->with('error', 'Driver not found');
    }
    
    // If you have a driver_id field in payments table, use this approach
    $payments = DB::table('payments')
        ->where('driver_id', $id) // Assuming you have driver_id field
        ->orderBy('created_at', 'desc')
        ->get();
    
    // Calculate summary statistics
    $totalEarned = $payments->where('final_payment', 1)->sum('paid_amount');
    $pendingAmount = $payments->where('first_payment', 0)->where('final_payment', 0)->sum('paid_amount');
    $partialAmount = $payments->where('first_payment', 1)->where('final_payment', 0)->sum('paid_amount');
    
    $completedPayments = $payments->where('final_payment', 1)->count();
    $pendingPayments = $payments->where('first_payment', 0)->where('final_payment', 0)->count();
    $partialPayments = $payments->where('first_payment', 1)->where('final_payment', 0)->count();
    
    return view('driver.payments', [
        'payments' => $payments,
        'driverName' => $driver->name,
        'driverId' => $id,
        'totalEarned' => $totalEarned,
        'pendingAmount' => $pendingAmount,
        'partialAmount' => $partialAmount,
        'completedPayments' => $completedPayments,
        'pendingPayments' => $pendingPayments,
        'partialPayments' => $partialPayments
    ]);
});


Route::get('/cars/add_driver', function () {
    return view('cars.add_driver');
});

// Route::get('/car-owner/dashboard', function (Request $request) {
//     $role = $request->query('role'); // from ?role=car_owner
//     $id = $request->query('id');     // from ?id=1

//     // Fallback to session values if query params are missing
//     $sessionRole = session('user_role');
//     $sessionId = session('user_id');

//     return view('car-owner.dashboard', [
//         'role' => $role ?? $sessionRole,
//         'id' => $id ?? $sessionId
//     ]);
// });

Route::get('/driver/dashboard', function (Request $request) {
    $role = $request->query('role');
    $id = $request->query('id');

    // Get from session
    $sessionRole = session('user_role');
    $sessionId = session('user_id');
    $sessionName = session('user_name');

    // If name is not in session but we have ID and role, fetch from database
    if (!$sessionName && ($id ?? $sessionId) && ($role ?? $sessionRole) === 'driver') {
        $userId = $id ?? $sessionId;
        $driver = DB::table('drivers')->where('id', $userId)->first();
        
        if ($driver) {
            // Get the driver's name using the same logic as sign-in
            if (isset($driver->name)) {
                $sessionName = $driver->name;
            } elseif (isset($driver->first_name) && isset($driver->last_name)) {
                $sessionName = $driver->first_name . ' ' . $driver->last_name;
            } elseif (isset($driver->first_name)) {
                $sessionName = $driver->first_name;
            } elseif (isset($driver->full_name)) {
                $sessionName = $driver->full_name;
            } else {
                $sessionName = explode('@', $driver->email)[0];
            }
            
            // Update session with the name
            session(['user_name' => $sessionName]);
        }
    }

    return view('driver.dashboard', [
        'role' => $role ?? $sessionRole,
        'id' => $id ?? $sessionId,
        'name' => $sessionName ?? 'Driver' // Fallback name
    ]);
});



Route::get('/car-owner/dashboard', function (Request $request) {
    $role = $request->query('role');
    $id = $request->query('id');

    // Get from session
    $sessionRole = session('user_role');
    $sessionId = session('user_id');
    $sessionName = session('user_name');

    // If name is not in session but we have ID and role, fetch from database
    if (!$sessionName && ($id ?? $sessionId) && ($role ?? $sessionRole) === 'car_owners') {
        $userId = $id ?? $sessionId;
        $carOwner = DB::table('car_owners')->where('id', $userId)->first();

        if ($carOwner) {
            // Get the car owner's name using the same logic as sign-in
            if (isset($carOwner->name)) {
                $sessionName = $carOwner->name;
            } elseif (isset($carOwner->first_name) && isset($carOwner->last_name)) {
                $sessionName = $carOwner->first_name . ' ' . $carOwner->last_name;
            } elseif (isset($carOwner->first_name)) {
                $sessionName = $carOwner->first_name;
            } elseif (isset($carOwner->full_name)) {
                $sessionName = $carOwner->full_name;
            } else {
                $sessionName = explode('@', $carOwner->email)[0];
            }
            
            // Update session with the name
            session(['user_name' => $sessionName]);
        }
    }

    return view('car-owner.dashboard', [
        'role' => $role ?? $sessionRole,
        'id' => $id ?? $sessionId,
        'name' => $sessionName ?? 'Car Owner' // Fallback name
    ]);
});





Route::get('/mechanic/dashboard', function (Request $request) {
    $role = $request->query('role'); // from ?role=mechanic
    $id = $request->query('id');     // from ?id=1

    // Fallback to session values if query params are missing
    $sessionRole = session('user_role');
    $sessionId = session('user_id');

    return view('mechanic.dashboard', [
        'role' => $role ?? $sessionRole,
        'id' => $id ?? $sessionId
    ]);
});


Route::get('/driver/update-profile', function (Request $request) {
    $id = $request->query('id') ?? session('user_id');
    $role = $request->query('role') ?? session('user_role');
    
    // Verify it's a driver
    if ($role !== 'driver') {
        return redirect()->back()->with('error', 'Unauthorized access');
    }
    
    // Get current driver data
    $driver = DB::table('drivers')->where('id', $id)->first();
    
    if (!$driver) {
        return redirect()->back()->with('error', 'Driver not found');
    }
    
    return view('driver.update-profile', [
        'driver' => $driver,
        'id' => $id,
        'role' => $role
    ]);
});

// POST route to handle the update
Route::post('/driver/update-profile', function (Request $request) {
    try {
        $id = $request->input('id') ?? session('user_id');
        $workingHours = $request->input('working_hours');
        
        // Validate input
        if (!$workingHours) {
            return redirect()->back()->with('error', 'Working hours field is required');
        }
        
        if (!$id) {
            return redirect()->back()->with('error', 'Driver ID not found');
        }
        
        // Update the working_hours column
        $updated = DB::table('drivers')
            ->where('id', $id)
            ->update(['working_hour' => $workingHours]);
            
        if ($updated) {
            return redirect("/driver/dashboard?role=driver&id=$id")
                ->with('success', 'Working hours updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update working hours');
        }
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
    }
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
        
        // Password check (use Hash::check if password is hashed)
        
        // Get the user's name based on the table structure
        $userName = '';
        if (isset($user->name)) {
            $userName = $user->name;
        } elseif (isset($user->first_name) && isset($user->last_name)) {
            $userName = $user->first_name . ' ' . $user->last_name;
        } elseif (isset($user->first_name)) {
            $userName = $user->first_name;
        } elseif (isset($user->full_name)) {
            $userName = $user->full_name;
        } else {
            // Fallback to email prefix if no name field found
            $userName = explode('@', $user->email)[0];
        }
        
        // Authentication successful - store in session
        session([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_name' => $userName, // Store the user's name
            'user_role' => $role,
            'user_table' => $tableName
        ]);
        
        // Redirect based on role with role & id in the URL
        switch ($role) {
            case 'mechanic':
                return redirect("/mechanic/dashboard?role=$role&id={$user->id}")
                    ->with('success', "Welcome back, $userName!");

            case 'car_owner':
                return redirect("/car-owner/dashboard?role=$role&id={$user->id}")
                    ->with('success', "Welcome back, $userName!");

            case 'driver':
                return redirect("/driver/dashboard?role=$role&id={$user->id}")
                    ->with('success', "Welcome back, $userName!");
        }
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Sign in failed: ' . $e->getMessage());
    }
});
