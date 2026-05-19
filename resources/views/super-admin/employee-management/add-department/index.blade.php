@extends('layout.index')

@section('title', 'Dashboard')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <h5 class="mb-4">Add Department</h5>
        <form class="row g-3" method="POST" action="{{route('departments.store')}}">
        @csrf
            <div class="col-md-2">
                <label for="input13" class="form-label">Department Name</label>
                <div class="position-relative input-icon">
                    <input type="text" class="form-control" id="input13" placeholder="Department Name" name="name" required>
                    <span class="position-absolute top-50 translate-middle-y"><i class="material-icons-outlined fs-5">person_outline</i></span>
                </div>
            </div>  
            <div class="col-md-2">
                <div class="d-md-flex d-grid align-items-center gap-3 mt-4">
                    <button type="submit" class="btn btn-primary px-4 mt-1">Add</button> 
                </div>
            </div>
        </form>
    </div>
</div> 

<!-- Employee List -->
<div class="card">
    <div class="card-body">
         <h5 class="mb-4">Roles List</h5>
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Role Name</th> 
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($departments as $department)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $department->name }}</td> 
                            <td>
                               <a href="javascript:;"
                                class="btn btn-sm text-warning editDepartmentBtn"
                                data-id="{{ $department->id }}"
                                data-name="{{ $department->name }}"  >
                                <i class="lni lni-pencil-alt fs-6"></i>
                                </a>

                                <form action="{{ route('departments.destroy', $department->id) }}"
                                      method="POST"
                                      class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm text-danger"> <i class="lni lni-trash fs-6 "></i> 
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No employees found</td>
                        </tr>
                    @endforelse
                </tbody> 
            </table>
        </div>
    </div>
</div>
<!-- Edit User Modal -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editDepartmentForm" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Edit Department</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label>Department Name</label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>  
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>

      </form>
    </div>
  </div>
</div>

@endsection

