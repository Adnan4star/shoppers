@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

@section('content') {{--calling dynamic content with same name provided in parent directory--}}
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    SubCategories
                </h2>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('sub-categories.create') }}" class="btn btn-primary">New Category</a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-lg-8">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="20%">Name</th>
                                    <th width="20%">Category</th>
                                    <th width="20%">Slug</th>
                                    <th width="5%">Status</th>
                                    <th width="30%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($subCategories->IsNotEmpty())
                                    @foreach ($subCategories as $subCategory)
                                    <tr>
                                        <td>{{ $subCategory->id }}</td>
                                        <td class="text-muted">
                                            {{ $subCategory->name }}
                                        </td>
                                        <td class="text-muted">
                                            {{ $subCategory->categoryName }}
                                        </td>
                                        <td class="text-muted"><a href="#" class="text-reset">{{ $subCategory->slug }}</a></td>
                                        <td class="text-muted">
                                            {{ $subCategory->status }}
                                        </td>
                                        <td>
                                            <a href="{{route('sub-categories.edit',$subCategory->id)}}" class="btn btn-primary">Edit</a>
                                            <a href="#" onclick="deleteSubCategory({{ $subCategory->id }})" class="btn btn-danger">Delete</a>
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
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJs')
    <script>
        function deleteSubCategory(id)
        {
            var url = '{{ route("sub-categories.delete","ID") }}';
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

                        // if(response['status']){
                            window.location.href = "{{ route('sub-categories.index') }}";
                            // location.reload();
                        // }
                    }
                });
            }
        }
    </script>
@endsection
