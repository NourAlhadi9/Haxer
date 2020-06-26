@extends('layouts.base')

@section('css')
<style>
    .modal-backdrop {
        z-index: -1;
    }

    td {
        display: flex; 
        align-items: center;
        text-overflow: ellipsis!important; 
    }
</style>
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-12 card">
            <div class="card-body">
                <h5 class="card-title text-center">{{ $user->username }}'s Teams</h5>
                <h6 class="subtitle text-center">Want to join a team? head to the teams page and join your desired team</h6>
                <p class="card-text">
                    @if(count($teams) == 0)
                        @if($errors->any())
                            <div class="alert alert-danger"> 
                            @foreach ($errors->all() as $error) {{ $error }} @endforeach
                            </div>
                        @endif
                        <div class="alert alert-danger"> You have no teams captain!</div>
                    @else

                        <table class="table table-responsive row">
                            <thead class="table-light">
                                <tr class="row">
                                    <th class="col-4 col-lg-2">Team Name</th>
                                    <th class="col-4 d-none d-lg-table-cell">Team Members</th>
                                    <th class="col-4 col-lg-2">Team Country</th>
                                    <th class="col-2 d-none d-lg-table-cell">Team Institution</th>
                                    <th class="col-4 col-lg-2">Team Operations</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($teams as $team)
                                    <tr class="row" id="team-row-{{$team->id}}">
                                        <td class="col-4 col-lg-2">{{$team->name}}</td>
                                        <td class="col-4 d-none d-lg-table-cell">
                                            @foreach ($team->users as $user)
                                                <div id="team-{{$team->id}}-user-{{$user->id}}">
                                                    {{ $user->username }}

                                                    @if($team->captain === $user->username)
                                                    <i class="fa fa-copyright ml-1 mr-1" data-toggle="tooltip" data-placement="top" title="Captain"></i>
                                                    @elseif (Auth::user()->username === $team->captain)
                                                    <i class="text-danger ml-1 mr-1" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="Kick" onclick="kickFromTeam('{{$user->id}}','{{$team->id}}')">X</i>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </td>
                                        <td class="col-4 col-lg-2"><img src="{{$team->country->flag}}" height="25px" width="50px"></td>
                                        <td class="col-2 d-none d-lg-table-cell">{{ucfirst($team->institution)}}</td>
                                        <td class="col-4 col-lg-2">
                                            @if($team->captain !== Auth::user()->username)
                                                <input type="button" class="btn btn-danger" value="Leave" onclick="leaveTeam('{!!Auth::user()->id!!}','{!!$team->id!!}')">
                                            @else
                                                
                                                
                                                <button type="button" class="btn btn-warning mr-2" data-toggle="modal" data-target="#teamsModal{{$team->id}}">Update</button>
                                                <form method="POST" action="{{ route('myteams.delete',[$team->id])}}">
                                                    @csrf
                                                    @method('delete')
                                                    <input type="submit" value="Delete" class="btn btn-danger">
                                                </form>
                                                
                                                
                                                <!-- Modal -->
                                                <div class="modal fade" id="teamsModal{{$team->id}}" tabindex="-1" aria-labelledby="teamsModalLabel{{$team->id}}" aria-hidden="true" style="z-index:112;">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="teamsModalLabel{{$team->id}}">Edit Team {{$team->name}}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            
                                                            <div class="modal-body">
                                                                <form method="POST" action="{{ route('myteams.update',[$team->id])}}">
                                                                    @csrf
                                                                    <div class="mb-3">
                                                                        <label for="team-name" class="form-label">Team Name</label>
                                                                        <input value="{{$team->name}}" name="team" type="text" class="form-control" id="team-name" aria-describedby="TeamName" autocomplete="off">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <select name="country" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                                                            <option selected>Please Choose Country</option>
                                                                            @foreach($countries as $country)
                                                                                <option value="{{$country->id}}">{{$country->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <select name="institution" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                                                            <option value="invalid" selected>Please Choose Institution Type</option>
                                                                            <option value="school">School</option>
                                                                            <option value="college">College</option>
                                                                            <option value="free">Free Agent</option>
                                                                        </select>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary">Let's Go</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#teamsModal">Create Team</button>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="teamsModal" tabindex="-1" aria-labelledby="teamsModalLabel" aria-hidden="true" style="z-index:112;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="teamsModalLabel">New Team</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <form method="POST" action="{{ route('myteams.create')}}">
                        @csrf
                        <div class="mb-3">
                            <label for="team-name" class="form-label">Team Name</label>
                            <input name="team" type="text" class="form-control" id="team-name" aria-describedby="TeamName" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <select name="country" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                <option selected>Please Choose Country</option>
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}">{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <select name="institution" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                <option value="invalid" selected>Please Choose Institution Type</option>
                                <option value="school">School</option>
                                <option value="college">College</option>
                                <option value="free">Free Agent</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Let's Go</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>

    function leaveTeam(uid, tid) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This operation is irreversible, are you sure you want to leave?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Leave!'
        }).then((result) => {
            if (result.value) {
                let url = '{{ route("myteams.leave", [":tid", ":uid"]) }}';
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
                            document.getElementById('team-row-' + tid).remove();
                        }
                    }
                };
                xhttp.send();     
            }
        });
    }

    function kickFromTeam(uid, tid) {
        let url = '{{ route("myteams.kick", [":tid", ":uid"]) }}';
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

                    let el = document.getElementById('team-' + tid + '-user-' + uid);
                    el.remove();
                }
            }
        };
        xhttp.send();
    }
</script>
@endsection