@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

@section('content') {{--calling dynamic content with same name provided in parent directory--}}
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Categories
                </h2>
            </div>
            <div class="col-auto">
                <a href="{{ route('categories.create') }}" class="btn btn-primary">New Category</a>
            </div>
            <div class="my-2 my-md-2 flex-grow-1 flex-md-grow-0 order-first order-md-last col-lg-12">
                <form action="" method="get" autocomplete="off" novalidate>
                    {{-- <div class="card-title">
                        <button type="button" onclick="window.location.href='{{ route('categories.index') }}'" class="btn btn-success btn-sm form-control">Reset</button>
                    </div> --}}
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                            <svg xmlns="{{ asset('admin-assets/http://www.w3.org/2000/svg') }}" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                        </span>
                        <input type="text" name="keyword" value="{{ Request::get('keyword') }}" class="form-control" placeholder="Search…" aria-label="Search in website">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-lg-12">
                @include('admin.message')
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th width="10%">ID</th>
                                    <th width="10%"></th>
                                    <th width="20%">Name</th>
                                    <th width="20%">Slug</th>
                                    <th width="10%">Status</th>
                                    <th width="30%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($categories->IsNotEmpty())
                                    @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>
                                            @if(!empty($category->image))
                                                <img src="{{ asset('uploads/'.$category->image) }}" class="img-thumbnail" width="50" >
                                            @endif
                                        </td>
                                        <td class="text-muted">
                                            {{ $category->name }}
                                        </td>
                                        <td class="text-muted"><a href="#" class="text-reset">{{ $category->slug }}</a></td>
                                        <td class="text-muted">
                                            @if ($category->status == 1)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" viewBox="0 0 16 16" class="bi bi-check-circle" style="width: 1em; height: 1em;">
                                                    <path d="M7.177 11.97a.75.75 0 0 0 1.058 1.06l4.545-4.546a.75.75 0 1 0-1.06-1.06L7.177 11.97z"/>
                                                    <path fill-rule="evenodd" d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zM4.646 7.646a.5.5 0 0 1 .708 0L7 9.293l4.646-4.647a.5.5 0 0 1 .708.708l-5 5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 0 1 0-.708z"/>
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" viewBox="0 0 16 16" class="bi bi-x-circle" style="width: 1em; height: 1em;">
                                                    <path fill-rule="evenodd" d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zM4.646 4.646a.5.5 0 0 1 .708-.708L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1-.708-.708z"/>
                                                </svg>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('categories.edit',$category->id)}}" class="btn btn-primary">Edit</a>
                                            <a href="#" onclick="deleteCategory({{ $category->id }})" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">Records Not Found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                {{--pagination--}}
                <div class="row mt-2" data-aos="fade-up">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('customJs')
    <script>
        function deleteCategory(id)
        {
            var url = '{{ route("categories.destroy","ID") }}';
            var newUrl = url.replace("ID",id);
            newUrl = url.replace("ID",id);
            
            if(confirm("Are you sure you want to delete?")){
                $.ajax({
                    url: newUrl,
                    type: 'delete',
                    data: {}, //serialize array will get the form enteries and pass on to ajax
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response){
                        $("button[type=submit]").prop('disabled',false);

                        if(response['status']){
                            // window.location.href = "{{ route('categories.index') }}";
                            location.reload();
                        }
                    }
                });
            }
        }
    </script>
@endsection
