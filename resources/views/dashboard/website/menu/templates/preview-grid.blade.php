<div id="templates" class="preview-grid">
    <?php /** @var \App\Model\Template $template */ ?>
    @foreach($templates as $template)
        <div class="preview" data-target="preview-modal" data-id="{{ $template->getId() }}">
            @if($template->getId() == $selectedTemplate)
                <div class="overlay">
                    <div class="selected"><i class="fa fa-check" aria-hidden="true"></i></div>
                </div>
            @endif
            <img src="{{ $template->getDesktopPreview() }}" alt="">
        </div>
    @endforeach
</div>
<div class="options">
    <div class="pagination">
        <div class="controls {{ $hideControls ? 'hide' : '' }}">
            <span class="previous">Précedent</span>
            <span class="next" {{ $nextDisabled ? 'disabled' : '' }}>Suivant</span>
        </div>
    </div>
    <div class="submit">
        <span class="next">Valider</span>
    </div>
</div>