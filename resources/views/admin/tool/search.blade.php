@extends('admin.layouts.main')
@section('content')
    <!-- /.content-header -->
    <form action="{{url('admin/tool/search')}}" method='get'>
    <div class=searh-form>
                <div class="search-input-group rounded">
                <input type="search" class="form-control rounded" name="search" placeholder="Search" aria-label="Search"
                    aria-describedby="search-addon" />
                <button class="input-group-text border-0" type="submit" id="search-addon">
                    <i class="fas fa-search"></i>
                </div>
    </div>
    </form>
    <div class="p-2" style="text-align:center;">
    <h5> Search keyword: "{{$keyword}}" ({{$totalResult}})</h5>
    </div>
    
    <!-- Main content -->
    @can('edit tools')
    <a href="{{route('tool.create')}}" class="nav-link btn btn-success" style="width:120px;" ><span class="mr-2">CREATE</span><i class="fas fa-plus"></i></a>
    @endcan
          <table class="table">
        <thead>
          <tr>
            <th scope="col">Id</th>
            <th scope="col">Name</th>
            <th scope="col">Image</th>
            <th scope="col">Quanity</th>
            @can('edit tools')
            <th scope="col">Action</th>
            @endcan
        </thead>
        <tbody>
          @foreach($data as $value)
          <tr class="tool-{{ $value->id }}">
            <td>{{$value->id}}</td>
            <td>{{$value->name}}</td>
            <td><img src="{{asset('images/'.$value->image)}}" alt="" width=50 height=50></td>
            <td>{{$value->quanity}}</td>
            @can('edit tools')
            <td>
              <a href="{{route('tool.edit', ['tool'=>$value->id])}}" class="btn btn-primary">Edit</a>
                
            <a data-id="{{$value->id}}" href="javascript:void(0)" class="remove-to-cart btn btn-danger" class="text-dark ">Delete</a>
            </td>
            @endcan
          </tr>
          @endforeach
        </tbody>
      </table>
      {{ $data->appends(['search'=>$keyword])->links() }}
      
          <!-- /.content -->
        <!-- /.content-wrapper -->
@endsection
@section('my_javascript')
<script type="text/javascript">

        $(function () {
    
          //delete
            $(document).on("click", '.remove-to-cart', function () {
                var result = confirm("Are you sure you want to delete?");
                if (result) {
                    var id = $(this).attr('data-id');
                    $.ajax({
                      type: 'delete',
                      dataType: 'json',
                      data: {
                        "_token": "{{ csrf_token() }}",
                        id : id,
                        },
                      url: " admin/tool/" + id,
                        success: function (response) {
                        console.log(response);
                        // success
                          if (response.status != 'undefined' && response.status == true) {
                          //  delete row
                          $('.tool-'+id).closest('tr').remove(); // class .item- ở trong class của thẻ td đã khai báo trong file index
                          }
                        },
                        error: function (e) { // lỗi nếu có
                            console.log(e.message);
                        }
                    });
                }
            });
          })
    </script>


@endsection