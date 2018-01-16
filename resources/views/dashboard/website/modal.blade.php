<div id="{{ $name }}" class="md-modal md-effect-12">
    <div class="md-content">
        @if(!isset($content))
            <!-- Ajax -->
        @else
            @include($content)
        @endif
    </div>
</div>