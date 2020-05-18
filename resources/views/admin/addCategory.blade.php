
@extends('admin.base')



@section('content')

{{App::setLocale(Session::get('locale'))}}
    <!-- Body Content Wrapper -->
    <div class="ms-content-wrapper">
      <div class="row">

        <div class="col-md-12">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb pl-0">
              <li class="breadcrumb-item"><a href="#"><i class="material-icons">home</i> Home</a></li>
              <li class="breadcrumb-item"><a href="#">Menu</a></li>
              <li class="breadcrumb-item active" aria-current="page">Add Product</li>
            </ol>
          </nav>




     
         







        </div>



        <div class="col-xl-6 col-md-12">
          <div class="ms-panel ms-panel-fh">
            <div class="ms-panel-header">
              <h6>Add Category Form</h6>
            </div>
            <div class="ms-panel-body">
              <form method="POST"  action="{{ url('admin/addCategoryForm') }}"  class="needs-validation clearfix" novalidate>
                
              @csrf
                <div class="form-row">



                            
         
    

                  <div class="col-md-12 mb-3">
                    <label for="validationCustom18">Category Name</label>
                    <div class="input-group">
                      <input type="text" name="categoryName" value="{{ old('categoryName') }}"  class="form-control @error('categoryName') is-invalid @enderror" id="validationCustom18" placeholder="category Name" required >
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                      @error('categoryName')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                  </div>


                </div>



                <div class="ms-panel-header new">
                  <button class="btn btn-primary d-block" type="submit">Add category</button>
                </div>



              </form>

            </div>
          </div>

        </div>

        

      </div>
    </div>


  
    @endsection