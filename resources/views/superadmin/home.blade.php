




@extends('superadmin.base')



@section('content')
{{App::setLocale(Session::get('locale'))}}
<?php

use Carbon\Carbon;
?>






<div class="ms-content-wrapper">
  <div class="row">

   

    

    <div class="col-md-12">
      <h1 class="db-header-title">{{__('Welcome')}}, {{Auth::user()->superadmin->name}}</h1>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
      <div class="ms-card ms-widget has-graph-full-width ms-infographics-widget">
        <span class="ms-chart-label bg-black"><i class="material-icons"></i>{{Carbon::now()->year}}</span>
        <div class="ms-card-body media">
          <div class="media-body">
            <span class="black-text"><strong>Total Customers</strong></span>
            <h2>{{$totalCustomers}}</h2>
          </div>
        </div>
        <canvas id="line-chart"></canvas>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
      <div class="ms-card ms-widget has-graph-full-width ms-infographics-widget">
        <span class="ms-chart-label bg-red"><i class="material-icons"></i>active</span>
        <div class="ms-card-body media">
          <div class="media-body">
            <span class="black-text"><strong>Total Admins</strong></span>
          <h2>{{$totalAdminActives}}</h2>
          </div>
        </div>
        <canvas id="line-chart-2"></canvas>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
      <div class="ms-card ms-widget has-graph-full-width ms-infographics-widget">
        <span class="ms-chart-label bg-black"><i class="material-icons"></i>active</span>
        <div class="ms-card-body media">
          <div class="media-body">
            <span class="black-text"><strong>Total Restaurants</strong></span>
            <h2> {{$totalRestauransActives}} </h2>
          </div>
        </div>
        <canvas id="line-chart-3"></canvas>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
      <div class="ms-card ms-widget has-graph-full-width ms-infographics-widget">
        <span class="ms-chart-label bg-red"><i class="material-icons"></i>active</span>
        <div class="ms-card-body media">
          <div class="media-body">
            <span class="black-text"><strong>Total Employees</strong></span>
            <h2> {{$totalEmployeesActives}} </h2>
          </div>
        </div>
        <canvas id="line-chart-4"></canvas>
      </div>
    </div>
   
   














































    









<!--===============================================================================================-->
<!--===============================================================================================-->
<!--===============================================================================================-->
<!--===============================================================================================-->
<!--===============================================================================================-->




