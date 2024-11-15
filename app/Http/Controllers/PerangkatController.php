<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $this->authorize('add device data');
        $request->validate([
            'NAME' => 'required|string|max:100',
            'HOST_NAME' => 'nullable|string|max:100',
            'TYPE' => 'required|in:SFP', // TYPE is restricted to SFP
            'SERIAL_NUMBER' => 'nullable|string|max:100',
            'IP_ADDRESS' => 'nullable|string|max:100',
            'LOCATION' => 'nullable|string|max:100',
            'PRODUCT_ID_DEVICE' => 'nullable|string|max:100',
            'JUMLAH_SFP_DICABUT' => 'nullable|integer',
            'STOCK' => 'nullable|integer',
            'CATEGORY' => 'nullable|string|max:100',
            'VENDOR' => 'nullable|string|max:100',
            'BRAND' => 'nullable|string|max:100',
            // Add other fields as needed
        ]);

        // Explicitly set TYPE to 'SFP' to ensure it's stored correctly
        $data = $request->all();
        $data['TYPE'] = 'SFP';

        Perangkat::create($data);

        return redirect()->route('sfp.index')->with('success', 'SFP Perangkat created successfully.');
    }
}