@extends('layouts/app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <div class="image-gradient">
    </div>
    <div class="wrapper-dashboard">
        <div class="head">
            <div class="user-pic">
                <img src="{{ $userpic }}" alt="">
            </div>
            <span class="user-name">{{ $name }}</span>
        </div>

        <div class="select-dashboard">
            <div class="select-choice">
				<div class="select-title">
                	<h1>Choisissez votre site</h1>
				</div>
				<div class="select-action">
					<select class="">
						<option value="" data-display-text="Fruits">None</option>
						<option value="apples" disabled>Apples</option>
						<option value="bananas">Bananas</option>
						<option value="oranges">Oranges</option>
						<option value="oranges">Oranges</option>
						<option value="oranges">Oranges</option>
						<option value="oranges">Oranges</option>
						<option value="oranges">Oranges</option>
					</select>
					<div class="add-page">+</div>
				</div>
            </div>


            <div id="dev-laurent">
                @forelse($pages as $page)
                    <a href="{{ route('dashboard.new', ['id' => $page['id']]) }}">{{ $page['name'] }}</a>
                @endforeach
            </div>

        </div>
    </div>

    <div class="md-modal md-effect-12">
        <div class="md-content">
			<h1>NOUVEAU SITE</h1>
			<h2>Choisissez la page à synchroniser</h2>
			<div class="flex-grid">
				<div class="flex-grid-item">
					<img src="https://phraseculte.files.wordpress.com/2017/06/south-park-cartman-0107.png?w=241&h=183" alt="">
					<span>text</span>
				</div>
				<div class="flex-grid-item">
					<img src="https://phraseculte.files.wordpress.com/2017/06/south-park-cartman-0107.png?w=241&h=183" alt="">
					<span>text</span>
				</div>
				<div class="flex-grid-item">
					<img src="https://phraseculte.files.wordpress.com/2017/06/south-park-cartman-0107.png?w=241&h=183" alt="">
					<span>text</span>
				</div>
				<div class="flex-grid-item">
					<img src="https://phraseculte.files.wordpress.com/2017/06/south-park-cartman-0107.png?w=241&h=183" alt="">
					<span>text</span>
				</div>
				<div class="flex-grid-item">
					<img src="https://phraseculte.files.wordpress.com/2017/06/south-park-cartman-0107.png?w=241&h=183" alt="">
					<span>text</span>
				</div>
				<div class="flex-grid-item">
					<img src="https://phraseculte.files.wordpress.com/2017/06/south-park-cartman-0107.png?w=241&h=183" alt="">
					<span>text</span>
				</div>
				<div class="flex-grid-item">
					<img src="https://phraseculte.files.wordpress.com/2017/06/south-park-cartman-0107.png?w=241&h=183" alt="">
					<span>text</span>
				</div>
				<div class="flex-grid-item">
					<img src="https://phraseculte.files.wordpress.com/2017/06/south-park-cartman-0107.png?w=241&h=183" alt="">
					<span>text</span>
				</div>
				<div class="flex-grid-item">
					<img src="https://phraseculte.files.wordpress.com/2017/06/south-park-cartman-0107.png?w=241&h=183" alt="">
					<span>text</span>
				</div>
				<div class="flex-grid-item">
					<img src="https://phraseculte.files.wordpress.com/2017/06/south-park-cartman-0107.png?w=241&h=183" alt="">
					<span>text</span>
				</div>
			</div>
        </div>
    </div>

    <div class="md-overlay">
        <button class="md-close">x</button>
    </div>


    <script>
function create_custom_dropdowns() {
  $('select').each(function(i, select) {
    if (!$(this).next().hasClass('dropdown')) {
      $(this).after('<div class="dropdown ' + ($(this).attr('class') || '') + '" tabindex="0"><span class="current"></span><div class="list"><ul></ul></div></div>');
      var dropdown = $(this).next();
      var options = $(select).find('option');
      var selected = $(this).find('option:selected');
      dropdown.find('.current').html(selected.data('display-text') || selected.text());
      options.each(function(j, o) {
        var display = $(o).data('display-text') || '';
        dropdown.find('ul').append('<li class="option ' + ($(o).is(':selected') ? 'selected' : '') + '" data-value="' + $(o).val() + '" data-display-text="' + display + '">' + $(o).text() + '</li>');
      });
    }
  });
}

// Event listeners

// Open/close
$(document).on('click', '.dropdown', function(event) {
  $('.dropdown').not($(this)).removeClass('open');
  $(this).toggleClass('open');
  if ($(this).hasClass('open')) {
    $(this).find('.option').attr('tabindex', 0);
    $(this).find('.selected').focus();
  } else {
    $(this).find('.option').removeAttr('tabindex');
    $(this).focus();
  }
});
// Close when clicking outside
$(document).on('click', function(event) {
  if ($(event.target).closest('.dropdown').length === 0) {
    $('.dropdown').removeClass('open');
    $('.dropdown .option').removeAttr('tabindex');
  }
  event.stopPropagation();
});
// Option click
$(document).on('click', '.dropdown .option', function(event) {
  $(this).closest('.list').find('.selected').removeClass('selected');
  $(this).addClass('selected');
  var text = $(this).data('display-text') || $(this).text();
  $(this).closest('.dropdown').find('.current').text(text);
  $(this).closest('.dropdown').prev('select').val($(this).data('value')).trigger('change');
});

// Keyboard events
$(document).on('keydown', '.dropdown', function(event) {
  var focused_option = $($(this).find('.list .option:focus')[0] || $(this).find('.list .option.selected')[0]);
  // Space or Enter
  if (event.keyCode == 32 || event.keyCode == 13) {
    if ($(this).hasClass('open')) {
      focused_option.trigger('click');
    } else {
      $(this).trigger('click');
    }
    return false;
    // Down
  } else if (event.keyCode == 40) {
    if (!$(this).hasClass('open')) {
      $(this).trigger('click');
    } else {
      focused_option.next().focus();
    }
    return false;
    // Up
  } else if (event.keyCode == 38) {
    if (!$(this).hasClass('open')) {
      $(this).trigger('click');
    } else {
      var focused_option = $($(this).find('.list .option:focus')[0] || $(this).find('.list .option.selected')[0]);
      focused_option.prev().focus();
    }
    return false;
  // Esc
  } else if (event.keyCode == 27) {
    if ($(this).hasClass('open')) {
      $(this).trigger('click');
    }
    return false;
  }
});

$(document).ready(function() {
  create_custom_dropdowns();
});


////////
////////
////////
////////




$(function () {
  
  $('.add-page').on('click', function() {
    $('.md-modal').addClass('md-show');
  });
  
  $('.md-close').on('click', function() {
    $('.md-modal').removeClass('md-show');
  });
  
});
    </script>
@endsection