<style>
  .highcharts-figure, .highcharts-data-table table {
min-width: 360px; 
max-width: 800px;
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
            <h6>Admins Revenue</h6>
           
          </div>
        </div>




        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/series-label.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        
        <figure class="highcharts-figure">
            <div id="container"></div>
            <p class="highcharts-description">
                Basic line chart showing trends in a dataset. This chart includes the
                <code>series-label</code> module, which adds a label to each line for
                enhanced readability.
            </p>
        </figure>
        
        <script>
          Highcharts.chart('container', {

title: {
text: 'Revenue of all active Admins in your system,  {{Carbon::now()->year}}'
},

subtitle: {
text: ''
},

yAxis: {
title: {
  text: 'SAR'
}
},
tooltip: {
headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
'<td style="padding:0"><b>{point.y:.2f} SAR</b></td></tr>',
footerFormat: '</table>',
shared: true,
useHTML: true
},

xAxis: {
  categories: [
      'Jan',
      'Feb',
      'Mar',
      'Apr',
      'May',
      'Jun',
      'Jul',
      'Aug',
      'Sep',
      'Oct',
      'Nov',
      'Dec'
    ],
   
},

legend: {
layout: 'vertical',
align: 'right',
verticalAlign: 'middle'
},

plotOptions: {
    column: {
      pointPadding: 0.2,
      borderWidth: 0
    }
  },

series: [

  @foreach ($charts as $chart)
  {
name: '{{ $chart[0] }}',
data: [  {{ $chart[1][0] }}, {{ $chart[1][1] }}, {{ $chart[1][2] }}, {{ $chart[1][3] }}, {{ $chart[1][4] }}, {{ $chart[1][5] }}, {{ $chart[1][6] }}, {{ $chart[1][7] }}, {{ $chart[1][8] }}, {{ $chart[1][9] }}, {{ $chart[1][10] }}, {{ $chart[1][11] }},]
}, 
  @endforeach



],

responsive: {
rules: [{
  condition: {
      maxWidth: 500
  },
  chartOptions: {
      legend: {
          layout: 'horizontal',
          align: 'center',
          verticalAlign: 'bottom'
      }
  }
}]
}

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






<!--===============================================================================================-->
<!--===============================================================================================-->
<!--===============================================================================================-->
<!--===============================================================================================-->
<!--===============================================================================================-->



  <div class="col-xl-12 col-md-12">
    <div class="ms-panel ms-panel-fh">
      <div class="ms-panel-header header-mini">
        <div class="d-flex justify-content-between">
          <div>
            <h6>Admins Expenses </h6>
      
          </div>
        </div>




    
        
        <figure class="highcharts-figure">
            <div id="container1"></div>
            <p class="highcharts-description">
                Basic line chart showing trends in a dataset. This chart includes the
                <code>series-label</code> module, which adds a label to each line for
                enhanced readability.
            </p>
        </figure>
        
        <script>
          Highcharts.chart('container1', {

title: {
text: 'Expenses of all active Admins in your system,  {{Carbon::now()->year}}'
},

subtitle: {
text: ''
},

yAxis: {
title: {
  text: 'SAR'
}
},
tooltip: {
headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
'<td style="padding:0"><b>{point.y:.2f} SAR</b></td></tr>',
footerFormat: '</table>',
shared: true,
useHTML: true
},

xAxis: {
  categories: [
      'Jan',
      'Feb',
      'Mar',
      'Apr',
      'May',
      'Jun',
      'Jul',
      'Aug',
      'Sep',
      'Oct',
      'Nov',
      'Dec'
    ],
   
},

legend: {
layout: 'vertical',
align: 'right',
verticalAlign: 'middle'
},

plotOptions: {
    column: {
      pointPadding: 0.2,
      borderWidth: 0
    }
  },

series: [

  @foreach ($chartsExpenses as $chart)
  {
name: '{{ $chart[0] }}',
data: [  {{ $chart[1][0] }}, {{ $chart[1][1] }}, {{ $chart[1][2] }}, {{ $chart[1][3] }}, {{ $chart[1][4] }}, {{ $chart[1][5] }}, {{ $chart[1][6] }}, {{ $chart[1][7] }}, {{ $chart[1][8] }}, {{ $chart[1][9] }}, {{ $chart[1][10] }}, {{ $chart[1][11] }},]
}, 
  @endforeach



],

responsive: {
rules: [{
  condition: {
      maxWidth: 500
  },
  chartOptions: {
      legend: {
          layout: 'horizontal',
          align: 'center',
          verticalAlign: 'bottom'
      }
  }
}]
}

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













































































    <!-- Recent Placed Orders< -->
    <div class="col-12">
      <div class="ms-panel">
        <div class="ms-panel-header">
          <h6>Recently Placed Orders</h6>
        </div>
        <div class="ms-panel-body">
          <div class="table-responsive">
            <table class="table table-hover thead-primary">
              <thead>
                <tr>
                  <th scope="col">Order ID</th>
                  <th scope="col">Order Name</th>
                  <th scope="col">Customer Name</th>
                  <th scope="col">Location</th>
                  <th scope="col">Order Status</th>
                
                  <th scope="col">Price</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">1</th>
                  <td>French Fries</td>
                  <td>Jhon Leo</td>
                  <td>New Town</td>
                  <td><span class="badge badge-primary">Pending</span>
                  </td>
             
                  <td>$10</td>
                </tr>
                <tr>
                  <th scope="row">2</th>
                  <td>Mango Pie</td>
                  <td>Kristien</td>
                  <td>Old Town</td>
                  <td><span class="badge badge-dark">Cancelled</span>
                  </td>
               
                  <td>$9</td>
                </tr>
                <tr>
                  <th scope="row">3</th>
                  <td>FrieD Egg Sandwich</td>
                  <td>Jack Suit</td>
                  <td>Oxford Street</td>
                  <td><span class="badge badge-success">Delivered</span>
                  </td>
            
                  <td>$19</td>
                </tr>
                <tr>
                  <th scope="row">4</th>
                  <td>Lemon Yogurt Parfait</td>
                  <td>Alesdro Guitto</td>
                  <td>Church hill</td>
                  <td><span class="badge badge-success">Delivered</span>
                  </td>
            
                  <td>$18</td>
                </tr>
                <tr>
                  <th scope="row">5</th>
                  <td>Spicy Grill Sandwich</td>
                  <td>Jacob Sahwny</td>
                  <td>palace Road</td>
                  <td><span class="badge badge-success">Delivered</span>
                  </td>
              
                  <td>$21</td>
                </tr>
                <tr>
                  <th scope="row">6</th>
                  <td>Chicken Sandwich</td>
                  <td>Peter Gill</td>
                  <td>Street 21</td>
                  <td><span class="badge badge-primary">Pending</span>
                  </td>
              
                  <td>$15</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- Recent Orders< -->

    
   
    












    








  </div>
</div>






  
@endsection