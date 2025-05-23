@extends('front.layouts.app')

@section('main')
<section class="section-4 bg-2">    
    <div class="container pt-3 pb-3">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('services') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Back to Services</a></li>
                    </ol>
                </nav>
            </div>
        </div> 
    </div>
    <div class="container service_details_area">
        <div class="row pb-5">
            <div class="col-md-8">
                @include('front.message')
                <div class="card shadow border-0">
                    <div class="service_details_header">
                        <div class="single_services white-bg d-flex justify-content-between">
                            <div class="services_left d-flex align-items-center">
                                <div class="services_conetent">
                                    <a href="#">
                                        <h4>{{ $service->title }}</h4>
                                    </a>
                                    <div class="links_locat d-flex align-items-center">
                                        <div class="location">
                                            <p> <i class="fa fa-map-marker"></i> {{ $service->location }} </p>
                                        </div>
                                        <div class="location">
                                            <p> <i class="fa fa-clock-o"></i> {{ $service->serviceType->name }} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="services_right">
                                <div class="apply_now {{ ($count == 1) ? 'saved-service' : '' }}">
                                    <a class="heart_mark" href="javascript:void(0);" onclick="saveService({{ $service->id }})"> <i class="fa fa-heart-o" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="descript_wrap white-bg">
                        <div class="single_wrap">
                            <h4>Service Description</h4>
                            {!! nl2br($service->description) !!}
                        </div>
                        @if (!empty($service->responsibility))
                        <div class="single_wrap">
                            <h4>Responsibility</h4>
                            {!! nl2br($service->responsibility) !!}
                        </div>
                        @endif
                        @if (!empty($service->qualifications))
                        <div class="single_wrap">
                            <h4>Qualifications</h4>
                            {!! nl2br($service->qualifications) !!}
                        </div>
                        @endif   
                        @if (!empty($service->benefits))
                        <div class="single_wrap">
                            <h4>Benefits</h4>
                            {!! nl2br($service->benefits) !!}
                        </div>
                        @endif
                        <div class="border-bottom"></div>
                        <div class="pt-3 text-end">
                            @if (Auth::check())
                            <a href="javascript:void(0);" onclick="saveService({{ $service->id }})" class="btn btn-secondary">Save</a>
                            @else
                                <a href="javascript:void(0)" class="btn btn-secondary disabled">Login to Save</a>
                            @endif
                            @if (Auth::check())
                                <a href="javascript:void(0);" onclick="applyService({{ $service->id }})" class="btn btn-primary">Apply</a>
                            @else
                                <a href="javascript:void(0)" class="btn btn-primary disabled">Login to Apply</a>
                            @endif

                        </div>
                    </div>
                </div>

                @if (Auth::user())
                    @if (Auth::user()->id == $service->user_id)
                        <div class="card shadow border-0 mt-4">
                            <div class="service_details_header">
                                <div class="single_services white-bg d-flex justify-content-between">
                                    <div class="services_left d-flex align-items-center">
                                        <div class="services_conetent">
                                                <h4>Applicants</h4>
                                        </div>
                                    </div>
                                    <div class="services_right">
                                    </div>
                                </div>
                            </div>
                            <div class="descript_wrap white-bg">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile Number</th>
                                        <th>Applied Date</th>
                                    </tr>
                                    @if ($applications->isNotEmpty())
                                        @foreach ($applications as $application)
                                        <tr>
                                            <th>{{ $application->user->name }}</th>
                                            <th>{{ $application->user->email }}</th>
                                            <th>{{ $application->user->mobile }}</th>
                                            <th>{{ \Carbon\Carbon::parse($application->applied_date)->format('d M, Y') }}</th>
                                        </tr>        
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3">No Applicants Yet</td>
                                        </tr>
                                    @endif
                                    
                                </table>
                            </div>
                        </div>
                    @endif
                @endif
                
            </div>
            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="service_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Service Summary</h3>
                        </div>
                        <div class="service_content pt-3">
                            <ul>
                                <li>Published on: <span>{{ \Carbon\Carbon::parse($service->created_at)->format('d M, Y') }}</span></li>
                                <li>Vacancy: <span>{{ $service->vacancy }} {{ $service->vacancy == 1 ? 'position' : 'positions' }}</span></li>
                            @if (!empty($service->salary))
                                <li>Salary: <span>{{ $service->salary }}</span></li>
                            @endif
                                <li>Location: <span>{{ $service->location }}</span></li>
                                <li>Service Nature: <span>{{ $service->serviceType->name }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card shadow border-0 my-4">
                    <div class="service_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Company Details</h3>
                        </div>
                        <div class="service_content pt-3">
                            <ul>
                                <li>Name: <span>{{ $service->company_name }}</span></li>
                            @if (!empty($service->company_location))
                                <li>Locaion: <span>{{ $service->company_location }}</span></li>
                            @endif
                            @if (!empty($service->website))
                                <li>Webite: <span><a href="https://{{ $service->website }}">{{ $service->website }}</a></span></li>
                            @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@section('customJs')
<script type="text/javascript">
    function applyService(id) {
        if (confirm("Are you sure you want to apply on this service?")) {
            $.ajax({
                url : '{{ route("applyService") }}',
                type: 'post',
                data: {id:id},
                dataType: 'json',
                success: function(response){
                    window.location.href = "{{ url()->current() }}";
                }
            }); 
        }
    }

    function saveService(id) {
        $.ajax({
                url : '{{ route("saveService") }}',
                type: 'post',
                data: {id:id},
                dataType: 'json',
                success: function(response){
                    window.location.href = "{{ url()->current() }}";
                }
            });
    }
</script>
@endsection