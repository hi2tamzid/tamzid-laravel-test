<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    public function index()
    {
        $filePath = 'form_data.json';
        if (Storage::exists($filePath)) {
            $json = Storage::get($filePath);
            $existingData = json_decode($json, true);

        } else {
            $existingData = [];
        }

        return view('pages.index', compact('existingData'));
    }

    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'quantity' => ['required', 'integer'],
            'price' => ['required', 'numeric'],
        ]);

        $data = [
            ...$validated,
            'created_at' => Carbon::now(),
        ];

        $filePath = 'form_data.json';
        if (Storage::exists($filePath)) {
            $json = Storage::get($filePath);
            $existingData = json_decode($json, true);
        } else {
            $existingData = [];
        }

        $existingData[] = $data;

        Storage::put($filePath, json_encode($existingData, JSON_PRETTY_PRINT));

        return response()->json(['success' => true, 'message' => 'Product uploaded successfully!!', 'index' => sizeof($existingData), 'product' => $data]);
    }

    public function showData()
    {
        $filePath = 'form_data.json';

        if (Storage::exists($filePath)) {
            $json = Storage::get($filePath);
            $existingData = json_decode($json, true);
            if (! empty($existingData)) {
                usort($existingData, function ($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });
            }
        } else {
            $existingData = [];
        }

        return view('pages.index', compact('existingData'));
    }

    public function updateData(Request $request)
    {
        $validated = $request->validate([
            'index' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'quantity' => ['required', 'integer'],
            'price' => ['required', 'numeric'],
        ]);

        $filePath = 'form_data.json';
        if (Storage::exists($filePath)) {
            $json = Storage::get($filePath);
            $existingData = json_decode($json, true);
        } else {
            return response()->json(['success' => false, 'message' => 'No data found!!']);
        }

        $existingData[$validated['index']]['name'] = $validated['name'];
        $existingData[$validated['index']]['quantity'] = $validated['quantity'];
        $existingData[$validated['index']]['price'] = $validated['price'];

        Storage::put($filePath, json_encode($existingData, JSON_PRETTY_PRINT));

        return response()->json(['success' => true, 'message' => 'Product uploaded successfully!!']);
    }
}
