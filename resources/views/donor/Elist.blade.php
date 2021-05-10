@extends('voyager::master')

@section('css')
    <style>
        .user-email {
            font-size: .85rem;
            margin-bottom: 1.5em;
        }
		.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #e91e63;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #4caf50;
}

input:focus + .slider {
  box-shadow: 0 0 1px #4caf50;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
    </style>
@stop

@section('content')
	<div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-droplet"></i> Imported Donors
        </h1>
	<!--	<a href="" class="btn btn-success btn-add-new">
		<i class="voyager-plus"></i> <span>Add New</span>
		</a>
		<a class="btn btn-danger" id="bulk_delete_btn"><i class="voyager-trash"></i> <span>Bulk Delete</span></a>-->
		
		<a class="btn btn-info" id="bulk_export_btn" href="{{ route('admin.donor.export' )}}"><i class="voyager-cloud-download"></i> <span>Export Donors</span></a>
			<a class="btn btn-info" id="bulk_export_btn" href="{{ route('admin.import' )}}"><i class="voyager-upload"></i> <span>Import Donors</span></a>



<!-- /.modal -->

<script>
window.onload = function () {
    // Bulk delete selectors
    var $bulkDeleteBtn = $('#bulk_delete_btn');
    var $bulkDeleteModal = $('#bulk_delete_modal');
    var $bulkDeleteCount = $('#bulk_delete_count');
    var $bulkDeleteDisplayName = $('#bulk_delete_display_name');
    var $bulkDeleteInput = $('#bulk_delete_input');
    // Reposition modal to prevent z-index issues
    $bulkDeleteModal.appendTo('body');
    // Bulk delete listener
    $bulkDeleteBtn.click(function () {
        var ids = [];
        var $checkedBoxes = $('#dataTable input[type=checkbox]:checked').not('.select_all');
        var count = $checkedBoxes.length;
        if (count) {
            // Reset input value
            $bulkDeleteInput.val('');
            // Deletion info
            var displayName = count > 1 ? 'Users' : 'User';
            displayName = displayName.toLowerCase();
            $bulkDeleteCount.html(count);
            $bulkDeleteDisplayName.html(displayName);
            // Gather IDs
            $.each($checkedBoxes, function () {
                var value = $(this).val();
                ids.push(value);
            })
            // Set input value
            $bulkDeleteInput.val(ids);
            // Show modal
            $bulkDeleteModal.modal('show');
        } else {
            // No row selected
            toastr.warning('You haven&#039;t selected anything to delete');
        }
    });
}
</script>
                                                                                                                               </div>
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
      @if(Session::has('flash_message'))
		<div class="alert alert-success">
            <div>
                {{ Session::get('flash_message') }}
            </div>
		</div>
        @endif
 
        @if($errors->any())
	<div class="alert alert-danger">	
            <div>
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            </div>
        @endif
		
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        
                        <div class="table-responsive">
                           <table id="dataTable" class="table table-striped table-bordered table-hover" style="width:100%">
								<thead>
									<tr>
									
										<th>#</th>
										
										<th>Donor</th>
										<th>Mobile Number</th>
										<th>created By</th>
										<th>Blood Group</th>
										<th>Status</th>
									<!--	<th>Action</th>-->
									</tr>
								</thead>
								<tbody>
								@foreach($all as $row)
									<tr>
									
										<td>{{ $loop->iteration }}</td>
										<td>
										   
										   <h6 style="text-transform: capitalize;"> {{ $row->name }}</h6>
										    </td>								<td>{{ $row->alternate_mobile_number }}</td>
										<td>{{ $row->user->name }}</td>
										<td>{{ $row->blood_group }}</td>
										<td>
										
										{{ $row->can_donate }}
									
										
										</td>
									<!--<td><a  href="{{ route('admin.donor.edit', $row->id )}}">EDIT</a>	
                                           | <a href = "{{ route('admin.donor.destroy', $row->id )}}" onclick="return confirm('Are you sure?')">DELETE</a>
										 
										</td>-->
									</tr>
								@endforeach
								</tbody>
								<tfoot>
									<tr>
								
										<th>#</th>
									<th>Donor</th>
										<th>created By</th>
										<th>Blood Group</th>
										<th>Status</th>
									<!--	<th>Action</th>-->
									</tr>
								</tfoot>
							</table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

  
@stop

@section('javascript')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
 function del_plan(v) {


	Swal.fire({
		  title: "Are you sure?",
		  text: "You will not be able to recover this plan!",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		  confirmButtonText: "Yes, delete it!",
		  cancelButtonText: "No, cancel please!",
	},
	  function(inputValue){
  //Use the "Strict Equality Comparison" to accept the user's input "false" as string)
  if (inputValue===false) {
    swal("Well done!");
    console.log("Do here everything you want");
  } else {
    swal("Oh no...","press CANCEL please!");
    console.log("The user says: ", inputValue);
  }
	});



}


jQuery(document).ready(function($){

	$('.status').on('change', function(e) {
		
		if(jQuery(this).is(':checked')){
			var v = 1;
		}else{
			var v = 0;
		}
		var id = $(this).data('id');
	//	return false;
    $.ajax({
        method: 'POST',
        url: "{{ route('ajax.request.status')}}",
        data: {
            status: v,id:id
        },
        dataType: 'json',
        success: function(res){
            if(res == 1){
				Swal.fire({
				  title: 'Success',
				  text: 'Plan status changed Successfully!!!',
				  icon: 'success',
				});
			}else{
				Swal.fire({
				  title: 'Error!',
				  text: 'SomeThing went wrong!!!',
				  icon: 'error',
				  confirmButtonText: 'Try Again Later'
				});
			}
        }
    });
});
});
</script>

@stop
