<?php

namespace App\Http\Controllers;

use App\Models\PcBuilds;
use App\Models\PcParts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceController extends Controller
{
    public function showAllBuilds()
    {
        // Eager load the parts with their details through the pivot table
        $builds = PcBuilds::with(['parts' => function($query) {
            $query->withPivot('quantity'); // Include the quantity from the pivot table
        }])->get();

        // Calculate total price if not already calculated (optional)
        $builds->each(function ($build) {
            if ($build->total_price == 0) {
                $build->total_price = $build->parts->sum(function ($part) {
                    return $part->price * $part->pivot->quantity;
                });
                $build->save();
            }
        });

        return view('list_builds', compact('builds'));
    }

    public function getDetailPart($id)
    {
        $build = PcBuilds::with('parts')->findOrFail($id);
        return response()->json($build);
    }

    public function addNewBuild(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'parts' => 'required|array',
            'parts.*.type' => 'required|string',
            'parts.*.name' => 'required|string',
            'parts.*.price' => 'required|numeric|min:0',
            'parts.*.quantity' => 'required|integer|min:1',
            'parts.*.picture' => 'nullable|image',
        ]);        

        DB::beginTransaction();

        try {
            // Create new PC build
            $pcBuild = PcBuilds::create([
                'name' => $request->name,
                'description' => $request->description,
                'total_price' => $request->total_price,
            ]);

            foreach ($request->parts as $partData) {
                if (isset($partData['picture'])) {
                    $file = $partData['picture'];
                    $fileName = $file->getClientOriginalName();
                    $storeResult = $file->move('storage/partPicture', $fileName);
                    $filePath = 'storage/partPicture/' . $fileName;
                } else {
                    $filePath = null;
                }
            
                $part = PcParts::create([
                    'type' => $partData['type'],
                    'name' => $partData['name'],
                    'price' => $partData['price'],
                    'picture' => $filePath,
                ]);
            
                // Attach part with quantity
                $pcBuild->parts()->attach($part->id, ['quantity' => $partData['quantity']]);
            }            

            DB::commit();
            return redirect()->route('showAllBuilds')->with('success', 'New build added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create build', 'details' => $e->getMessage()], 500);
        }
    }

    public function deleteBuild($id)
    {
        $pcBuild = PcBuilds::findOrFail($id);
        
        // Get all related part IDs before detaching
        $partIds = $pcBuild->parts->pluck('id')->toArray();

        // Detach all related parts
        $pcBuild->parts()->detach();
        
        // Delete the PC build
        $pcBuild->delete();

        // Delete parts that were associated with this build
        PcParts::whereIn('id', $partIds)->delete();

        return response()->json(['message' => 'PC Build and its parts deleted successfully']);
    }
}
