@push('footer')
    @if(session('errors'))
        <script type="text/javascript">
            toastr.error("{{ is_object(session('errors')) ? session('errors')->first() : session('errors') }}", "Error");
        </script>
    @endif
@endpush
