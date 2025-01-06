
<table id="reservationTable" class="table  mtable ">
  <thead>
    <tr>   
      <th scope="col">Status</th>   
      <th scope="col">Person In Charge</th>
      <th scope="col">Vehicle</th>      
      <th scope="col">Destination</th>    
      <th scope="col">Date</th>      
      <th scope="col" class="tblActions"></th>
    </tr>
  </thead>
  <tbody>
          @forelse($reservations as $reservation)            
              
                @if($reservation->reservationstatus != null)
                  @if($reservation->errand == true)
                  <td class="errand text-warning"><strong>
                    Errand
                  @else
                  <td class="{{$reservation->reservationstatus->slug}}"><strong>
                  {{$reservation->reservationstatus->name}}
                    @if($reservation->approvedstatus_id == 1)
                    <small class="spFull text-success">* by {{$reservation->approvedby->FirstName}} {{$reservation->approvedby->LastName}}</small>
                      
                    @endif
                  @endif
                @else
                <td><strong>
                @endif

                
              </strong>
              </td>
              <td>
                  @if($reservation->createdby != null)
                  {{$reservation->createdby->FirstName}} {{$reservation->createdby->LastName}}
                  @else
                  -
                  @endif
              </td>
              <td>
                @if($reservation->vehiclereserve != null)
                  {{$reservation->vehiclereserve->model }} {{$reservation->vehiclereserve->make }} {{$reservation->vehiclereserve->platenumber}}

                  @if($reservation->recommendedby_id != $reservation->approvedby_id)

                    @if($reservation->reserved_id != $reservation->recommendedby_id)
                      <small class="spFull text-recommend">* Recommended by {{$reservation->recommendedby->FirstName}} {{$reservation->recommendedby->LastName}}</small>                    
                    @endif

                    @if($reservation->fuelload == true)
                    <small class="spFull text-recommend">* With Fuel</small>
                    @elseif($reservation->fuelload === 0)
                    <small class="spFull text-danger">* Without Fuel</small>
                    @endif
                  @endif

                @else
                <small class="text-danger">*To be set by the dispatcher / admin</small>
                @endif
              </td>
              
              <td>
                  {{$reservation->location}}
              </td>

              <td class="evntTime">
           
                @if($reservation->reservationschedules != null && count($reservation->reservationschedules) > 0)     

                  @if($reservation->errand == true)
                  <label>Date:</label> <strong> {{date('l F j, Y', strtotime($reservation->reservationschedules->first()->startduration))}}</strong><br/>
                  <label>Start Time:</label>  <strong>{{date('h:i A', strtotime($reservation->reservationschedules->first()->startduration))}} </strong>

                  @if($reservation->reservationschedules->first()->endduration != null || $reservation->isdone == true)
                  <br/>
                  <label>End Time:</label>  <strong>{{date('h:i A', strtotime($reservation->reservationschedules->first()->endduration))}} </strong>

                  @else

                  - <span class="text-danger"><i>Ongoing</i></span>
                  @endif

                  @else
                  



                  @if($reservation->reservationschedules->first()->allday == false)

                  <label>Duration:</label> <strong>                  


                    @if(date('j', strtotime($reservation->reservationschedules->first()->startduration)) == date('j', strtotime($reservation->reservationschedules->last()->endduration)))

                      @if(date('F', strtotime($reservation->reservationschedules->first()->startduration)) == date('F', strtotime($reservation->reservationschedules->last()->endduration)))
                        {{date('F j, Y', strtotime($reservation->reservationschedules->first()->startduration))}}
                      @else
                        {{date('F j, Y', strtotime($reservation->reservationschedules->first()->startduration))}} - {{date('F j, Y', strtotime($reservation->reservationschedules->last()->endduration))}}
                      @endif
                      
                    @else

                      {{date('F j, Y', strtotime($reservation->reservationschedules->first()->startduration))}} - {{date('F j, Y', strtotime($reservation->reservationschedules->last()->endduration))}}

                    @endif   

                  </strong> <br/>

                  

                    @if(date('j', strtotime($reservation->reservationschedules->first()->startduration)) == date('j', strtotime($reservation->reservationschedules->last()->endduration)))
                      @if(date('F', strtotime($reservation->reservationschedules->first()->startduration)) == date('F', strtotime($reservation->reservationschedules->last()->endduration)))
                        <label>Date:</label> <strong> {{date('l', strtotime($reservation->reservationschedules->first()->startduration))}}
                      @else
                        <label>Every:</label> <strong> {{date('l', strtotime($reservation->reservationschedules->first()->startduration))}} - {{date('l', strtotime($reservation->reservationschedules->last()->endduration))}}

                      @endif
          
                    @else
                        @if($reservation->reservationschedules->first()->recurrencetimes > 1)
                        <label>Every:</label> <strong>
                        @else
                        <label>Date:</label> <strong>
                        @endif
                       {{date('l', strtotime($reservation->reservationschedules->first()->startduration))}} - {{date('l', strtotime($reservation->reservationschedules->last()->endduration))}}

                    @endif   

                  </strong>
                    <br/>
                  @endif





                  @foreach($reservation->reservationschedules as $resched)


                    @if($resched->allday == true)   

 
                    @if(date('j', strtotime($resched->startduration)) == date('j', strtotime($resched->endduration)))

                       <label>Date:</label>   <strong>{{date('l F j, Y', strtotime($resched->startduration))}}</strong>
                    @else

                      <label>Start Date:</label>  <strong>{{date('l F j, Y', strtotime($resched->startduration))}}</strong> <br/>
                            
                      <label>End Date:</label>   <strong>{{date('l F j, Y', strtotime($resched->endduration))}}</strong>

                    @endif            
                    


                    <input type="hidden" value="{{$resched->startduration}}" class="startTemp">
                    <input type="hidden" value="{{$resched->endduration}}" class="endTemp">
                    @elseif($resched->allday == false)
                
                        

                         @if ($loop->first)                       
                       <label>Start Time:</label>  <strong>{{date('h:i A', strtotime($resched->startduration))}} </strong><br/>
                       <input type="hidden" value="{{$resched->startduration}}" class="startTemp">
                          @endif                   

                        @if($resched->endduration != null)
                          @if ($loop->last)
                       <label>End Time:</label> <strong>{{date('h:i A', strtotime($resched->endduration))}}</strong>
                       <input type="hidden" value="{{$resched->endduration}}" class="endTemp">
                          @endif     
                        @else
                        <label>End Time:</label> <strong> -- </strong>
                       <input type="hidden" value="{{$resched->endduration}}" class="endTemp">
                        @endif                  
                          
                          
                          
                           
                    @endif
                  @endforeach   
                  @endif <!--end errand-->             
                @endif
                
              </td>
              
              <td>
             @if($reservation->errand == true)
             @if($reservation->isdone == false)
               @canany(['isAdminOfficer','isAdmin','isDispatcher','isSuperAdmin'])
               <a href="#" title="End Errand" class="btn btn-outline-success" data-toggle="modal"
               data-target="#errandendmodal"
               data-id="{{$reservation->id}}"
               data-title="{{$reservation->location}}"
               data-action="{{route('end.errand')}}"
               ><i class="fas fa-calendar-check"></i> <span>End Errand</span> </a>
               @else
                @if(\Auth::id() == $reservation->reserved_id || \Auth::id() == $reservation->driver_id)
                  <a href="#" title="End Errand" class="btn btn-outline-success" data-toggle="modal"
                 data-target="#errandendmodal"
                 data-id="{{$reservation->id}}"
                 data-title="{{$reservation->location}}"
                 data-action="{{route('end.errand')}}"
                 ><i class="fas fa-calendar-check"></i> <span>End Errand</span> </a>
                @endif
               @endcan
             @endif

             @can('delete', $reservation)
               <a href="#" title="Delete Reservation" class="btn btn-outline-danger" data-toggle="modal" data-target="#confirmationModal" data-title="{{$reservation->location}} reserved by {{$reservation->createdby->FirstName}} {{$reservation->createdby->LastName}}" data-action="{{route('reservation.destroy',[$reservation->id])}}">
                
                <i class="fas fa-trash-alt"></i> <span>Delete</span>

              </a>
              @endcan
             @else
              @canany(['isAdminOfficer','isAdmin','isDispatcher','isSuperAdmin'])



              @canany(['isAdminOfficer','isSuperAdmin'])
                @if($reservation->approvedstatus_id == 3 || $reservation->approvedstatus_id == 4)
                  <a href="#" title="Approve"  data-id="{{$reservation->id}}" 
                  data-cat="{{optional($reservation->reservationschedules->first())->allday ?? false}}" 
                  data-recurrence="{{ optional($reservation->reservationschedules->first())->recurrencetimes ?? null }}"               
                  data-recommended="{{$reservation->recommendedby_id}}" class="btn btn-outline-success btnApprove" 
                  data-toggle="modal" data-target="#approveModal" 
                  data-title="{{$reservation->location}} reserved by {{$reservation->createdby->FirstName}} {{$reservation->createdby->LastName}}" data-action="{{route('approveReservation.reservation')}}"><i class="fas fa-thumbs-up" aria-hidden="true"></i> <span>Approve</span></a>                 
                @endif
              @endcanany
              @canany(['isAdmin','isDispatcher','isSuperAdmin'])
                @if($reservation->approvedstatus_id == 4)
                  <a href="#" title="Recommend" data-id="{{$reservation->id}}" 
                    data-cat="{{ optional($reservation->reservationschedules->first())->allday ?? false }}" 
                    data-recurrence="{{ optional($reservation->reservationschedules->first())->recurrencetimes ?? null }}"  
                    class="btn btn-outline-info btnRecommend" 
                    data-toggle="modal" 
                    data-target="#recommendModal" 
                    data-title="{{$reservation->location}} reserved by {{$reservation->createdby->FirstName}} {{$reservation->createdby->LastName}}" 
                    data-action="{{route('recommendReservation.reservation')}}"><i class="fas fa-thumbs-up" aria-hidden="true"></i> <span>Recommend</span></a>    
                @endif
              @endcanany
              <a href="#" title="Disapprove" data-id="{{$reservation->id}}" data-cat="{{$reservation->reservationschedules ? $reservation->reservationschedules->first()->allday : false}}" 
                  class="btn btn-outline-warning" data-toggle="modal" data-target="#disapproveModal" data-title="{{$reservation->location}} reserved by {{$reservation->createdby->FirstName}} {{$reservation->createdby->LastName}}" data-action="{{route('disapproveReservation.reservation')}}"><i class="fas fa-thumbs-down" aria-hidden="true"></i> <span>Disapprove</span> 
                                   </a>     
              @endcanany



              @can('update', $reservation)

                  <a href="{{route('reservation.edit',[$reservation->id])}}" title="Edit Reservation" class="btn btn-outline-info">
                    <i class="far fa-edit"></i> <span>Edit</span>
              </a> 
              @endcan
              @can('delete', $reservation)
               <a href="#" title="Delete Reservation" class="btn btn-outline-danger" data-toggle="modal" data-target="#confirmationModal" data-title="{{$reservation->location}} reserved by {{$reservation->createdby->FirstName}} {{$reservation->createdby->LastName}}" data-action="{{route('reservation.destroy',[$reservation->id])}}">
                
                <i class="fas fa-trash-alt"></i> <span>Delete</span>

              </a>
              @endcan
             @endif
            <input type="hidden" class="viewpagelink" name="viewpagelink" data-target="{{route('reservation.show',[$reservation->id])}}">
            </td>
            </tr>

          
          
        
        @empty
        <tr>
          <td id="nocontent" colspan="100%">No Reservation at the moment</td>
        </tr>
        @endforelse
 </tbody>
</table>
