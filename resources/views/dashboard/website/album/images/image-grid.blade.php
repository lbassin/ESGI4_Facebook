@foreach($photos as $photo) <?php /** @var \App\Http\Api\Photo $photo */ ?>
<div class="preview" data-target="preview-modal" data-id="1">
    <div class="overlay">
        <div class="visibility">
            <i class="fa fa-eye" aria-hidden="true"></i>
        </div>
        <div class="border"></div>
        <div class="view" data-id="{{ $photo->getId() }}">
            <i class="fa fa-picture-o" aria-hidden="true"></i>
        </div>
    </div>
    <img src="{{ $photo->getLink(\App\Http\Api\Photo::SIZE_MEDIUM) }}" alt="">
</div>
@endforeach