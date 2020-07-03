@extends('layouts.base')

@section('css')
<style>
.btn-custom {
  color: #bdc3c7;
  background-color: #343a40;
  font-size: 18px;
  border: 1px solid #bdc3c7;
}
.btn-custom:hover {
  color: #ffffff;
  border: 1px solid #ffffff;
}
#contest-table {
  padding-top: 50px;
}
#contest-table .contest {
  margin: 0;
  padding: 0;
  font-family: 'Robot', sans-serif;
}
#contest-table .contest .contest-table {
  padding-bottom: 30px;
}
#contest-table .contest .contest-table .contest-header {
  position: relative;
  background: #343a40;
  padding: 22px 22px;
  text-align: center;
  border-top-right-radius: 4px;
  border-top-left-radius: 4px;
}
#contest-table .contest .contest-table .contest-header .contest-title {
  color: #ffffff;
  text-transform: uppercase;
  letter-spacing: 2px;
  font-size: 12px;
  text-align: center;
  font-weight: 700;
}
#contest-table .contest .contest-table .contest-header .contest-rate {
  font-size: 30px;
  font-weight: 700;
  color: #ffffff;
  position: relative;
  text-align: center;
}
#contest-table .contest .contest-table .contest-header .contest-rate sup {
  font-size: 24px;
  position: relative;
  top: -30px;
  color: #bdc3c7;
}
#contest-table .contest .contest-table .contest-header .contest-rate span {
  font-size: 16px;
  color: #bdc3c7;
  text-transform: uppercase;
}
#contest-table .contest .contest-list {
  padding: 20px 0 40px 0;
  background: #ffffff;
  border: 1px solid #e3e3e3;
}
#contest-table .contest .contest-list ul {
  padding: 0px;
  display: table;
  margin: 0px auto;
}
#contest-table .contest .contest-list ul li {
  list-style: none;
  border-bottom: 1px solid #EAECEB;
  color: #bdc3c7;
  font-size: 16px;
  line-height: 42px;
}
#contest-table .contest .contest-list ul li:last-child {
  border: none;
}
#contest-table .contest .contest-list ul li i {
  margin-right: 12px;
  color: #bdc3c7;
}
#contest-table .contest .contest-list ul li span {
  color: #34495e;
}
</style>
@endsection

@section('content')
    <section id="contest-table">
        <div class="container">
            <div class="row contest">
                @foreach($contests as $contest)
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="contest-table">
                            <div class="contest-header">
                                <p class="contest-rate">{{ $contest->name }}</p>
                                <div class="row text-center">
                                    <div class="col-6 contest-title">Starts at <br/> {{ Carbon\Carbon::parse($contest->start)->format('Y-m-d') }} <br/> {{ Carbon\Carbon::parse($contest->start)->format('H:i:00') }}</div>
                                    <div class="col-6 contest-title">Ends at <br/> {{ Carbon\Carbon::parse($contest->start)->addHours($contest->duration)->format('Y-m-d') }} <br/> {{ Carbon\Carbon::parse($contest->start)->addHours($contest->duration)->format('H:i:00') }}</div>
                                </div>
                            </div>

                            <div class="contest-list text-center">
                                    <div class="mb-3">{{ $contest->description }}</div>
                                    <div class="mb-3">Registered teams: {{ count($contest->teams) }}</div>
                                    <div class="mb-3">Total problems: {{ count($contest->problems) }}</div>
                                    @if(Auth::user()->contests->contains('id',$contest->id))
                                    <div><a href="#" class="btn btn-custom">View Contest</a></div>
                                    @else
                                      <div><a href="#" class="btn btn-custom">Register</a></div>
                                    @endif
                            </div>
                        </div>
                    </div> 
                    
                                       
                @endforeach
            </div>
        </div>
    </section>
@endsection

