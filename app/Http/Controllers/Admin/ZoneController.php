<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;


class ZoneController extends Controller
{
    public function addZone(){
        return view('admin.page.zone.add');
    }
    public function zonelist(){
        $zones = Zone::select(['id', 'name','status'])
            ->withCount('services')
            ->get();
        return view('admin.page.zone.list',compact('zones'));
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:zones|max:191',
                'coordinates' => 'required',
            ]);

            $value = $request->coordinates;

            $points = [];
            $lastLat = null;
            $lastLng = null;

            foreach (explode('),(', trim($value, '()')) as $index => $single_array) {
                $coords = explode(',', $single_array);
                $lat = (float) $coords[0];
                $lng = (float) $coords[1];

                if ($index === 0) {
                    $lastLat = $lat;
                    $lastLng = $lng;
                }

                $points[] = [$lat, $lng];
            }

            // Check if the last point is the same as the first point to close the polygon
            if ($lastLat !== null && $lastLng !== null) {
                $points[] = [$lastLat, $lastLng];
            }

            $polygon = $this->createPolygon($points);

            // Convert the JSON-encoded coordinates to MySQL-compatible format
            $polygonString = "POLYGON((";
            foreach ($polygon['coordinates'][0] as $point) {
                $polygonString .= $point[0] . " " . $point[1] . ",";
            }
            $polygonString .= $polygon['coordinates'][0][0][0] . " " . $polygon['coordinates'][0][0][1] . "))";
            $zoneExists = Zone::where('name', $request->name)->exists();
            if ($zoneExists) {
                Session::flash('toaster', ['status' => 'error', 'message' => 'Zone already exists.']);
                return back();
            }

            // Save the polygon in the database or perform any other action as needed
            // For example, if you have a 'Zone' model, you can do this:
            $zone = new Zone();
            $zone->name = $request->name;
            $zone->coordinates = DB::raw("ST_GeomFromText('" . $polygonString . "')");
            $zone->save();

            Session::flash('toaster', ['status' => 'success', 'message' => 'Zone added successfully']);
            return redirect()->route('list.zone');

        }catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('toaster', [
                    'status' => 'error',
                    'message' => 'Failed to add new zone.',
                    'errors' => $e->errors()
                ]);
        }


    }

    private function createPolygon($points)
    {
        $area = 0;
        $count = count($points);

        for ($i = 0; $i < $count - 1; $i++) {
            $area += ($points[$i + 1][0] - $points[$i][0]) * ($points[$i + 1][1] + $points[$i][1]);
        }

        // If the area is negative, reverse the order of the points
        if ($area < 0) {
            $points = array_reverse($points);
        }

        $polygon = [];
        foreach ($points as $point) {
            $polygon[] = [
                (float) $point[1], // Longitude
                (float) $point[0], // Latitude
            ];
        }

        return [
            'type' => 'Polygon',
            'coordinates' => [$polygon],
        ];
    }


    public function zoneEdit( $id)
    {

        $zone = Zone::selectRaw("*, ST_AsText(coordinates) as coordinates_text, ST_AsText(ST_Centroid(`coordinates`)) as center")->findOrFail($id);

        $coordinatesString = '';

        if (preg_match('/POLYGON\s*?\(\((.*?)\)\)/', $zone->coordinates_text, $matches)) {
            $wkt_coordinates = $matches[1];
            $coordinates = [];
            foreach (explode(',', $wkt_coordinates) as $coord) {
                list($lat, $lng) = explode(' ', trim($coord));
                $coordinates[] = "($lng, $lat)";
            }
            $coordinatesString = implode(',', $coordinates);
        }

        $zone->coordinates_json = $coordinatesString;


        return view('admin.page.zone.edit', compact('zone'));

    }

    public function update(Request $request, $id)
    {

        try {
            $request->validate([
                'name' => 'required|unique:zones,name,' . $id . '|max:191',
            ]);

            $zone = Zone::find($id);
            if (!$zone) {
                return back()->with('error', 'Zone not found.');
            }

            $zone->name = $request->name;

            if ($request->has('coordinates') && !empty($request->coordinates)) {

                $value = $request->coordinates;
                $points = [];
                $lastLat = null;
                $lastLng = null;

                foreach (explode('),(', trim($value, '()')) as $index => $single_array) {
                    $coords = explode(',', $single_array);
                    $lat = (float) $coords[0];
                    $lng = (float) $coords[1];

                    if ($index === 0) {
                        $lastLat = $lat;
                        $lastLng = $lng;
                    }

                    $points[] = [$lat, $lng];
                }

                // Check if the last point is the same as the first point to close the polygon
                if ($lastLat !== null && $lastLng !== null) {
                    $points[] = [$lastLat, $lastLng];
                }

                $polygon = $this->createPolygon($points);
                $polygonString = "POLYGON((";
                foreach ($polygon['coordinates'][0] as $point) {
                    $polygonString .= $point[0] . " " . $point[1] . ",";
                }
                $polygonString .= $polygon['coordinates'][0][0][0] . " " . $polygon['coordinates'][0][0][1] . "))";

                $zone->coordinates = DB::raw("ST_GeomFromText('" . $polygonString . "')");
            }

            $zone->save();
            Session::flash('toaster', ['status' => 'success', 'message' => 'Zone update successfully.']);
            return redirect()->route('list.zone');

        }catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('toaster', [
                    'status' => 'error',
                    'message' => 'There were some validation errors.',
                    'errors' => $e->errors()
                ]);
        }

    }



    public function destroy( $id)
    {
        try {

            // Find the zone by ID
            $zone = Zone::select(['id', 'name', 'status'])
                ->findOrFail($id);

            // Delete the zone and its related data (categories and services)
//            $zone->categories()->delete();
//            $zone->services()->delete();
            $zone->delete();

            // Delete the zone and its related data (if any)


            Session::flash('toaster', ['status' => 'success', 'message' => 'Zone deleted successfully!']);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the deletion process
            Session::flash('toaster', ['status' => 'error', 'message' => 'Failed to delete zone!']);
        }

        return redirect()->route('list.zone');
    }


}
