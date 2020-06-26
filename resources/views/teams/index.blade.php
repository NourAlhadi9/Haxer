@extends('layouts.base')

@section('css')
<style>
    td {
        align-items: center;
        text-overflow: ellipsis!important; 
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mt-5 mb-5">
        <div class="card col-sm-12 ml-0 ml-md-3 ml-lg-5 col-md-8">
            <div class="card-body">
                <h5 class="card-title text-center">Welcome H4x3R teams</h5>
                <h6 class="subtitle text-center"> Order teams?
                    <button id="order-score" type="button" class="btn btn-sm btn-primary">Score</button>
                    <button id="order-name" type="button" class="btn btn-sm btn-primary">Name</button>
                    <button id="order-country" type="button" class="btn btn-sm btn-primary">Country</button>
                    <a href="{{route('teams.index')}}" class="btn btn-sm btn-warning">Clear</a>
                </h6>
                <p class="card-text">
                    @if(count($teams) == 0)
                        @if($errors->any())
                            <div class="alert alert-danger"> 
                            @foreach ($errors->all() as $error) {{ $error }} @endforeach
                            </div>
                        @endif
                        <div class="alert alert-danger"> No teams on System!</div>
                    @else

                        <table class="table table-responsive">
                            <thead class="table-light container">
                                <tr>
                                    <div class="row">
                                        <th class="col-4 col-md-2">Team Name</th>
                                        <th class="col-lg-2 d-none d-lg-table-cell">Team Captain</th>
                                        <th class="col-4 col-md-2">Team Country</th>
                                        <th class="col-lg-2 d-none d-lg-table-cell">Team Institution</th>
                                        <th class="col-4 col-md-2">Overall Score</th>
                                        <th class="col-lg-2 d-none d-lg-table-cell">Manage Teams</th>
                                    </div>
                                </tr>
                            </thead>
                            <tbody class="bg-white container">
                                @foreach ($teams as $team)
                                    <tr class="col-12">
                                        <div class="row">
                                            <td class="col-4 col-lg-2">{{$team->name}}</td>
                                            <td class="col-lg-2 d-none d-lg-table-cell">
                                                    {{ $team->captain }}
                                                    <i class="fa fa-copyright ml-1 mr-1" data-toggle="tooltip" data-placement="top" title="Captain"></i>        
                                            </td>
                                            <td class="col-4 col-lg-2"><img src="{{$team->country->flag}}" height="25px" width="50px"  data-toggle="tooltip" data-placement="top" title="{{$team->country->name}}"></td>
                                            <td class="col-lg-2 d-none d-lg-table-cell">{{ucfirst($team->institution)}}</td>
                                            <td class="col-4 col-lg-2">{{$team->overall_score}}</td>
                                            <td class="col-lg-2 d-none d-lg-table-cell">
                                                @if(Auth::user()->teams->contains($team->id))
                                                    <a href="{{route('myteams.index')}}" class="btn btn-warning">My Teams</a>
                                                @else
                                                    <input type="button" class="btn btn-primary" value="Join" onclick="joinTeam(this,'{!!Auth::user()->id!!}','{!!$team->id!!}')">
                                                @endif
                                            </td>
                                        </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </p>
            </div>
        </div>


        <div class="card col-sm-12 ml-0 ml-md-3 col-md-3 mt-5 mt-md-0">
            <h5 class="card-title text-center mt-3">Searching for something?</h5>
            <p class="card-text">
                <div class="mb-3">
                    <label for="team-name" class="form-label">Team Name</label>
                    <input name="team-name" type="text" class="form-control" id="team-name" value="{!! request()->get('filter-name') !!}">
                </div>

                <div class="mb-3">
                    <label for="team-country" class="form-label">Team Country</label>
                    <select name="team-country" class="form-select form-select-lg mb-3" id="team-country" aria-label=".form-select-lg example">
                        <option value="" selected>Please Choose Country</option>
                        @foreach($countries as $country)
                            <option value="{{$country->id}}" {{ request()->get('filter-country') == $country->id ? 'selected' : '' }}>{{$country->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="team-institution" class="form-label">Team Institution</label>
                    <select name="team-institution" class="form-select form-select-lg mb-3" id="team-institution" aria-label=".form-select-lg example">
                        <option value="">Please Choose Institution Type</option>
                        <option {{ request()->get('filter-institution') === 'school' ? 'selected' : '' }} value="school">School</option>
                        <option {{ request()->get('filter-institution') === 'college' ? 'selected' : '' }} value="college">College</option>
                        <option {{ request()->get('filter-institution') === 'free' ? 'selected' : '' }} value="free">Free Agent</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="team-captain" class="form-label">Team Captain</label>
                    <input name="team-captain" type="text" class="form-control" id="team-captain" value="{!! request()->get('filter-captain') !!}">
                </div>

                <div class="mb-3">
                    <label for="team-max-score" class="form-label">Max Score</label>
                    <input name="team-max-score" type="text" class="form-control" id="team-max-score" value="{!! request()->get('filter-max-score') !!}">
                </div>

                <div class="mb-3">
                    <label for="team-min-score" class="form-label">Min Score</label>
                    <input name="team-min-score" type="text" class="form-control" id="team-min-score" value="{!! request()->get('filter-min-score') !!}">
                </div>
                <button id="team-filter" type="submit" class="btn btn-primary">Search</button>
            
            </p>
        </div>
    </div>
</div>

<form id="go-form" method="GET" action="{{route('teams.index')}}">
    @csrf
    <input type="hidden" name="filter-order">
    <input type="hidden" name="filter-name">
    <input type="hidden" name="filter-country">
    <input type="hidden" name="filter-institution">
    <input type="hidden" name="filter-captain">
    <input type="hidden" name="filter-min-score">
    <input type="hidden" name="filter-max-score">
</form>

@endsection

@section('js')
<script>

    let orderButtons = document.querySelectorAll('button[id^=order-]');
    let filterFields = document.querySelectorAll('[name^=team-]');
    let filterButton = document.querySelector('#team-filter');
    
    function getFilterValues() {
        let filterName = document.querySelector('[name=team-name]').value;
        let filterCountry = document.querySelector('[name=team-country]').value;
        let filterInstitution = document.querySelector('[name=team-institution]').value;
        let filterCaptain = document.querySelector('[name=team-captain]').value;
        let filterMinScore = document.querySelector('[name=team-min-score]').value;
        let filterMaxScore = document.querySelector('[name=team-max-score]').value;
        return {
            'name': filterName,
            'country': filterCountry,
            'institution': filterInstitution,
            'captain': filterCaptain,
            'min-score': filterMinScore,
            'max-score': filterMaxScore
        };
    }

    function goForm(order, filters) {
        document.querySelector('[name=filter-order]').value = order;
        Object.keys(filters).forEach(function(key) {
            document.querySelector('[name=filter-'+key+']').value = filters[key];
        });

        document.querySelector('#go-form').submit();
    }

    filterButton.addEventListener('click',function () {
        let order = "{!! request()->get('filter-order') !!}";
        let filters = getFilterValues();
        goForm(order,filters);
    });

    for (let i=0; i<orderButtons.length; i++) {
        let orderType = orderButtons[i].id.split('-')[1];
        orderButtons[i].addEventListener('click', function (){
            let order = orderType;
            let filters = getFilterValues();
            goForm(order,filters);
        });
    }

    function joinTeam(button, uid, tid) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to join the team.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Join!'
        }).then((result) => {
            if (result.value) {
                let url = '{{ route("teams.join", [":tid", ":uid"]) }}';
                url = url.replace(':tid', tid);
                url = url.replace(':uid', uid);

                let xhttp = new XMLHttpRequest();
                let csrf = document.querySelector('meta[name="csrf-token"]').content;
                xhttp.open("POST", url, true);
                xhttp.setRequestHeader("X-CSRF-TOKEN", csrf);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        let response = JSON.parse(this.responseText);
                        if (response.result === 'error') {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'Close'
                            });
                        }else {
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'Close'
                            });
                            
                            // <a href="{{route('myteams.index')}}" class="btn btn-warning">My Teams</a>
                            let a = document.createElement('a');
                            a.innerText = 'My Teams';
                            a.classList.add('btn');
                            a.classList.add('btn-warning');
                            a.href= "{{route('myteams.index')}}";
                            button.parentNode.replaceChild(a, button);
                        }
                    }
                };
                xhttp.send();     
            }
        });
    }

</script>
@endsection

