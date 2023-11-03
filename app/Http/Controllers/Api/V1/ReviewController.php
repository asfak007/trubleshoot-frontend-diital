<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HTTP;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\Api\V1\HandymanResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;


class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         $reviews = Review::with('customers')->where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->limit(15)
        ->get();

        if($reviews->isEmpty()){
            return Response::json([
                'success'   => false,
                'status'    => HTTP::HTTP_NOT_FOUND,
                'message'   => "No Reviews found",
            ],  HTTP::HTTP_NOT_FOUND); // HTTP::HTTP_OK
        }

        return Response::json([
            'success'   => true,
            'status'    => HTTP::HTTP_OK,
            'message'   => "Successfully authorized.",
            'data'      => [
                'reviews'  =>  $reviews,
               
                // 'services' => $services,
            ]
        ],  HTTP::HTTP_OK); // HTTP::HTTP_OK
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
}
