@extends('master')
@section('main-section')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">{{isset($data) ? 'Update User' : 'Add User'}}</h4>
            
            @foreach($errors->all() as $a)
            <p style="color:red;">{{$a}}<p>
            @endforeach
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">

                <div class="tab-content">
                    <div class="tab-pane show active" id="input-types-preview">
                        <div class="row">
                            <div class="col-lg-12">
                                @if(isset($data))
                                <form method="post" action="/update" class="needs-validation" novalidate>
                                    @else
                                    <form method="post" action="/addrole" class="needs-validation" novalidate>
                                        @endif
                                        @csrf
                                        @if(isset($data))
                                        <input type="hidden" value="{{$data->id}}" name="roleid" />
                                        @endif
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom01">Name</label>
                                            <input type="text" name="username" value="{{isset($data->name) ? $data->name : ''}}" class="form-control" id="validationCustom01" required>
                                            <div class="invalid-feedback">
                                                Please enter a name.
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom02">Email</label>
                                            <input type="email" name="email" value="{{isset($data->email) ? $data->email : ''}}" class="form-control" id="validationCustom02" placeholder="Enter Email" required>
                                            <!-- <div class="valid-feedback">
                                                                Looks good!
                                                            </div> -->
                                            <div class="invalid-feedback">
                                                Please enter a email.
                                            </div>
                                        </div>
                                        @if(isset($data))
                                        @else
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom03">Password</label>
                                            <div class="input-group input-group-merge">
                                                <input type="password" class="form-control" id="validationCustom03" placeholder="Enter Password" required>
                                                <div class="input-group-text" data-password="false">
                                                    <span class="password-eye"></span>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Please enter a email.
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom04">User Role</label>
                                            @if(isset($data->user_role))
                                            <select name="userrole" class="form-select mb-3" for="validationCustom04" value="{{isset($data->user_role) ? $data->user_role : ''}}">
                                                <option value="Company User" {{($data->user_role == "Company User") ? 'selected' : ''}}>Company User</option>
                                                <option value="Company Admin" {{($data->user_role == "Company Admin") ? 'selected' : ''}}>Company Admin</option>
                                                <option value="Super Admin" {{($data->user_role == "Super Admin") ? 'selected' : ''}}>Super Admin</option>
                                            </select>
                                            @else
                                            <select name="userrole" class="form-select mb-3" for="validationCustom04">
                                                <option value="Company User">Company User</option>
                                                <option value="Company Admin">Company Admin</option>
                                                <option value="Super Admin">Super Admin</option>
                                            </select>
                                            @endif
                                            <div class="invalid-feedback">
                                                Please enter a email.
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" type="submit">{{isset($data) ? 'Update User' : 'Save User'}}</button>
                                    </form>
                            </div> <!-- end col -->
                        </div><!-- end row-->
                    </div> <!-- end preview-->
                </div> <!-- end tab-content-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
</div><!-- end row -->


@endsection