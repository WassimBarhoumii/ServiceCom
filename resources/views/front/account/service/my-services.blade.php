@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Account Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('front.account.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.message')
                <div class="card border-0 shadow mb-4 p-3">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">My Services</h3>
                            </div>
                            <div style="margin-top: -10px;">
                                <a href="{{ route('account.createService') }}" class="btn btn-primary">Post a Service</a>
                            </div>
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Service Created</th>
                                        <th scope="col">Applicants</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if ($services->isNotEmpty())
                                        @foreach ($services as $service)
                                        <tr class="active">
                                            <td>
                                                <div class="service-name fw-500">{{ $service->title }}</div>
                                                <div class="info1">{{ $service->serviceType->name }} . {{ $service->location }}</div>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($service->created_at)->format('d M, Y') }}</td>
                                            <td>0 Applications</td>
                                            <td>
                                                @if ($service->status == 1)
                                                <div class="service-status text-capitalize">Active</div>
                                                @else
                                                <div class="service-status text-capitalize">Block</div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-dots float-end">
                                                    <button href="#" class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="{{ route('serviceDetail',$service->id) }}"> <i class="fa fa-eye" aria-hidden="true"></i> View</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('account.editService', $service->id) }}"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="deleteService({{ $service->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif

                                    
                                </tbody>
                                
                            </table>
                        </div>
                        <div>
                            {{ $services->links() }}
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
    function deleteService(serviceId) {
        if (confirm("Are you sure you want to delete?")) {
            $.ajax({
                url: '{{ route("deleteService") }}',
                type: 'post',
                data: {serviceId: serviceId},
                dataType: 'json',
                success: function(response) {
                    window.location.href='{{ route("account.myServices") }}';
                }
            });
        }
    }
</script>
@endsection