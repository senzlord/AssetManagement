<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Perangkat;
use App\Models\Kategori;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NonHardwareExport;

class NonHardwareController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view device data');

        $categories = Kategori::where('type', 'Non-Hardware')->get();

        // Filter perangkat where TYPE is 'Hardware', with optional search
        $search = $request->input('search');
        $hardwares = Perangkat::where('TYPE', 'Non-Hardware')
                    ->where(function ($query) use ($search) {
                        $query->where('BRAND', 'like', "%$search%")
                              ->orWhere('SERIAL_NUMBER', 'like', "%$search%")
                              ->orWhere('CATEGORY', 'like', "%$search%")
                              ->orWhere('HOST_NAME', 'like', "%$search%");
                    })
                    ->paginate(10);

        return view('nonhardware.index', compact('hardwares','categories'));
    }
    
    public function export()
    {
        return Excel::download(new NonHardwareExport, 'non_hardware-data.xlsx');
    }

    public function storeCategory(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:Non-Hardware',
        ]);

        // Handle validation failure
        if ($validator->fails()) {
            log_action('error', 'Category Non-Hardware creation failed.', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->all(),
            ]);

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Pastikan semua input benar.');
        }

        // Create the new category
        $category = Kategori::create([
            'name' => $request->input('name'),
            'type' => $request->input('type'),
        ]);

        // Log successful creation
        log_action('success', 'Category Non-Hardware created successfully.', [
            'category_id' => $category->id,
            'name' => $category->name,
            'type' => $category->type,
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Kategori Non Hardware berhasil ditambahkan.');
    }

    public function create()
    {
        $latestId = Perangkat::latest('PERANGKAT_ID')->value('PERANGKAT_ID') ?? 0;
        $nextId = $latestId + 1;

        $categories = Kategori::where('type', 'Non-Hardware')->get();

        return view('nonhardware.create', compact('nextId','categories'));
    }

    public function store(Request $request)
    {
        // dd($request->all()); // Debugging to inspect the request
        $this->authorize('add device data');

        // Define validation rules for the incoming request
        $rules = [
            'HOST_NAME' => ['required', 'string', 'max:100', 'unique:perangkat,HOST_NAME'],
            'BRAND' => ['required', 'string', 'max:100'],
            'CATEGORY' => ['required', 'string', 'max:100'],
            'SERIAL_NUMBER' => ['required', 'string', 'max:100'],
            'LOCATION' => ['required', 'string', 'max:100'],
            'USER' => ['required', 'string', 'max:100'],
            'VENDOR' => ['required', 'string', 'max:100'],
            'FIRMWARE' => ['nullable', 'string', 'max:100'], // Optional firmware version
            'OS_VERSION' => ['nullable', 'string', 'max:100'], // Optional OS_VERSION version
            'EOS_FIRMWARE' => ['nullable', 'date'], // Optional firmware EOS date
            'LICENCE_END_DATE' => ['nullable', 'date'], // Optional license end date
            'NAMA_KONTRAK' => ['required', 'string', 'max:100'],
            'NO_KONTRAK' => ['required', 'string', 'max:100'],
            'STATUS_SUPPORT' => ['required', 'in:Support,Tidak Support'], // Ensures valid support status
            'ATS_END_DATE' => ['nullable', 'date'], // Optional ATS end date
        ];

        // Run the validator
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            log_action('error', 'Validation failed during hardware creation: ' . json_encode($validator->errors()->toArray()));
            return redirect()->route('nonhardware.create')
                ->withErrors($validator) // Send back validation errors
                ->withInput(); // Retain user input
        }

        // Prepare the validated data
        $data = $validator->validated();
        $data['TYPE'] = 'Non-Hardware'; // Set TYPE explicitly as 'Hardware'

        // Insert into the Perangkat table
        Perangkat::create($data);

        log_action('info', 'Hardware Perangkat created successfully.', ['data' => $data]);

        return redirect()->route('nonhardware.index')->with('success', 'Hardware Perangkat created successfully.');
    }

    public function show($id)
    {
        $hardware = Perangkat::where('TYPE', 'Non-Hardware')
                            ->findOrFail($id);
        return view('nonhardware.show', compact('hardware'));
    }

    public function edit($id)
    {
        $hardware = Perangkat::where('TYPE', 'Non-Hardware')
                            ->findOrFail($id);
        $categories = Kategori::where('type', 'Non-Hardware')->get();
        return view('nonhardware.edit', compact('hardware','categories'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('edit device data');

        // Find the existing hardware record by ID
        $hardware = Perangkat::findOrFail($id);

        // Define validation rules
        $rules = [
            'HOST_NAME' => ['required', 'string', 'max:100', Rule::unique('perangkat', 'HOST_NAME')->ignore($id, 'PERANGKAT_ID')],
            'BRAND' => ['required', 'string', 'max:100'],
            'CATEGORY' => ['required', 'string', 'max:100'],
            'SERIAL_NUMBER' => ['required', 'string', 'max:100'],
            'LOCATION' => ['required', 'string', 'max:100'],
            'USER' => ['required', 'string', 'max:100'],
            'VENDOR' => ['required', 'string', 'max:100'],
            'EOS_HARDWARE' => ['nullable', 'date'], // Optional hardware EOS date
            'FIRMWARE' => ['nullable', 'string', 'max:100'], // Optional firmware version
            'OS_VERSION' => ['nullable', 'string', 'max:100'], // Optional OS_VERSION version
            'EOS_FIRMWARE' => ['nullable', 'date'], // Optional firmware EOS date
            'LICENCE_END_DATE' => ['nullable', 'date'], // Optional license end date
            'NAMA_KONTRAK' => ['required', 'string', 'max:100'],
            'NO_KONTRAK' => ['required', 'string', 'max:100'],
            'STATUS_SUPPORT' => ['required', 'in:Support,Tidak Support'], // Valid support status
            'ATS_END_DATE' => ['nullable', 'date'], // Optional ATS end date
        ];

        // Run the validator
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            log_action('error', 'Validation failed during hardware update: ' . json_encode($validator->errors()->toArray()));
            return redirect()->route('nonhardware.edit', $id)
                ->withErrors($validator) // Send back validation errors
                ->withInput(); // Retain user input
        }

        // Prepare the validated data
        $data = $validator->validated();
        $data['TYPE'] = 'Non-Hardware'; // Ensure TYPE remains 'Hardware'

        // Update the record in the Perangkat table
        $hardware->update($data);

        log_action('info', 'Hardware Perangkat updated successfully.', ['id' => $id, 'data' => $data]);

        return redirect()->route('nonhardware.index')->with('success', 'Hardware Perangkat updated successfully.');
    }

    public function destroy($id)
    {
        try {
            // Find the resource by its ID
            $nonHardware = Perangkat::findOrFail($id);

            // Delete the resource
            $nonHardware->delete();

            // Log action if necessary
            log_action('info', 'Non-Hardware deleted successfully.', ['id' => $id]);

            // Redirect back with success message
            return redirect()->route('nonhardware.index')->with('success', 'Non-Hardware deleted successfully.');
        } catch (\Exception $e) {
            // Handle errors and redirect with error message
            log_action('error', 'Failed to delete Non-Hardware.', ['error' => $e->getMessage()]);
            return redirect()->route('nonhardware.index')->with('error', 'Failed to delete Non-Hardware.');
        }
    }
}
