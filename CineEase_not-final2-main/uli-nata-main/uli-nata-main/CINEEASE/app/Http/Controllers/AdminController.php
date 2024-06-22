<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Movie;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;

class AdminController extends Controller
{
    public function index()
    {
        // Fetch all movies
        $movies = Movie::all();
        return view('admindash', compact('movies'));
    }

    public function manageUsers()
    {
        // Fetch all reserved and confirmed seats
        $bookings = Booking::whereIn('status', ['reserved', 'confirmed'])->get(['seatArrangement']);
        $reservedSeats = $bookings->flatMap(function ($booking) {
            return is_array($booking->seatArrangement) ? $booking->seatArrangement : json_decode($booking->seatArrangement, true);
        })->toArray();

        return view('admin.manage-users', compact('reservedSeats'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.manage-users')->with('success', 'User deleted successfully.');
    }
}
