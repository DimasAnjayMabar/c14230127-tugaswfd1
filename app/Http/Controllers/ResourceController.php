<?php

namespace App\Http\Controllers;

use App\Models\PcBuilds;
use App\Models\PcParts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceController extends Controller
{   
    // Show all builds
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

    // Get detail parts
    public function getDetailPart($id)
    {
        $build = PcBuilds::with('parts')->findOrFail($id);
        return response()->json($build);
    }

    // Load initial data in edit form
    public function showEditForm($id)
    {
        $build = PcBuilds::with(['parts' => function($query) {
            $query->withPivot('quantity');
        }])->findOrFail($id);
        
        return view('edit_build', compact('build'));
    }

    // Add new build
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

    // Delete build
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

    // Edit build
    public function editBuild(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_price' => 'nullable|numeric',
            'parts' => 'array',
            'parts.*.type' => 'required|string',
            'parts.*.name' => 'required|string',
            'parts.*.price' => 'required|numeric',
            'parts.*.quantity' => 'required|integer|min:1',
            'parts.*.picture' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
       
        try {
            $build = PcBuilds::findOrFail($id);
            $build->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            $existingPartIds = $build->parts()->pluck('pc_parts.id')->toArray();
                $updatedPartIds = [];

                foreach ($request->parts as $index => $partData) {
                    // Handle file upload for each part
                    if (isset($partData['picture']) && is_array($partData['picture'])) {
                        $file = $partData['picture'][0]; // Use the first image from the array
                        $fileName = $file->getClientOriginalName();
                        $filePath = $file->move('storage/partPicture', $fileName); // Store the file
                        $filePath = 'storage/partPicture/' . $fileName; // Ensure correct path format
                    } else {
                        $filePath = $part->picture ?? null; // Keep existing picture if no new file uploaded
                    }
                
                    // Handle the part update or creation
                    if (isset($partData['id']) && in_array($partData['id'], $existingPartIds)) {
                        // Update existing part
                        $part = PcParts::findOrFail($partData['id']);
                        $part->update([
                            'type' => $partData['type'],
                            'name' => $partData['name'],
                            'price' => $partData['price'],
                        ]);
                
                        // Update picture if a new one is provided
                        if ($filePath) {
                            $part->update(['picture' => $filePath]);
                        }
                
                        // Update quantity in pivot table
                        $build->parts()->updateExistingPivot($part->id, ['quantity' => $partData['quantity']]);
                    } else {
                        // Create new part
                        $part = PcParts::create([
                            'type' => $partData['type'],
                            'name' => $partData['name'],
                            'price' => $partData['price'],
                            'picture' => $filePath, // Save the image path
                        ]);
                
                        // Attach part with quantity
                        $build->parts()->attach($part->id, ['quantity' => $partData['quantity']]);
                    }
                
                    $updatedPartIds[] = $part->id;
                }
                

            // Detach parts that were removed
            $partsToDetach = array_diff($existingPartIds, $updatedPartIds);
            if (!empty($partsToDetach)) {
                $build->parts()->detach($partsToDetach);
                // Delete the detached parts
                PcParts::whereIn('id', $partsToDetach)->delete();
            }
            
            // Recalculate total price
            $build->total_price = $build->parts->sum(function ($part) {
                return $part->price * $part->pivot->quantity;
            });
            $build->save();

            DB::commit();
            return response()->json(['message' => 'Build updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
