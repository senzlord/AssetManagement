<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Perangkat;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SfpExport;

class PerangkatController extends Controller
{
    public function sfpIndex(Request $request)
    {
        $this->authorize('view device data');

        // Filter perangkat where TYPE is 'SFP', with optional search
        $search = $request->input('search');
        $sfps = Perangkat::where('TYPE', 'SFP')
                    ->where(function ($query) use ($search) {
                        $query->where('NAME', 'like', "%$search%")
                              ->orWhere('BRAND', 'like', "%$search%")
                              ->orWhere('SERIAL_NUMBER', 'like', "%$search%")
                              ->orWhere('HOST_NAME', 'like', "%$search%");
                    })
                    ->paginate(10);

        return view('sfp.index', compact('sfps'));
    }

    public function exportSfp()
    {
        return Excel::download(new SfpExport, 'sfp-data.xlsx');
    }

    public function createSfp()
    {
        $latestId = Perangkat::latest('PERANGKAT_ID')->value('PERANGKAT_ID') ?? 0;
        $nextId = $latestId + 1;

        return view('sfp.create', compact('nextId'));
    }

    public function storeSfp(Request $request)
    {
        // Authorize the action
        $this->authorize('add device data');

        // Define validation rules
        $rules = [
            'LOCATION' => ['required', 'string', 'max:100'],
            'VENDOR' => ['required', 'string', 'max:100'],
            'PRODUCT_ID_DEVICE' => ['required', 'string', 'max:100'],
            'SERIAL_NUMBER' => ['required', 'string', 'max:100'],
            'HOST_NAME' => ['required', 'string', 'max:100'],
            'IP_ADDRESS' => ['required', 'string', 'ip'],
            'JUMLAH_SFP_DICABUT' => ['required', 'integer', 'min:0'],
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
        $data['NAME'] = 'Perangkat SFP';

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
        // Fetch the SFP device by ID
        $sfp = Perangkat::where('TYPE', 'SFP')
                        ->findOrFail($id);

        // Return the edit view with the SFP data
        return view('sfp.edit', compact('sfp'));
    }

    public function updateSfp(Request $request, $id)
    {
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

        // Log the update action
        log_action('info', 'Updating SFP device', ['id' => $id, 'data' => $validator->validated()]);

        // Update the SFP device with validated data
        $sfp->update($validator->validated());

        // Redirect to the index page with a success message
        return redirect()->route('sfp.index')->with('success', 'SFP Perangkat updated successfully.');
    }
}