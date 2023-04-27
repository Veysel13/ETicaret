<div class="table-responsive table-container">
    <table class="table table-striped table-bordered" cellspacing="0" width="100%"
           id="{{ $divId }}"
           cellspacing="0"
           width="100%"
           data-sort="{{ $sort }}"
           data-ajaxUrl="{{ $url }}"
           data-ajaxMethod="{{ $method }}"
           data-rowClick="{{ $rowClick }}"
           data-pageLength="{{ $pageLength }}"
           data-lengthChange="{{ $lengthChange }}"
    >
        <thead>
        {{ $slot }}
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
