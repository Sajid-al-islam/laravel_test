@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{ route('product.index') }}" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" id="myInput" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    
                    <select name="variant" id="" class="form-control"> 
                        @foreach ($variants as $variant)
                            <option value="{{ $variant->id }}"><strong>{{ $variant->title }}</strong></option>
                            @foreach ($variant->product_variant as $item)
                                <option value="{{ $item->variant }}">{{ $item->variant }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody id="myTable">
                    
                    @foreach ($products as $item)
                        
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td> {{ $item->title }} <br> Created at : {{ $item->created_at->diffForHumans() }}</td>
                            <td> {{ $item->description }} </td>
                            <td>
                                <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">
                                        @foreach ($item->product_variant as $variation)

                                        <dt class="col-sm-3 col-md-3 pb-0">
                                            <p>{{ $variation->variant }} /</p>
                                        </dt>

                                        @endforeach
                                        
                                    <dd class="col-sm-9 col-md-9">
                                        <dl class="row mb-0">
                                            @foreach ($item->product_variant_price as $variant_price)
                                                <dt class="col-sm-4 col-md-4 pb-0">Price : {{ number_format($variant_price->price, 2) }} <br> {{ number_format(200,2) }}</dt>
                                                <dd class="col-sm-8 col-md-8 pb-0">InStock : {{ number_format($variant_price->stock, 2) }} <br> {{ number_format(50,2) }}</dd>
                                            @endforeach
                                        </dl>
                                    </dd>
                                </dl>
                                <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('product.edit', $item->id) }}" class="btn btn-success">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    
                    
                    
                    </tbody>

                </table>
                <div class="row">
                    <div class="col-md-12">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    
                    <p> @php $to = substr($products->nextPageUrl(),-1) @endphp</p> 
                    
                    <p>Showing {{ $products->currentPage() }} to {{ $to }} out of {{ $products->lastPage() }}</p> 
                    
                </div>
                <div class="col-md-2">

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        });
    </script>   
@endsection
