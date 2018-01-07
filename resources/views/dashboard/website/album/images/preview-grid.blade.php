@foreach($photos as $photo) <?php /** @var \App\Http\Api\Photo $photo */ ?>
<div class="preview" data-target="preview-modal" data-id="1">
    <img src="{{ $photo->getLink(\App\Http\Api\Photo::SIZE_MEDIUM) }}" alt="">
</div>
@endforeach