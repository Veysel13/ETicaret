@extends('backend.layout.default')
@push('header')

@endpush
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-content pb20">
                    <x-categoryTable :restaurantId="$restaurant->id"/>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script type="text/javascript">

    </script>
@endpush
