
@extends('admin.base')



@section('content')


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




          
        <script type="text/javascript" > 
          setTimeout(function() {
       $('#successalert').fadeOut('fast');
     }, 8000); // <-- time in milliseconds
     </script>
    
   
        
        @if (session('success'))
        <div class="x_content bs-example-popovers" id="successalert" >
          <div class="alert alert-success" role="alert" >
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <strong>well done!</strong> {{ session('success') }}
            </div>
          </div>

        
          @endif

         







        </div>



        <div class="col-xl-12 col-md-12">
          <div class="ms-panel ms-panel-fh">
            <div class="ms-panel-header">
              <h6>Add Product Form</h6>
            </div>
            <div class="ms-panel-body">
              <form method="POST"  action="{{ url('admin/addProductForm') }}" enctype="multipart/form-data" class="needs-validation clearfix" novalidate>
                
              @csrf
                <div class="form-row">



                            
         
    

                  <div class="col-md-12 mb-3">
                    <label for="validationCustom18">Product Name</label>
                    <div class="input-group">
                      <input type="text" name="productName" value="{{ old('productName') }}"  class="form-control @error('productName') is-invalid @enderror" id="validationCustom18" placeholder="Product Name" required >
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                      @error('productName')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                  </div>







                  
                  <div class="col-md-12 mb-3">
                    <label for="validationCustom18">unity</label>
                    <div class="input-group">
                      <input type="text" name="unity" value="{{ old('unity') }}"  class="form-control @error('unity') is-invalid @enderror" id="validationCustom18" placeholder="unity" required >
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                      @error('unity')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                  </div>










                  <div class="col-md-12 mb-3">
                    <label for="validationCustom18">tax</label>
                    <div class="input-group">
                      <input type="number" name="tax" value="{{ old('tax') }}"  class="form-control @error('tax') is-invalid @enderror" id="validationCustom18" placeholder="tax" required >
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                      @error('tax')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                  </div>






               


                  <div class="col-md-12 mb-3">
                    <label for="validationCustom18">limiteSTK</label>
                    <div class="input-group">
                      <input type="text" name="limiteSTK" value="{{ old('limiteSTK') }}"  class="form-control @error('limiteSTK') is-invalid @enderror" id="validationCustom18" placeholder="limiteSTK" required >
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                      @error('limiteSTK')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                  </div>



             


                  
                





            
                </div>



                <div class="ms-panel-header new">
                  <button class="btn btn-primary d-block" type="submit">Add Product</button>
                </div>



              </form>

            </div>
          </div>

        </div>



        

        

      </div>
    </div>











  
    @endsection




    