<?php 

namespace App\Services;

use App\Models\Reservation;
use App\Models\Archive;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Auth;

class Checkmyavailability{

	public static function checkNow(){
        
        $tempStat = 'available';
        $dateNow = \Carbon\Carbon::now();
        $id = Auth::id();

        $reservations = Reservation::where('driver_id',$id)
            ->orWhere('reserved_id',$id)
            ->orWhereHas('passengers',function ($query) use ($id){
                return $query->where('user_id',$id);
            })
            ->with('reservationschedules')
            ->get();
        
        if(count($reservations) > 0){
            foreach ($reservations as $reservation) {            
                if ($reservation->reservationschedules[0]->allday == true) {                    
                    $startdb = $reservation->reservationschedules[0]->startduration;
                    $enddb = $reservation->reservationschedules[0]->endduration;  
                    if ($dateNow->between($startdb, $enddb)){
                        $tempStat = 'unavailable';
                    }        
                }elseif ($reservation->reservationschedules[0]->allday == false) {
                    foreach ($reservation->reservationschedules as $reservationschedule) {
                        $startdb = $reservationschedule->startduration;
                        $enddb = $reservationschedule->endduration;
                        if ($dateNow->between($startdb, $enddb)) {
                            $tempStat = 'unavailable';
                            break 2;
                        }                        
                    }
                }            
            }
        }    

        return $tempStat;

    }
    public static function checkUser($employee){
        $msg = 'not included';

        $reservations = Reservation::where('approvedstatus_id',1)
        ->with(['reservationschedules','passengers','getreservationDriver'])
        ->get();
        
        if(count($reservations)){
            foreach ($reservations as $reservation) {        
                if(in_array($employee, $reservation->passengers->pluck('user_id')->toArray() )){
                    $msg = 'included';
                }elseif($employee == $reservation->getreservationDriver->id){
                    $msg = 'included';
                }elseif($employee == $reservation->reserved_id){
                    $msg = 'included';
                }elseif($employee == Auth::id()){
                    $msg = 'included';
                }                                 
            }
        }
        return $msg;

    }
    public static function checkUsers($employees){

        $msg = 'not included';

        $reservations = Reservation::where('approvedstatus_id',1)
        ->with(['reservationschedules','passengers','getreservationDriver'])
        ->get();

        if(count($reservations) > 0){
            foreach ($reservations as $reservation) {
                foreach ($employees as $key => $employee) {

                    if(in_array($employee, $reservation->passengers->pluck('user_id')->toArray() )){
                        $msg = 'included';
                    }elseif($employee == $reservation->getreservationDriver->id){
                        $msg = 'included';
                    }elseif($employee == $reservation->reserved_id){
                        $msg = 'included';
                    }elseif($employee == Auth::id()){
                        $msg = 'included';
                    }
                }            
            }
        }
        return $msg;
    }
        
}
