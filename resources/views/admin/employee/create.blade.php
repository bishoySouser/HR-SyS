@extends(backpack_view('blank'))

@section('content')
    <div class="row">
        <div class="col-md-8 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Create New Item</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
        
                        <!-- Add more form fields as needed -->
                        
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection