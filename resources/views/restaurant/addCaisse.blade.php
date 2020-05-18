
@extends('restaurant.base')



@section('content')
{{App::setLocale(Session::get('locale'))}}


    <!-- Body Content Wrapper -->
    <div class="ms-content-wrapper">
      <div class="row">

        <div class="col-md-12">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb pl-0">
              <li class="breadcrumb-item"><a href="#"><i class="material-icons">home</i> {{__('Home')}}</a></li>
        
              <li class="breadcrumb-item"><a href="#">{{__('Caisse')}}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{__('Add Caisse')}}</li>
            </ol>
          </nav>












        </div>



        <div class="col-xl-6 col-md-12">
          <div class="ms-panel ms-panel-fh">
            <div class="ms-panel-header">
              <h6>{{__('Add Caisse Form')}}</h6>
            </div>
            <div class="ms-panel-body">
              <form method="POST"  action="{{ url('restaurant/addCaisseForm') }}"  class="needs-validation clearfix" novalidate>
                
              @csrf
                <div class="form-row">



                  <div class="col-md-12 mb-3">
                    <label for="validationCustom18">{{__('Name Caisse')}}  </label>
                    <div class="input-group">
                      <input type="text" name="caisseName" value="{{ old('caisseName') }}"  class="form-control @error('caisseName') is-invalid @enderror" id="validationCustom18" placeholder="caisse name" required >
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                      @error('caisseName')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                  </div>


                            
         
    











              


                  <div class="col-md-12 mb-3">
                    <label for="validationCustom25">{{__('Cache Init')}}</label>
                    <div class="input-group">
                      <input type="number" min="0" step=".01" value="{{ old('cacheInit') }}"  class="form-control @error('cacheInit') is-invalid @enderror " name="cacheInit" id="validationCustom25" placeholder="cache Init" required>
                     
                      @error('cacheInit')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                  </div>





                  
   
              




            
                </div>



                <div class="ms-panel-header new">
                  <button class="btn btn-primary d-block" type="submit">{{__('Add Caisse')}}</button>
                </div>



              </form>

            </div>
          </div>

        </div>



        

        

      </div>
    </div>


  
    @endsection