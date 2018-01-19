<div id="images" class="preview-grid">
    <?php /** @var \App\Http\Api\Photo $photo */ ?>
    @foreach($photos as $photo)
        <div class="preview" data-target="preview-modal" data-id="1">
            <div class="overlay" data-id="{{ $photo->getId() }}">
                <div class="visibility">
                    <i class="fa {{ $photo->isVisible() ? 'fa-eye' : 'fa-eye-slash' }}" aria-hidden="true"></i>
                </div>
                <div class="border"></div>
                <div class="view">
                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                </div>
            </div>
            <img src="{{ $photo->getLink(\App\Http\Api\Photo::SIZE_MEDIUM) }}" alt="">
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