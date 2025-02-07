@extends('admin.layouts.main')
@section('content')
<form action="{{url('admin/request/search')}}" method='get'>
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
    <h5> Search keyword: "{{$search}}" ({{$totalResult}})</h5>
    </div>
<div class="request_status" style="width:150px;">
  <select name="" id="statusId" class="form-control">
      <option {{( $filter == ''  ? 'selected' : '') }} value="0">all</option>
      <option {{( $filter == '1' ? 'selected' : '') }} value="1">New</option>
      <option {{( $filter == '2' ? 'selected' : '') }} value="2">Accepted</option>
      <option {{( $filter == '3' ? 'selected' : '') }} value="3">Finished</option>
      <option {{( $filter == '4' ? 'selected' : '') }} value="4">Cancel</option>
      <option {{( $filter == '5' ? 'selected' : '') }} value="5">Returning</option>
  </select>

</div>
          <table class="table">
        <thead>
          <tr>
            <th scope="col">Id</th>
            <th scope="col">email</th>
            <th scope="col">total Quanity</th>
            <th scope="col">status</th>
            <th scope="col">Action</th>
        </thead>
        <tbody>
          @foreach($data as $value)
          <tr id="my-table" class="request-{{ $value->id }}">
            <td>{{$value->id}}</td>
            <td>{{$value->user_email}}</td>
            <td>{{$value->totalQty}}</td>
            <td>
            @if ($value->status_id === 1)
                    <span class="label label-info badge badge-info"><h6>New</h6></span>
                @elseif ($value->status_id === 2)
                    <span class="label label-warning badge badge-success"><h6>accepted</h6></span>
                @elseif ($value->status_id === 3)
                    <span class="label label-danger badge badge-success"><h6>Finished</h6></span>
                @elseif ($value->status_id === 4)
                    <span class="label label-danger badge badge-danger"><h6>Cancled</h6></span>
                @else
                <span class="label label-danger badge badge-warning"><h6>Returning</h6></span>
            @endif
            </td>
            <td>
              <a href="{{route('request.edit', ['request'=>$value->id])}}" class="btn btn-primary">Detail</a>
              <a data-id="{{$value->id }}" href="javascript:void(0)" class="remove-to-cart btn btn-danger">delete</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @if($filter == 0)
      {{ $data->appends(['search'=>$search])->links() }}
      @else
      {{ $data->appends(['search'=>$search,'status'=>$filter])->links() }}
      @endif
          <!-- /.content -->
        <!-- /.content-wrapper -->


@endsection

@section('my_javascript')
<script type="text/javascript">
     
        $(function () {
          // delete request
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
                      url: " admin/request/" + id,
                        success: function (response) {
                        console.log(response);
                        // success
                          if (response.status != 'undefined' && response.status == true) {
                          // click delete row
                          $('.request-'+id).closest('tr').remove(); 
                          }
                        },
                        error: function (e) { // lỗi nếu có
                            console.log(e.message);
                        }
                    });
                }
            });
          })
          var pathname = window.location.pathname; // 
          var urlParams = new URLSearchParams(window.location.search); 
          $(document).on("change", '#statusId', function () {
                var status = $(this).val();
                if (status) {
                  if (status == '0') {
                    urlParams.delete('status');
                  } else {
                    urlParams.set('status', status);
                  }
                  window.location.href = pathname + "?"+decodeURIComponent(urlParams.toString());
                }
            });
    </script>
    @endsection