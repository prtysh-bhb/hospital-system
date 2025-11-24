<?php

namespace App\Services\Admin;

use App\Models\Specialty;
use Exception;

class SpecialtysServices
{
    public function getList($request)
    {
        $query = Specialty::query();

        // Search filter
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // Status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Paginate results
        $data = $query->orderBy('id', 'DESC')->paginate(10);

        return [
            'data' => $data
        ];
    }
    public function destroy($id)
    {
        try {
            $specialty = Specialty::find($id);

            if (!$specialty) {
                return response()->json([
                    'status' => 404,
                    'msg' => 'Specialty not found.',
                ], 404);
            }
            $specialty->delete();

            return response()->json([
                'status' => 200,
                'msg' => 'Specialty deleted successfully!',
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'status' => 400,
                'msg' => 'Failed to delete specialty: ' . $ex->getMessage(),
            ], 400);
        }
    }
}
