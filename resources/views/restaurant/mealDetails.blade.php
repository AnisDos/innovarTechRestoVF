
@extends('restaurant.base')



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
              <li class="breadcrumb-item active" aria-current="page">Meal Detail</li>
            </ol>
          </nav>
        </div>
         <div class="col-md-12">
          <div class="ms-panel">
            <div class="ms-panel-header">
              <h6>Meal Details</h6>
            </div>
            <div class="ms-panel-body">

              <div id="arrowSlider" class="ms-arrow-slider carousel slide" data-ride="carousel" data-interval="false">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img class="d-block w-100" style="height: 400px;" src="/storage/{{$meal->image}}" alt="First slide">
                    <div class="carousel-caption d-none d-md-block">
                      <h3 class="text-white">{{$meal->mealName}} img </h3>
                    </div>
                  </div>
                
                </div>
              
              </div>
            </div>
          </div>
        </div>


<div class=" col-md-6">
          <div class="ms-panel ms-panel-fh">
            <div class="ms-panel-body">
              <h4 class="section-title bold">Meal Info</h4>
              <table class="table ms-profile-information">
                <tbody>

                  <tr>
                    <th scope="row">Price</th>
                    <td>{{ $meal->price }} SAR </td>
                  </tr>
                  <tr>
                    <th scope="row">Product Category</th>
                    <td>{{ $meal->category->categoryName }}</td>
                  </tr>
                  <tr>
                    <th scope="row">Public</th>
                 <td><span class="badge badge-pill badge-primary">@if($meal->public ) active @else deactive @endif</span></td>
                  </tr>
                  <tr>
                    <th scope="row">Delivery Charges</th>
                    <td>Free</td>
                  </tr>

                  <tr>
                    <th scope="row">SKU Identification</th>
                    <td>23445</td>
                  </tr>

                </tbody>
              </table>
              <div class="new">
                <a href="/restaurant/updateMeal/{{$meal->id}}" class="btn btn-primary">Edit</a>


                @if($meal->public )
                <form method="POST" action="/restaurant/deactivateMeal" >
                  @csrf
                  <input type="hidden" name="meal_id" value="{{$meal->id}}" >
                  <button type="submit" class="btn btn-secondary">Deactivate</button>
                </form>
             
               
                 @else 
                 <form method="POST" action="/restaurant/activateMeal" >
                  @csrf
                  <input type="hidden" name="meal_id" value="{{$meal->id}}" >
                  <button type="submit" class="btn btn-success">Activate</button>
                </form>
             
                 @endif
           



              </div>

            </div>
          </div>
        </div>

        <div class=" col-md-6">
          <div class="ms-panel ms-panel-fh">
            <div class="ms-panel-body">

              <h4 class="section-title bold">Meal Details </h4>
              <p class="description">{{ $meal->description }}.</p>


            </div>
            <div class="ms-quick-stats">
                <div class="ms-stats-grid">
                  <i class="fa fa-bullhorn"></i>
                <p class="ms-text-dark">{{$totalOrders}}</p>
                  <span>Today Order</span>
                </div>
                <div class="ms-stats-grid">
                  <i class="fa fa-heart"></i>
                  <p class="ms-text-dark">3,039</p>
                  <span>Favourite</span>
                </div>
              </div>
          </div>
        </div>




      </div>
    </div>

  
    @endsection
