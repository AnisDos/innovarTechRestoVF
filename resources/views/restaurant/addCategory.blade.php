
@extends('restaurant.base')



@section('content')
{{App::setLocale(Session::get('locale'))}}


<?php
use Carbon\Carbon;
?>


    <!-- Body Content Wrapper -->
    <div class="ms-content-wrapper">
      <div class="row">

        <div class="col-md-12">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb pl-0">
              <li class="breadcrumb-item"><a href="#"><i class="material-icons">home</i> Home</a></li>
              <li class="breadcrumb-item"><a href="#">Menu</a></li>
              <li class="breadcrumb-item active" aria-current="page">Add Category</li>
            </ol>
          </nav>




        
   
        







        </div>



        <div class="col-xl-6 col-md-12">
          <div class="ms-panel ms-panel-fh">
            <div class="ms-panel-header">
              <h6>{{__('Add Category Form')}}</h6>
            </div>
            <div class="ms-panel-body">
              <form method="POST"  action="{{ url('restaurant/addCategoryForm') }}"  class="needs-validation clearfix" novalidate>
                
              @csrf
                <div class="form-row">



                            
         
    

                  <div class="col-md-12 mb-3">
                    <label for="validationCustom18">{{__('Category Name')}}</label>
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
                  <button class="btn btn-primary d-block" type="submit">{{__('Add category')}}</button>
                </div>



              </form>

            </div>
          </div>

        </div>

        


        <div class="col-xl-6 col-md-12">
        <div class="ms-panel">
          <div class="ms-panel-header">
            <h6>{{__('All Categories List')}}</h6>
          </div>
          <div class="ms-panel-body">
            <div class="table-responsive">
              <table id="data-table-123" class="table w-100 thead-primary"></table>
            </div>
          </div>

        </div>
        </div>
      



<!--===============================================================================================-->
<!--===============================================================================================-->
<!--===============================================================================================-->
<!--===============================================================================================-->
<!--===============================================================================================-->




<style>
  .highcharts-figure, .highcharts-data-table table {
    min-width: 320px; 
    max-width: 660px;
    margin: 1em auto;
}

.highcharts-data-table table {
	font-family: Verdana, sans-serif;
	border-collapse: collapse;
	border: 1px solid #EBEBEB;
	margin: 10px auto;
	text-align: center;
	width: 100%;
	max-width: 500px;
}
.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}
.highcharts-data-table th {
	font-weight: 600;
    padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
    padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}
.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

        </style>
          <div class="col-xl-12 col-md-12">
            <div class="ms-panel ms-panel-fh">
              <div class="ms-panel-header header-mini">
                <div class="d-flex justify-content-between">
                  <div>
                    <h6>{{__('Categories Pie')}}</h6>
                    
                  </div>
                </div>
  
  
  
  
                <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
                <script src="https://code.highcharts.com/highcharts.js"></script>
                <script src="https://code.highcharts.com/modules/exporting.js"></script>
                <script src="https://code.highcharts.com/modules/export-data.js"></script>
                <script src="https://code.highcharts.com/modules/accessibility.js"></script>
                
                <figure class="highcharts-figure">
                    <div id="container"></div>
                    <p class="highcharts-description" >
                      This pie chart shows best categories trending sells in your restaurant and it's very useful to develop your marketing.
                    </p>
                </figure>
                
                
                <script>
                 // Build the chart
Highcharts.chart('container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'best category sales for this year,{{Carbon::now()->format('Y')}}'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Category',
        colorByPoint: true,
        data: [

  

          @foreach($categories as $category)
          {
            name: '{{$category->categoryName}}',
            y: {{$category->nbrmeal}} ,
            @if ($loop->first)
            sliced: true,
            selected: true
             @endif
        },
          @endforeach
          
         ]
    }]
});
                </script>
                
  
  
  
              </div>
              <div class="ms-panel-body">
                <canvas id="pm-chart"></canvas>
              </div>
            </div>
          </div>
  
  
  
<!--===============================================================================================-->
<!--===============================================================================================-->
<!--===============================================================================================-->
<!--===============================================================================================-->
<!--===============================================================================================-->  
































      </div>
    </div>


  
    @endsection












    
@section('script')

<script>

(function($) {
  'use strict';

   var dataSet12 = [
    @foreach ($allcategories as $category)
                            

   [ "{{ $category->id }}","  {{ $category->categoryName }}", ],
   
                            @endforeach
];









  var tableFour = $('#data-table-123').DataTable( {
    data: dataSet12,
    columns: [
      { title: "category ID" },
      { title: "category Name" },

     


    ],
  });


 




})(jQuery);

</script>

  
@endsection
