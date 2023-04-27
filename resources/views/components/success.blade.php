@push('footer')
    @if(session('success'))
        <script type="text/javascript">
            toastr.success("{{ session('success') }}", 'Success');
        </script>
    @endif
@endpush
