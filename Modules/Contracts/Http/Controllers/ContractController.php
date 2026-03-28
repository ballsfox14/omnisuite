<?php

namespace Modules\Contracts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Entities\Package;
use Modules\Contracts\Entities\Zone;
use App\Models\User;
use Illuminate\Support\Str;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'])->except(['publicSignForm', 'publicSignStore', 'publicSigned']);
    }

    public function index()
    {
        $contracts = Contract::with('creator', 'package', 'zone')->latest()->paginate(10);
        return view('contracts::index', compact('contracts'));
    }

    public function create()
    {
        $packages = Package::all();
        $zones = Zone::all();
        return view('contracts::create', compact('packages', 'zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'package_id' => 'nullable|exists:packages,id',
            'zone_id' => 'nullable|exists:zones,id',
            'price' => 'nullable|numeric',
            'signature' => 'nullable|string',
        ]);

        $data = [
            'client_name' => $request->client_name,
            'status' => 'pendiente',
            'created_by' => auth()->id(),
            'package_id' => $request->package_id,
            'zone_id' => $request->zone_id,
            'price' => $request->price,
        ];

        if ($request->filled('signature')) {
            $data['signature'] = $request->signature;
            $data['signature_date'] = now();
            $data['signature_method'] = 'panel';
        }

        Contract::create($data);

        return redirect()->route('contracts.index')->with('success', 'Contrato creado correctamente.');
    }

    public function show(Contract $contract)
    {
        $contract->load('creator', 'package', 'zone');
        return view('contracts::show', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        $packages = Package::all();
        $zones = Zone::all();
        return view('contracts::edit', compact('contract', 'packages', 'zones'));
    }

    public function update(Request $request, Contract $contract)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'status' => 'required|in:pendiente,en_revision,aprobado,rechazado',
            'package_id' => 'nullable|exists:packages,id',
            'zone_id' => 'nullable|exists:zones,id',
            'price' => 'nullable|numeric',
            'signature' => 'nullable|string',
        ]);

        $data = $request->only('client_name', 'status', 'package_id', 'zone_id', 'price');

        if ($request->filled('signature')) {
            $data['signature'] = $request->signature;
            $data['signature_date'] = now();
            $data['signature_method'] = 'panel';
        }

        $contract->update($data);

        return redirect()->route('contracts.index')->with('success', 'Contrato actualizado correctamente.');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();
        return redirect()->route('contracts.index')->with('success', 'Contrato eliminado correctamente.');
    }

    public function getPrice(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'zone_id' => 'required|exists:zones,id',
        ]);

        $price = \DB::table('package_zone')
            ->where('package_id', $request->package_id)
            ->where('zone_id', $request->zone_id)
            ->value('price');

        return response()->json(['price' => $price]);
    }

    public function generatePublicSigningLink(Contract $contract)
    {
        if (!$contract->signature_token) {
            $contract->signature_token = Str::random(32);
            $contract->save();
        }
        $url = route('contracts.public.sign', $contract->signature_token);
        return response()->json(['url' => $url]);
    }

    public function publicSignForm($token)
    {
        $contract = Contract::where('signature_token', $token)->firstOrFail();
        if ($contract->signed_at) {
            return view('contracts::public.already-signed', compact('contract'));
        }
        return view('contracts::public.sign', compact('contract'));
    }

    public function publicSignStore(Request $request, $token)
    {
        $contract = Contract::where('signature_token', $token)->firstOrFail();
        if ($contract->signed_at) {
            return redirect()->route('contracts.public.signed', $token)->with('error', 'Este contrato ya fue firmado.');
        }

        $request->validate([
            'signature' => 'required|string',
        ]);

        $contract->update([
            'signature' => $request->signature,
            'signature_date' => now(),
            'signed_at' => now(),
            'signature_method' => 'public',
        ]);

        return redirect()->route('contracts.public.signed', $token)->with('success', 'Contrato firmado correctamente.');
    }

    public function publicSigned($token)
    {
        $contract = Contract::where('signature_token', $token)->firstOrFail();
        return view('contracts::public.signed', compact('contract'));
    }
}