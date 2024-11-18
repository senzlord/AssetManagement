<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Perangkat;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SfpExport;

class SFPController extends Controller
{
    public function sfpIndex(Request $request)
    {
        $this->authorize('view device data');

        // Filter perangkat where TYPE is 'SFP', with optional search
        $search = $request->input('search');
        $sfps = Perangkat::where('TYPE', 'SFP')
                    ->where(function ($query) use ($search) {
                        $query->where('BRAND', 'like', "%$search%")
                              ->orWhere('SERIAL_NUMBER', 'like', "%$search%")
                              ->orWhere('HOST_NAME', 'like', "%$search%");
                    })
                    ->paginate(10);

        return view('sfp.index', compact('sfps'));
    }

    public function exportSfp()
    {
        $this->authorize('generate reports');

        return Excel::download(new SfpExport, 'sfp-data.xlsx');
    }

    public function createSfp()
    {
        // Authorize the action
        $this->authorize('add device data');

        $latestId = Perangkat::latest('PERANGKAT_ID')->value('PERANGKAT_ID') ?? 0;
        $nextId = $latestId + 1;

        return view('sfp.create', compact('nextId'));
    }

    public function storeSfp(Request $request)
    {
        // dd($request->all());
        // Authorize the action
        $this->authorize('add device data');

        // Define validation rules
        $rules = [
            'HOST_NAME' => ['required', 'string', 'max:100', 'unique:perangkat,HOST_NAME'],
            'LOCATION' => ['required', 'string', 'max:100'],
            'VENDOR' => ['required', 'string', 'max:100'],
            'PRODUCT_ID_DEVICE' => ['required', 'string', 'max:100'],
            'SERIAL_NUMBER' => ['required', 'string', 'max:100'],
            'IP_ADDRESS' => ['required', 'string', 'ip'],
            'JUMLAH_SFP_DICABUT' => ['required', 'integer', 'min:0'],

            // Add rules for sfp array
            'SFP.product_id.*' => ['required', 'string', 'max:255'], // Validate each product ID
            'SFP.serial_number.*' => ['required', 'string'], // Validate each serial number string
        ];

        // Run the validator
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            log_action('error', 'Validation failed during SFP creation: ' . json_encode($validator->errors()->toArray()));
            return redirect()->route('sfp.create')
                ->withErrors($validator) // Pass validation errors to the session
                ->withInput(); // Keep the input data for repopulation
        }

        // Add the fixed TYPE field
        $data = $validator->validated();
        $data['TYPE'] = 'SFP';

        // Process the SFP array
        if (isset($data['SFP'])) {
            $sfpData = [
                'product_id' => [],
                'serial_number' => []
            ];

            foreach ($data['SFP']['serial_number'] as $index => $serial) {
                if ($serial !== null) {
                    // Keep only entries where serial_number is not null
                    $sfpData['product_id'][] = $data['SFP']['product_id'][$index];
                    $sfpData['serial_number'][] = $serial;
                }
            }

            $data['SFP'] = json_encode($sfpData);
        }

        // Save the validated data
        Perangkat::create($data);

        // Log the success action
        log_action('info', 'SFP Perangkat created successfully.', ['data' => $data]);

        // Redirect with success message
        return redirect()->route('sfp.index')->with('success', 'SFP Perangkat created successfully.');
    }

    public function showSfp($id)
    {
        $sfp = Perangkat::where('TYPE', 'SFP')
                        ->findOrFail($id);
        return view('sfp.show', compact('sfp'));
    }

    public function editSfp($id)
    {
        $this->authorize('edit device data');
        // Fetch the SFP device by ID
        $sfp = Perangkat::where('TYPE', 'SFP')
                        ->findOrFail($id);

        // Return the edit view with the SFP data
        return view('sfp.edit', compact('sfp'));
    }

    public function updateSfp(Request $request, $id)
    {
        // Authorize the action
        $this->authorize('edit device data');

        // Fetch the existing SFP device
        $sfp = Perangkat::where('TYPE', 'SFP')
                        ->findOrFail($id);

        // Define validation rules
        $rules = [
            'LOCATION' => ['required', 'string', 'max:100'],
            'VENDOR' => ['required', 'string', 'max:100'],
            'PRODUCT_ID_DEVICE' => ['required', 'string', 'max:100'],
            'SERIAL_NUMBER' => ['required', 'string', 'max:100'],
            'HOST_NAME' => ['required', 'string', 'max:100'],
            'IP_ADDRESS' => ['required', 'string', 'ip'],
            'JUMLAH_SFP_DICABUT' => ['required', 'integer', 'min:0'],

            // Add rules for sfp array
            'SFP.product_id.*' => ['required', 'string', 'max:255'], // Validate each product ID
            'SFP.serial_number.*' => ['required', 'string'], // Validate each serial number string
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check for validation errors
        if ($validator->fails()) {
            log_action('error', 'Validation failed during SFP update: ' . json_encode($validator->errors()->toArray()));
            return redirect()->route('sfp.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $data['TYPE'] = 'SFP';

        // Process the SFP array
        if (isset($data['SFP'])) {
            $sfpData = [
                'product_id' => [],
                'serial_number' => []
            ];

            foreach ($data['SFP']['serial_number'] as $index => $serial) {
                if ($serial !== null) {
                    // Keep only entries where serial_number is not null
                    $sfpData['product_id'][] = $data['SFP']['product_id'][$index];
                    $sfpData['serial_number'][] = $serial;
                }
            }

            $data['SFP'] = json_encode($sfpData);
        }

        // Update the SFP device with validated data
        $sfp->update($data);

        // Log the update action
        log_action('info', 'Updating SFP device', ['id' => $id, 'data' => $data]);

        // Redirect to the index page with a success message
        return redirect()->route('sfp.index')->with('success', 'SFP Perangkat updated successfully.');
    }

    public function destroy($id)
    {
        $this->authorize('delete device data');
        try {
            // Find the resource by its ID
            $sfp = Perangkat::findOrFail($id);

            // Delete the resource
            $sfp->delete();

            // Log action if necessary
            log_action('info', 'SFP deleted successfully.', ['id' => $id]);

            // Redirect back with success message
            return redirect()->route('sfp.index')->with('success', 'SFP deleted successfully.');
        } catch (\Exception $e) {
            // Handle errors and redirect with error message
            log_action('error', 'Failed to delete SFP.', ['error' => $e->getMessage()]);
            return redirect()->route('sfp.index')->with('error', 'Failed to delete SFP.');
        }
    }
}
